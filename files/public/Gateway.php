<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

//
$requestUri = $_SERVER['REQUEST_URI'];
$path       = explode('?', $requestUri)[0];
$segments   = array_filter(explode('/', $path));
$segments   = array_values($segments);

// Check if we have the minimum required segments
if (count($segments) < 2) {
    $response = response(false, null, 'Invalid API endpoint');
    echo json_encode($response);
    die();
}

$class  = ucfirst($segments[0]);
$action = $segments[1];

if (!preg_match('/^[a-zA-Z0-9_]+$/', $class)) {
    $response = response(false, null, 'Invalid class name');
    echo json_encode($response);
    die();
}

// Look for the class file
$file = getenv("PROJECT_ROOT") . 'classes/' . $class . '.php';
if (!file_exists($file)) {
    $response = response(false, null, 'Class not found in ' . $file);
    echo json_encode($response);
    die();
}

// Check if the class exists
$class = "MemoryLane\\$class";
if (!class_exists($class)) {
    $response = response(false, null, 'Class not found');
    echo json_encode($response);
    die();
}

// Create instance of the class
$instance = new $class(DB);

// Check if the action method exists
if (!method_exists($instance, $action) || !is_callable([$instance, $action])) {
    $response = response(false, null, 'Action not found');
    echo json_encode($response);
    die();
}

$request = json_decode(file_get_contents('php://input'), true);
if (json_last_error() != 0) {
    $response = response(false, null, 'Unexpected request body');
    echo json_encode($response);
    die();
}

try {
    
    // $result = "VOY A LLAMAR A " . $class . " -> " . $action;
    $reflection         = new \ReflectionMethod($class, $action);
    $method_parameters  = $reflection->getParameters();
    
    // Prepare parameters for the method call
    $call_parameters = [];
    
    foreach ($method_parameters as $param) {
        $param_name = $param->getName();
        
        // Check if parameter is missing and has no default value
        if (!isset($request[$param_name]) && !$param->isDefaultValueAvailable()) {
            $response = response(false, null, 'Required parameter '.$param_name.' missing');
            echo json_encode($response);
            die();
        }
        
        // Add parameter value to call parameters array, using default if not provided
        $call_parameters[] = $request[$param_name] ?? $param->getDefaultValue();
    }
    
    // Call the method with the prepared parameters
    $result     = $reflection->invokeArgs($instance, $call_parameters);
    $response   = response(true, $result);
    echo json_encode($response);
    die();
} catch (Exception $e) {
    echo json_encode(response(false, $e, $e->getMessage()));
    die();
}