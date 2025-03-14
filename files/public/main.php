<?php
// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get all headers
$headers = getallheaders();

// Get request body
$body = file_get_contents('php://input');

// Get query parameters
$query = $_GET;

// Format JSON body for display if applicable
$formattedBody = $body;
if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'application/json') !== false) {
    $jsonBody = json_decode($body, true);
    if ($jsonBody !== null) {
        $formattedBody = json_encode($jsonBody, JSON_PRETTY_PRINT);
    }
}

// Get current timestamp
$timestamp = date('Y-m-d H:i:s');



require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');
use MemoryLane\Auth;
$auth = new Auth();

// echo $auth->get_cookie();
// die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTTP Request Logger</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .request-info {
            display: flex;
            justify-content: space-between;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .method {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }
        .GET { background-color: #28a745; }
        .POST { background-color: #007bff; }
        .PUT { background-color: #fd7e14; }
        .DELETE { background-color: #dc3545; }
        .PATCH { background-color: #6f42c1; }
        .HEAD, .OPTIONS { background-color: #6c757d; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .timestamp {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <pre><a href='index.php'>GO BACK</a></pre>
    <div class="container">
        <h1>HTTP Request Logger</h1>
        <p class="timestamp">Request received at: <?php echo $timestamp; ?></p>
        
        <div class="request-info">
            <div>
                <span class="method <?php echo $method; ?>"><?php echo $method; ?></span>
                <span><?php echo $_SERVER['REQUEST_URI']; ?></span>
            </div>
            <div>
                <span><?php echo $_SERVER['SERVER_PROTOCOL']; ?></span>
            </div>
        </div>

        <h2>Headers</h2>
        <?php if (empty($headers)): ?>
            <p>No headers found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Header</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($headers as $name => $value): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (!empty($query)): ?>
            <h2>Query Parameters</h2>
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($query as $name => $value): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td>
                                <?php 
                                    if (is_array($value)) {
                                        echo htmlspecialchars(json_encode($value));
                                    } else {
                                        echo htmlspecialchars($value);
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2>Request Body</h2>
        <?php if (empty($body)): ?>
            <p>No body content found.</p>
        <?php else: ?>
            <pre><?php echo htmlspecialchars($formattedBody); ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>