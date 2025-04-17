<?php
require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["form_name"]) ) {

    if ( $_POST["form_name"] == "register" ) {
        $payload = [
            'username'  => $_POST["username"],
            'email'     => $_POST["email"],
            'password'  => $_POST["password"],
            'role_id'   => 2
        ];
        if (SIMULATE_EXTERNAL_SERVER) 
            external_api_call('User', 'create', $payload);
        else 
            api_call('User', 'create', $payload);
        // A REAL REGISTRATION PROCESS WOULD DO SOMETHING ABOUT NOW
    }

    $payload = [
        'client_id' => 1,
        'username'  => $_POST["username"],
        'password'  => $_POST["password"],
        'device'    => device_id()
    ];

    if (SIMULATE_EXTERNAL_SERVER) {
        $auth = external_api_call('User', 'authenticate', $payload);
    }
    else {
        $auth = api_call('User', 'authenticate', $payload);
    }

    if ( !$auth["success"] ) {
        header("Location: index.php?e=".$auth["message"]);
        die();
    }
    set_auth_user(json_encode($auth['data']['user']));
    set_auth_token($auth["data"]["jwt"]);
    header("Location: main.php");
    die();
}
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

        .main-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .action-panel {
            width: 180px;
            background: #f5f7fa;
            padding: 30px 15px;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
        }

        .middle-panel {
            width: 240px;
            background: #f9f9f9;
            padding: 30px 15px;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 600px;
        }

        .middle-panel-header {
            color: #333;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
        }

        .middle-panel-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .user-button {
            padding: 10px;
            background: #e0e7ff;
            color: #4f46e5;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            font-size: 13px;
        }

        .user-button:hover {
            background: #c7d2fe;
            transform: translateY(-1px);
        }

        .action-panel h3, .middle-panel h3 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        .action-button {
            margin-bottom: 12px;
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
        }

        .action-button:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .container {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            background: white;
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

        .highlight {
            border: 2px solid #ff6b6b !important;
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1) !important;
        }

        .panel-section {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }

        .panel-section-title {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }

        /* Hide middle panel on mobile */
        @media (max-width: 768px) {
            .middle-panel {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Left Action Panel -->
        <div class="action-panel">
            <h3>Actions</h3>
            <button class="action-button" id="btn-show-users">Show Users</button>
            <button class="action-button" id="btn-show-templates">Templates</button>
            <button class="action-button" id="btn-fill-login">Fill Login</button>
            <button class="action-button" id="btn-clear-login">Clear Login</button>
            <button class="action-button" id="btn-fill-register">Fill Register</button>
            <button class="action-button" id="btn-clear-register">Clear Register</button>
            <button class="action-button" id="btn-toggle-forms">Switch Form</button>
        </div>

        <!-- Middle Panel -->
        <div class="middle-panel" id="middle-panel">
            <div class="middle-panel-header">Welcome</div>
            <div class="middle-panel-content" id="middle-panel-content">
                <div class="panel-section">
                    <div class="panel-section-title">Getting Started</div>
                    <p style="font-size: 13px; color: #666;">Use the action buttons on the left to interact with the login forms.</p>
                </div>
            </div>
        </div>

        <!-- Right Container with Login/Register Forms -->
        <div class="container">
            <div class="form-container">
                <!-- Login Form -->
                <form id="login-form" class="active" method="post">
                    <input type="hidden" name="form_name" value="login">
                    <h2>Login</h2>
                    <div class="form-group">
                        <input type="text" name="username" id="login-username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="login-password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
                    <div class="toggle-form">
                        Don't have an account? <a href="#" id="show-register">Register</a>
                    </div>
                </form>

                <!-- Registration Form -->
                <form id="register-form" method="post">
                    <input type="hidden" name="form_name" value="register">
                    <h2>Create Account</h2>
                    <div class="form-group">
                        <input type="text" name="username" id="register-username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="register-email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="register-password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="rPass" id="register-confirm" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn">Register</button>
                    <div class="toggle-form">
                        Already have an account? <a href="#" id="show-login">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sample user array
        <?php
        $res = $User->list();
        if ( !$res['success'] ) $users = [];
        else $users = $res['data'];
        ?>
        const users = <?= json_encode($users) ?>;

        // Sample form templates
        const templates = [
            { name: 'Admin Login', username: 'admin', password: '1234' },
            { name: 'Guest Access', username: 'guest', password: 'guest' },
            { name: 'Developer Account', username: 'developer', password: 'dev2023' },
            { name: 'New User', username: 'new_user', email: 'new@example.com', password: 'welcome123' }
        ];

        // Form toggle functionality
        document.getElementById('show-register').addEventListener('click', function(e) {
            e.preventDefault();
            toggleForms();
        });

        document.getElementById('show-login').addEventListener('click', function(e) {
            e.preventDefault();
            toggleForms();
        });

        // Function to toggle between forms
        function toggleForms() {
            document.getElementById('login-form').classList.toggle('active');
            document.getElementById('register-form').classList.toggle('active');
        }

        // Action panel functionality
        document.getElementById('btn-toggle-forms').addEventListener('click', toggleForms);

        // Fill login form with default values
        document.getElementById('btn-fill-login').addEventListener('click', function() {
            document.getElementById('login-username').value = 'admin';
            document.getElementById('login-password').value = '1234';
        });

        // Clear login form
        document.getElementById('btn-clear-login').addEventListener('click', function() {
            document.getElementById('login-username').value = '';
            document.getElementById('login-password').value = '';
        });

        // Fill register form with default values
        document.getElementById('btn-fill-register').addEventListener('click', function() {
            document.getElementById('register-username').value = 'my_username';
            document.getElementById('register-email').value = 'email@email.com';
            document.getElementById('register-password').value = '12341234';
            document.getElementById('register-confirm').value = '12341234';
        });

        // Clear register form
        document.getElementById('btn-clear-register').addEventListener('click', function() {
            document.getElementById('register-username').value = '';
            document.getElementById('register-email').value = '';
            document.getElementById('register-password').value = '';
            document.getElementById('register-confirm').value = '';
        });

        // Show users in middle panel
        document.getElementById('btn-show-users').addEventListener('click', function() {
            const middlePanelContent = document.getElementById('middle-panel-content');
            const middlePanelHeader = document.querySelector('.middle-panel-header');
            
            middlePanelHeader.textContent = 'Available Users';
            
            // Clear previous content
            middlePanelContent.innerHTML = '';
            
            // Add users as buttons
            users.forEach(user => {
                const userButton = document.createElement('button');
                userButton.className = 'user-button';
                userButton.textContent = `${user.username}`;
                userButton.addEventListener('click', function() {
                    // Fill login form with user data
                    document.getElementById('login-username').value = user.username;
                    document.getElementById('login-password').value = user.password;
                    
                    // Ensure login form is active
                    document.getElementById('login-form').classList.add('active');
                    document.getElementById('register-form').classList.remove('active');
                });
                
                middlePanelContent.appendChild(userButton);
            });
        });

        // Show templates in middle panel
        document.getElementById('btn-show-templates').addEventListener('click', function() {
            const middlePanelContent = document.getElementById('middle-panel-content');
            const middlePanelHeader = document.querySelector('.middle-panel-header');
            
            middlePanelHeader.textContent = 'Form Templates';
            
            // Clear previous content
            middlePanelContent.innerHTML = '';
            
            // Add templates as sections
            templates.forEach(template => {
                const templateSection = document.createElement('div');
                templateSection.className = 'panel-section';
                
                const templateTitle = document.createElement('div');
                templateTitle.className = 'panel-section-title';
                templateTitle.textContent = template.name;
                
                const useButton = document.createElement('button');
                useButton.className = 'user-button';
                useButton.textContent = 'Use Template';
                useButton.style.marginTop = '8px';
                
                useButton.addEventListener('click', function() {
                    if ('email' in template) {
                        // This is a register template
                        document.getElementById('register-username').value = template.username;
                        document.getElementById('register-email').value = template.email;
                        document.getElementById('register-password').value = template.password;
                        document.getElementById('register-confirm').value = template.password;
                        
                        // Show register form
                        document.getElementById('login-form').classList.remove('active');
                        document.getElementById('register-form').classList.add('active');
                    } else {
                        // This is a login template
                        document.getElementById('login-username').value = template.username;
                        document.getElementById('login-password').value = template.password;
                        
                        // Show login form
                        document.getElementById('login-form').classList.add('active');
                        document.getElementById('register-form').classList.remove('active');
                    }
                });
                
                templateSection.appendChild(templateTitle);
                
                // Add template details
                const details = document.createElement('div');
                details.style.fontSize = '12px';
                details.style.color = '#666';
                details.style.marginBottom = '8px';
                
                if ('email' in template) {
                    details.textContent = `Username: ${template.username}, Email: ${template.email}`;
                } else {
                    details.textContent = `Username: ${template.username}`;
                }
                
                templateSection.appendChild(details);
                templateSection.appendChild(useButton);
                middlePanelContent.appendChild(templateSection);
            });
        });
    </script>
</body>
</html>