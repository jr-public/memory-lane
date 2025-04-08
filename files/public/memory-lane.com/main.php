<?php

require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');

$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$params = $_GET;
unset($params['error']);
$current_url = strtok($current_url, '?') . (!empty($params) ? '?' . http_build_query($params) : '');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $controller = $_POST['entity_name'];
    $action     = $_POST['entity_action'];
    unset($_POST['entity_name']);
    unset($_POST['entity_action']);
    if ( $action == 'create' ) $data = ["data" => $_POST];
    elseif ( $action == 'update' ) {
        $u_id = $_POST["id"];
        unset($_POST["id"]);
        $data = [
            "id"    => $u_id,
            "data" => $_POST
        ];
    }
    elseif ( $action == 'delete' ) {
        $data = ['id' => $_POST['id']];
    }
    else {
        $data = [];
    }

    $args = '';
    $res = api_call($controller, $action, $data);
    if ( !$res['success'] ) {
        if (empty($res['message'])) $res['message'] = 'UNKNOWN ERROR';
        $args = $_GET;
        $args['error'] = $res['message'];
        $args = http_build_query($args);
        $current_url = strtok($current_url, '?') . '?' . $args;
    }
    
    header('Location: '.$current_url);
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Admin Panel</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/tasks.js" defer></script>
    
    <!-- CONTEXTUAL POPOVER -->
    <link rel="stylesheet" href="assets/popover/popover.css">
    <script src="assets/popover/popover.js" defer></script>
    <script src="assets/temp_popover.js" defer></script>

</head>
<body>
    <header>
        <div class="header-left">
            <button id="toggle-sidebar" class="sidebar-toggle">☰</button>
            <div class="header-title">API Admin Panel</div>
        </div>
        <div class="user-info">
            <span class="user-name">Admin User</span>
            <div class="user-avatar">A</div>
        </div>
    </header>
    <div class="main-container">
        <!-- Include the navbar from external file -->
        <?php include 'includes/navbar.php'; ?>
        
        <main class="content">
            <?php
            // Get the current action from URL parameter
            $action = isset($_GET['action']) ? $_GET['action'] : 'projects';
            
            // Sanitize the action parameter to prevent directory traversal
            $action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);
            
            // Define the path to the action file
            $action_file = 'actions/' . $action . '.php';
            
            // Check if the action file exists
            if (file_exists($action_file)) {
                include $action_file;
            } else {
                // Display default content or 404
                echo '<div class="content-placeholder">';
                echo 'The requested section "' . htmlspecialchars($action) . '" was not found or is still under development.';
                echo '</div>';
            }
            ?>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            
            // Function to toggle sidebar visibility
            function toggleSidebarVisibility() {
                sidebar.classList.toggle('collapsed');
                document.body.classList.toggle('sidebar-collapsed');
            }
            
            // Toggle sidebar when hamburger button is clicked
            toggleSidebar.addEventListener('click', toggleSidebarVisibility);
            
            // Close sidebar when X button is clicked
            closeSidebar.addEventListener('click', toggleSidebarVisibility);
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.matchMedia('(max-width: 768px)').matches;
                if (isMobile && 
                    !sidebar.contains(event.target) && 
                    !toggleSidebar.contains(event.target) &&
                    !sidebar.classList.contains('collapsed')) {
                    toggleSidebarVisibility();
                }
            });
        });
    </script>
</body>
</html>