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
            <a href="main.php?action=tasks" class="nav-link <?php echo ($current_action == 'tasks') ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-link-text">Tasks</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=create_entity" class="nav-link <?php echo ($current_action == 'create_entity') ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-link-text">Create entities</span>
            </a>
        </li>
    </ul>
</aside>