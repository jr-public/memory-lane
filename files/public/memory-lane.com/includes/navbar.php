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
            <a href="main.php?action=projects" class="nav-link <?php echo ($current_action == 'projects') ? 'active' : ''; ?>">
                <span class="nav-icon">📁</span>
                <span class="nav-link-text">Projects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=entity_create" class="nav-link <?php echo ($current_action == 'entity_create') ? 'active' : ''; ?>">
                <span class="nav-icon">➕</span>
                <span class="nav-link-text">Create entities</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="main.php?action=entity_list" class="nav-link <?php echo ($current_action == 'entity_list') ? 'active' : ''; ?>">
                <span class="nav-icon">📋</span>
                <span class="nav-link-text">List entities</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="index.php" class="nav-link">
                <span class="nav-icon">📋</span>
                <span class="nav-link-text">LOGOUT</span>
            </a>
        </li>
    </ul>
</aside>