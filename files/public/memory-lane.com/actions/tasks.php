<?php

if (true) { // TREE
	$res = api_call("Task", "tree", [
		"options" => [
			"with" => ["assignments"]
		]
	]);
}
else { // FLAT LIST
	$res = api_call("Task", "list", [
		"options" => [
			"filters" => ["parent_id IS NULL"], // COMMENT FOR ALL RESULTS
			"with" => ["assignments"]
		]
	]);
}

//
require_once("tasks-rows.php");
$tasks = $res;

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'completed':
            return 'status-active';
        case 'in_progress':
            return 'status-in-progress';
        case 'pending':
            return 'status-pending';
        case 'backlogged':
            return 'status-inactive';
        default:
            return 'status-pending';
    }
}

// Function to get priority badge class
function getPriorityBadgeClass($priority) {
    switch ($priority) {
        case 'high':
            return 'priority-high';
        case 'medium':
            return 'priority-medium';
        case 'low':
            return 'priority-low';
        default:
            return 'priority-medium';
    }
}

// Function to get status icon class
function getStatusIconClass($status) {
    switch ($status) {
        case 'completed':
            return 'status-completed-icon';
        case 'in_progress':
            return 'status-in-progress-icon';
        case 'pending':
            return 'status-pending-icon';
        case 'backlogged':
            return 'status-on-hold-icon';
        default:
            return 'status-pending-icon';
    }
}

// Recursive function to render task tree
function renderTaskTree($tasks, $level = 0) {
    foreach ($tasks as $task) {

		//
		if ( !isset($task['status']) ) $task['status'] = 'not_set';
		if ( !isset($task['priority']) ) $task['priority'] = 'not_set';


        $hasChildren = !empty($task['children']);
        $taskId = $task['id'];
        $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
        
        echo '<div class="task-item" data-task-id="' . $taskId . '" data-status="' . $task['status'] . '" data-level="' . $level . '">';
        echo '<div class="task-info">';
        echo $indentation;
        
        if ($hasChildren) {
            echo '<span class="toggle-icon" data-task-id="' . $taskId . '">▼</span>';
        } else {
            echo '<span class="toggle-icon-placeholder"></span>';
        }
        
        echo '<div class="task-status-icon ' . getStatusIconClass($task['status']) . '"></div>';
        echo '<div class="task-name">' . htmlspecialchars($task['title']) . '</div>';
        echo '</div>';
        
        echo '<div class="task-meta">';
        echo '<div class="task-priority">';
        echo '<span class="priority-indicator ' . getPriorityBadgeClass($task['priority']) . '">' . ucfirst($task['priority']) . '</span>';
        echo '</div>';
        echo '<div class="task-assignee">';
        // echo '<span>👤</span>';
        // echo htmlspecialchars($task['assigned_to']);
        echo '</div>';
        echo '<div class="task-due-date">';
        echo '<span>📅</span>';
        echo date('M d', strtotime($task['due_date']));
        echo '</div>';
        echo '<div class="task-status-badge ' . getStatusBadgeClass($task['status']) . '">';
        echo ucfirst($task['status']);
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Render children if they exist
        if ($hasChildren) {
            echo '<div class="task-children" id="children-' . $taskId . '">';
            renderTaskTree($task['children'], $level + 1);
            echo '</div>';
        }
    }
}
?>

<style>
    /* Task Management Specific Styles */
    .task-container {
        padding: 20px;
    }
    
    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .task-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .task-card {
        background-color: #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .stat-title {
        color: #909090;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #e0e0e0;
        margin-bottom: 0.5rem;
    }
    
    .task-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .btn-action {
        background-color: #3498db;
        color: #121212;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .task-filters {
        display: flex;
        gap: 0.5rem;
    }
    
    .filter-btn {
        background-color: #333;
        border: none;
        color: #e0e0e0;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
    }
    
    .filter-btn.active {
        background-color: #3498db;
        color: #121212;
    }
    
    .task-list-container {
        background-color: #2a2a2a;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .task-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #444;
    }
    
    .task-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #444;
        transition: all 0.2s ease;
    }
    
    .task-item:hover {
        background-color: #333;
    }
    
    .task-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 2;
    }
    
    .task-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #909090;
        font-size: 0.85rem;
        flex: 1;
        justify-content: flex-end;
    }
    
    .task-status-icon {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .status-completed-icon {
        background-color: #2ecc71;
    }
    
    .status-in-progress-icon {
        background-color: #3498db;
    }
    
    .status-pending-icon {
        background-color: #f39c12;
    }
    
    .status-on-hold-icon {
        background-color: #7f8c8d;
    }
    
    .task-name {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
    }
    
    .task-assignee, .task-due-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        white-space: nowrap;
    }
    
    .task-status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    
    .status-active {
        background-color: #2ecc71;
        color: #121212;
    }
    
    .status-in-progress {
        background-color: #3498db;
        color: #121212;
    }
    
    .status-pending {
        background-color: #f39c12;
        color: #121212;
    }
    
    .status-inactive {
        background-color: #7f8c8d;
        color: #121212;
    }
    
    .priority-indicator {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    
    .priority-high {
        background-color: #e74c3c;
        color: white;
    }
    
    .priority-medium {
        background-color: #f39c12;
        color: #121212;
    }
    
    .priority-low {
        background-color: #3498db;
        color: #121212;
    }
    
    .toggle-icon, .toggle-icon-placeholder {
        cursor: pointer;
        width: 16px;
        display: inline-block;
        text-align: center;
        font-size: 0.8rem;
    }
    
    .toggle-icon-placeholder {
        visibility: hidden;
    }
    
    .task-children {
        padding-left: 0.5rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .task-overview {
            grid-template-columns: 1fr;
        }
        
        .task-meta {
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }
        
        .task-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .task-info {
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="task-container">
    <div class="task-header">
        <h1>Task Management</h1>
    </div>
    
    <div class="task-list-container">
        <div class="task-actions">
            <button class="btn-action">+ Add New Task</button>
            
            <div class="task-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
                <button class="filter-btn" data-filter="in_progress">In Progress</button>
                <button class="filter-btn" data-filter="pending">Pending</button>
            </div>
        </div>
        
        <div class="task-list-header">
            <div style="flex: 2;">Task</div>
            <div style="flex: 1; text-align: right;">Details</div>
        </div>
        
        <div class="task-list">
            <?php renderTaskTree($tasks); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Task filtering functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Get filter value
                const filter = this.getAttribute('data-filter');
                
                // Filter tasks
                const taskItems = document.querySelectorAll('.task-item');
                taskItems.forEach(item => {
                    const status = item.getAttribute('data-status');
                    if (filter === 'all' || status === filter) {
                        item.style.display = 'flex';
                        
                        // If parent is visible, also show its children if they were previously visible
                        const parentId = item.getAttribute('data-task-id');
                        const childrenContainer = document.getElementById('children-' + parentId);
                        if (childrenContainer && childrenContainer.classList.contains('visible')) {
                            childrenContainer.style.display = 'block';
                        }
                    } else {
                        item.style.display = 'none';
                        
                        // Hide children as well
                        const parentId = item.getAttribute('data-task-id');
                        const childrenContainer = document.getElementById('children-' + parentId);
                        if (childrenContainer) {
                            childrenContainer.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Task collapse/expand functionality
        const toggleIcons = document.querySelectorAll('.toggle-icon');
        toggleIcons.forEach(icon => {
            const taskId = icon.getAttribute('data-task-id');
            const childrenContainer = document.getElementById('children-' + taskId);
            
            // Mark initially as visible
            if (childrenContainer) {
                childrenContainer.classList.add('visible');
            }
            
            icon.addEventListener('click', function() {
                if (childrenContainer) {
                    if (childrenContainer.style.display === 'none') {
                        childrenContainer.style.display = 'block';
                        icon.textContent = '▼';
                        childrenContainer.classList.add('visible');
                    } else {
                        childrenContainer.style.display = 'none';
                        icon.textContent = '▶';
                        childrenContainer.classList.remove('visible');
                    }
                }
            });
        });
    });
</script>