<?php
// Get the current action from URL parameter
$current_action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <span>Navigation</span>
        <button id="close-sidebar" class="sidebar-toggle">×</button>
    </div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="main.php?action=dashboard" class="nav-link <?php echo ($current_action == 'dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=projects" class="nav-link <?php echo ($current_action == 'projects') ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-link-text">Projects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=database" class="nav-link <?php echo ($current_action == 'database') ? 'active' : ''; ?>">
                <span class="nav-icon">🗄️</span>
                <span class="nav-link-text">Database</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=api" class="nav-link <?php echo ($current_action == 'api') ? 'active' : ''; ?>">
                <span class="nav-icon">🔌</span>
                <span class="nav-link-text">API Configuration</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=logs" class="nav-link <?php echo ($current_action == 'logs') ? 'active' : ''; ?>">
                <span class="nav-icon">📝</span>
                <span class="nav-link-text">Logs</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=settings" class="nav-link <?php echo ($current_action == 'settings') ? 'active' : ''; ?>">
                <span class="nav-icon">⚙️</span>
                <span class="nav-link-text">Settings</span>
            </a>
        </li>
    </ul>
</aside>