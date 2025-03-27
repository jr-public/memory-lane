<?php

require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');
$User = new MemoryLane\User(DB);
$Client = new MemoryLane\Client(DB);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Admin Panel</title>
    <link rel="stylesheet" href="assets/style.css">
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
            $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
            
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