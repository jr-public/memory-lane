<?php
// Define constants
session_start();
require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');

// Only accept POST requests for security
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false, 
        'message' => 'Only POST requests are allowed'
    ]);
    exit;
}

// Get the request data
$request_data = json_decode(file_get_contents('php://input'), true);

// Validate request data
if (!$request_data || !isset($request_data['controller']) || !isset($request_data['action'])) {
    http_response_code(400); // Bad Request
    echo json_encode(response(false, null,'Invalid request data. Required fields: controller, action'));
    die();
}

// Extract request parameters
$controller = $request_data['controller'];
$action = $request_data['action'];
$params = $request_data['params'] ?? [];

// Security check: Validate controller and action names to prevent injection
if (!preg_match('/^[a-zA-Z0-9_]+$/', $controller) || !preg_match('/^[a-zA-Z0-9_]+$/', $action)) {
    http_response_code(400);
    echo json_encode(response(false, null,'Invalid controller or action name'));
    die();
}

// Get JWT from the session or cookies
$jwt = get_cookie();

// Check if JWT exists
if (!$jwt) {
    http_response_code(401); // Unauthorized
    echo json_encode(response(false, null,'Authentication required'));
    die();
}

// Make API call with JWT
try {
    // Assuming api_call is already defined in your system
    // and can accept a JWT parameter (or gets it from session)
    $result = external_api_call($controller, $action, $params);
    
    // Return the result to the frontend
    header('Content-Type: application/json');
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false, 
        'message' => 'API call failed: ' . $e->getMessage()
    ]);
}