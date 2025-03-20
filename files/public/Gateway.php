<?php
/**
 * API Gateway
 * 
 * This file serves as the entry point for all API requests.
 * URL format: www.website.com/className/action/[id]
 */

// Enable error reporting during development
// Comment out or modify for production
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include autoloader (assuming PSR-4 autoloading)
require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');

// Handle CORS if needed
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

function parseRequest() {
    $requestUri = $_SERVER['REQUEST_URI'];
    $path       = explode('?', $requestUri)[0];
    $segments   = array_filter(explode('/', $path));
    return array_values($segments);
}

/**
 * Route the request to the appropriate class and method
 */
function routeRequest($segments) {
    // Check if we have the minimum required segments
    if (count($segments) < 2) {
        sendResponse(404, ['error' => 'Invalid API endpoint']);
        return;
    }
    
    // Extract className and action
    $className = ucfirst($segments[0]);
    $action = $segments[1];
    
    // Extract ID if present
    $id = null;
    if (isset($segments[2])) {
        $id = $segments[2];
    }
    
    // Validate className (basic security check)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $className)) {
        sendResponse(400, ['error' => 'Invalid class name']);
        return;
    }

    // Look for the class file
    $classFile = getenv("PROJECT_ROOT") . '/classes/' . $className . '.php';
    
    if (!file_exists($classFile)) {
        sendResponse(404, ['error' => 'Class not found in '. getenv("PROJECT_ROOT") . '/classes/' . $className . '.php']);
        return;
    }
    
    // Check if the class exists
    $fullClassName = "MemoryLane\\$className";
    
    if (!class_exists($fullClassName)) {
        sendResponse(404, ['error' => 'Class not found']);
        return;
    }
    
    // Create instance of the class
    $instance = new $fullClassName(DB);
    
    // Check if the action method exists
    if (!method_exists($instance, $action)) {
        sendResponse(404, ['error' => 'Action not found']);
        return;
    }
    
    try {
        // // Determine HTTP method
        // $httpMethod = $_SERVER['REQUEST_METHOD'];
        
        // // Get request data based on HTTP method
        // $requestData = [];
        
        // switch ($httpMethod) {
        //     case 'GET':
        //         $requestData = $_GET;
        //         break;
        //     case 'POST':
        //         $requestData = $_POST;
        //         // If JSON was sent in the request body
        //         $jsonData = json_decode(file_get_contents('php://input'), true);
        //         if ($jsonData) {
        //             $requestData = array_merge($requestData, $jsonData);
        //         }
        //         break;
        //     case 'PUT':
        //     case 'DELETE':
        //         $jsonData = json_decode(file_get_contents('php://input'), true);
        //         if ($jsonData) {
        //             $requestData = $jsonData;
        //         }
        //         break;
        // }
        
        // // Determine how to call the method based on whether we have an ID
        // if ($id !== null) {
        //     $result = $instance->$action($id, $requestData);
        // } else {
        //     $result = $instance->$action($requestData);
        // }
        
        // // Send successful response
        // sendResponse(200, $result);
        
        $result = "VOY A LLAMAR A " . $fullClassName . " -> " . $action . " " . $id;
        // Send successful response
        sendResponse(200, $result);
    } catch (Exception $e) {
        // Handle exceptions
        sendResponse(500, ['error' => $e->getMessage()]);
    }
}

/**
 * Send JSON response with appropriate HTTP status code
 */
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

// Main execution
$segments = parseRequest();
// echo json_encode($segments);


routeRequest($segments);