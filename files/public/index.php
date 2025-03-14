<?php
// 1. Initial Authentication
// - Generate JWT with current context
// - Store context markers in token

// 2. Subsequent Requests
// - Validate current context against token
// - If context differs:
//   * Reject request
//   * Prompt client to re-authenticate
//   * Generate new JWT with updated context
function reserved_db() {
	$host 		= getenv("POSTGRES_SERVICE");
	$dbname 	= getenv("POSTGRES_DB");
	$username 	= getenv("POSTGRES_USER");
	$password 	= getenv("POSTGRES_PASSWORD");
	try {
		$db = new PDO(
			"pgsql:host=$host;dbname=$dbname",
			$username,
			$password,
			[
				PDO::ATTR_ERRMODE 				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES 		=> false
			]
		);
	} catch(PDOException $e) {
		die("Connection failed: " . $e->getMessage());
	}
	return $db;
}
define("DB",reserved_db());

// try {
// 	$sql	= "SELECT * FROM users";
// 	$stmt	= DB->prepare($sql);
// 	$stmt->execute();
// 	$users	= $stmt->fetchAll();
	
// 	echo "<br /><br />";
// 	foreach ( $users AS $user ) {
// 		echo json_encode($user);
// 		echo "<br /><br />";
// 	}
// } catch (\Throwable $th) {
//     echo "<b>An error occurred:</b> " . $th->getMessage();
// }


// echo "<br/>";
// echo "and now for something completely different :";
// echo "<br/>";

// require 'vendor/autoload.php';

// use MemoryLane\Auth;
// $auth = new Auth();
// $jwt = $auth->authenticate("jr-client","12341234");
// $response = $auth->verifyToken($jwt);
// echo json_encode($response);

// if ( $_SERVER["REQUEST_METHOD"] == "POST") {

// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .form-container {
            text-align: center;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .toggle-form {
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .toggle-form a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        #login-form, #register-form {
            display: none;
        }

        #login-form.active, #register-form.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Login Form -->
            <form id="login-form" class="active" method="post" action="Api.php">
                <h2>Login</h2>
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" value="my_username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" value="12341234" required>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="toggle-form">
                    Don't have an account? <a href="#" id="show-register">Register</a>
                </div>
            </form>

            <!-- Registration Form -->
            <form id="register-form" method="post" action="Api.php">
                <h2>Create Account</h2>
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" value="my_username" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="email@email.com" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" value="12341234" required>
                </div>
                <div class="form-group">
                    <input type="password" name="rPass" placeholder="Confirm Password" value="12341234" required>
                </div>
                <button type="submit" class="btn">Register</button>
                <div class="toggle-form">
                    Already have an account? <a href="#" id="show-login">Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Form toggle functionality
        document.getElementById('show-register').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form').classList.remove('active');
            document.getElementById('register-form').classList.add('active');
        });

        document.getElementById('show-login').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('register-form').classList.remove('active');
            document.getElementById('login-form').classList.add('active');
        });
    </script>
</body>
</html>