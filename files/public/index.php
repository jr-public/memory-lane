<?php

// header("Location: /memory-lane.com");

/**
 * Make a POST request to a configured endpoint with JSON body and JWT authentication
 */

// Configuration
$config = [
    'api_endpoint' => 'http://localhost/user/get', // Replace with your API endpoint
    'jwt_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...', // Replace with your actual JWT token
    'timeout' => 30, // Request timeout in seconds
    'verify_ssl' => true // Set to false to disable SSL verification (not recommended for production)
];

// JSON payload
$payload = [
    'id' => 1,
    'action' => 'update',
    'data' => [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'status' => 'active'
    ]
];

/**
 * Send POST request with JSON body and JWT token
 * 
 * @param array $config API configuration
 * @param array $payload JSON payload
 * @return array Response data and status
 */
function sendApiRequest($config, $payload) {
    // Initialize cURL session
    $ch = curl_init($config['api_endpoint']);
    
    // Encode payload as JSON
    $jsonPayload = json_encode($payload);
    if ($jsonPayload === false) {
        return [
            'success' => false,
            'status_code' => 0,
            'error' => 'Failed to encode JSON payload: ' . json_last_error_msg()
        ];
    }
    
    // Set cURL options
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,     // Return response as string
        CURLOPT_POST => true,               // Set request method to POST
        CURLOPT_POSTFIELDS => $jsonPayload, // Set JSON payload
        CURLOPT_TIMEOUT => $config['timeout'],
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $config['jwt_token']
        ]
    ]);
    
    // Disable SSL verification if configured (not recommended for production)
    if ($config['verify_ssl'] === false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    
    // Execute the request
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return [
            'success' => false,
            'status_code' => $statusCode,
            'error' => 'cURL Error: ' . $error
        ];
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Try to parse JSON response
    $responseData = json_decode($response, true);
    if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false,
            'status_code' => $statusCode,
            'error' => 'Invalid JSON response: ' . json_last_error_msg(),
            'raw_response' => $response
        ];
    }
    
    return [
        'success' => $statusCode >= 200 && $statusCode < 300,
        'status_code' => $statusCode,
        'data' => $responseData,
        'raw_response' => $response
    ];
}

// Send the request
$result = sendApiRequest($config, $payload);

// Output the result
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);