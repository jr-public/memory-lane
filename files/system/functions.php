<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_token(array $data, string $device ): string {
	$issued_at = time();
	$expiration = $issued_at + 3600; // Token valid for 1 hour

	$payload = [
		'iat' => $issued_at,           // Issued at
		'exp' => $expiration,          // Expiration time
		'sub' => $data['id'], // Subject (client ID)
		'data' => $data,
		'dev' => $device
	];

	return JWT::encode($payload, getenv("JWT_SECRET"), 'HS256');
}
function verify_token(string $token, string $device): array {
	try {
		$decoded = JWT::decode($token, new Key(getenv("JWT_SECRET"), 'HS256'));
		if ( $decoded->dev != $device ) return response(false, null, 'BAD_DEVICE');
		return response(true,$decoded);
	} catch (\Exception $e) {
		return response(false,[$e],$e->getMessage());
	}
}
function set_cookie( $jwt, $expiration = null) {
	setcookie('auth_token', $jwt, [
		'expires' => $expiration ?? time() + 3600,
		'path' => '/',
		'domain' => $_SERVER['SERVER_NAME'],
		// 'secure' => true,     // Only send over HTTPS
		'httponly' => true,   // Not accessible via JavaScript
		'samesite' => 'Lax'   // Protects against CSRF
	]);
}
function get_cookie() {
    $cook = isset($_COOKIE["auth_token"]) ? $_COOKIE["auth_token"] : null;
	return $cook;
}
function device_id() {
    // $userAgent 		= $_SERVER['HTTP_USER_AGENT'] ?? 'NO_USER_AGENT';
    // $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'NO_LANG';
    // $ipAddress 		= $_SERVER['REMOTE_ADDR'];
    
    // // Create a device fingerprint
    // $device_id = hash('sha256', $userAgent . $acceptLanguage . $ipAddress);
    // // $device_id = $userAgent . $acceptLanguage . $ipAddress;
    $device_id = "NODEVICE";
    return $device_id;
}

function api_call( $controller, $action, $request = [] ) {
    
    $backtrace  = debug_backtrace();
    $caller     = basename($backtrace[0]['file']);
    
    $skip_auth  = false;
    if ( $controller === 'User' && in_array($action, ['authenticate', 'create']) ) $skip_auth = true;
    elseif ( $controller === 'Client' && in_array($action, ['create']) ) $skip_auth = true;
    if (!$skip_auth) {
        // Get JWT token - either from request headers (Gateway) or cookies (internal)
        $token = null;
        if ($caller === 'Gateway.php') {
            // Extract from Authorization header
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $auth = explode(' ', $headers['Authorization']);
                if (count($auth) === 2 && strtolower($auth[0]) === 'bearer') {
                    $token = $auth[1];
                }
            }
        } else {
            // Get from cookie for internal calls
            $token = get_cookie();
        }
        
        // Validate token
        if (!$token) {
            return response(false, null, 'Authentication required');
        }
        
        $res = verify_token($token, device_id());
        if (!$res['success']) {
            // LOG?
            return $res;
        }
    }

    // VALIDATIONS
    // Validate controller name contains only alphanumeric characters and underscores
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $controller)) {
        return response(false, null, 'Invalid class name');
    }
    // Check if the controller file exists in the specified path
    $file = getenv("PROJECT_ROOT") . 'classes/' . $controller . '.php';
    if (!file_exists($file)) {
        return response(false, null, 'Class not found in ' . $file);
    }
    // Verify the class exists in the MemoryLane namespace
    $class = "MemoryLane\\$controller";
    if (!class_exists($class)) {
        return response(false, null, 'Class not found');
    }
    // Ensure the requested action exists and is callable on the controller instance
    $instance = new $class(DB); // Could be any of the MemoryLance namespace classes
    if (!method_exists($instance, $action) || !is_callable([$instance, $action])) {
        return response(false, null, 'Action not found: '. $action);
    }
    
    // Use reflection to analyze the method parameters
    $reflection = new \ReflectionMethod($instance, $action);
    $m_params   = $reflection->getParameters(); // Method parameters
    $c_params   = []; // Call parameters
    foreach ($m_params as $param)  {
        $param_name = $param->getName();
        // Check if required parameter exists in request
        if (!isset($request[$param_name]) && !$param->isDefaultValueAvailable()) {
            return response(false, null, 'Required parameter ' . $param_name . ' missing');
        }
        $c_params[] = $request[$param_name] ?? $param->getDefaultValue();
    }
    // All MemoryLane class methods must return response() for consistency
    return $reflection->invokeArgs($instance, $c_params);
}
function external_api_call( $controller, $action, $request = [] ) {

    // Get JWT from the session or cookies
    $jwt = get_cookie();
    if (!$jwt) {
        return response(false, null,'Authentication required');
    }

    // Initialize cURL session
    $ch = curl_init('http://localhost/'.strtolower($controller).'/'.strtolower($action));
    
    // Encode payload as JSON
    $jsonPayload = json_encode($request);
    if (json_last_error() != 0) {
        return response(false,[],'Failed to encode JSON payload: ' . json_last_error_msg());
    }
    
    // Set cURL options
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,     // Return response as string
        CURLOPT_POST => true,               // Set request method to POST
        CURLOPT_POSTFIELDS => $jsonPayload, // Set JSON payload
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $jwt
        ]
    ]);
    
    // Execute the request
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        
        return response(false,null,'cURL Error: ' . $error, $statusCode);
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Try to parse JSON response
    $responseData = json_decode($response, true);
    if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
        return response(false,null,'Invalid JSON response: ' . json_last_error_msg(), $statusCode);
    }
    
    return $responseData;
}
function response( bool $success = true, $data = null, ?string $message = null, ?int $code = null ): array {
    $response = [
		"success" 	=> $success,
		"data"		=> $data,
		"message" 	=> $message,
		"code"		=> $code
	];
	return $response;
}
?>