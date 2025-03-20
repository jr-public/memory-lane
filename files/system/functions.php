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
	return $_COOKIE["auth_token"];
}
function device_id() {
    $userAgent 		= $_SERVER['HTTP_USER_AGENT'] ?? 'NO_USER_AGENT';
    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'NO_LANG';
    $ipAddress 		= $_SERVER['REMOTE_ADDR'];
    
    // Create a device fingerprint
    $device_id = hash('sha256', $userAgent . $acceptLanguage . $ipAddress);
    // $device_id = $userAgent . $acceptLanguage . $ipAddress;
    
    return $device_id;
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