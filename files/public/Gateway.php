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

$requestUri = $_SERVER['REQUEST_URI'];
$path       = explode('?', $requestUri)[0];
$segments   = array_filter(explode('/', $path));
$segments   = array_values($segments);

// Check if we have the minimum required segments
if (count($segments) < 2) {
    echo json_encode(response(false, null, 'Invalid API endpoint'));
    die();
}

$class      = ucfirst($segments[0]);
$action     = $segments[1];
$request    = json_decode(file_get_contents('php://input'), true);
if (json_last_error() != 0) {
    echo json_encode(response(false, null, 'Unexpected request body'));
    die();
}

try {
    $result = api_call( $class, $action, $request );
    echo json_encode(response(true, $result));
} catch (Exception $e) {
    echo json_encode(response(false, null, $e->getMessage()));
    die();
}


