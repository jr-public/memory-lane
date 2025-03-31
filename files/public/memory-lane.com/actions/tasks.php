<?php
// Task Management action file
// This would be placed in the actions/tasks.php directory and included via main.php

// In a real implementation, these tasks would be fetched from the database
// using the Task model and its relationships
$tasks = [
    [
        'id' => 1,
        'title' => 'Website Redesign',
        'description' => 'Complete redesign of the corporate website with new branding',
        'status' => 'in_progress',
        'due_date' => '2025-04-30',
        'priority' => 'high',
        'assigned_to' => 'Jane Smith',
        'parent_id' => null,
        'depth' => 0,
        'children' => [
            [
                'id' => 101,
                'title' => 'Design mockups approval',
                'description' => 'Get approval for the new design mockups',
                'status' => 'completed',
                'due_date' => '2025-02-01',
                'priority' => 'medium',
                'assigned_to' => 'Design Team',
                'parent_id' => 1,
                'depth' => 1,
                'children' => []
            ],
            [
                'id' => 102,
                'title' => 'Frontend implementation',
                'description' => 'Implement the frontend based on approved designs',
                'status' => 'in_progress',
                'due_date' => '2025-03-15',
                'priority' => 'high',
                'assigned_to' => 'Development Team',
                'parent_id' => 1,
                'depth' => 1,
                'children' => [
                    [
                        'id' => 1021,
                        'title' => 'Homepage development',
                        'description' => 'Develop the new homepage',
                        'status' => 'in_progress',
                        'due_date' => '2025-02-28',
                        'priority' => 'high',
                        'assigned_to' => 'John Doe',
                        'parent_id' => 102,
                        'depth' => 2,
                        'children' => []
                    ],
                    [
                        'id' => 1022,
                        'title' => 'About page development',
                        'description' => 'Develop the about page',
                        'status' => 'pending',
                        'due_date' => '2025-03-05',
                        'priority' => 'medium',
                        'assigned_to' => 'Sarah Johnson',
                        'parent_id' => 102,
                        'depth' => 2,
                        'children' => []
                    ]
                ]
            ],
            [
                'id' => 103,
                'title' => 'Content migration',
                'description' => 'Migrate content from old site to new site',
                'status' => 'pending',
                'due_date' => '2025-04-15',
                'priority' => 'medium',
                'assigned_to' => 'Content Team',
                'parent_id' => 1,
                'depth' => 1,
                'children' => []
            ]
        ]
    ],
    [
        'id' => 2,
        'title' => 'Mobile App Development',
        'description' => 'Development of a cross-platform mobile application',
        'status' => 'in_progress',
        'due_date' => '2025-06-30',
        'priority' => 'high',
        'assigned_to' => 'John Doe',
        'parent_id' => null,
        'depth' => 0,
        'children' => [
            [
                'id' => 201,
                'title' => 'Requirements gathering',
                'description' => 'Gather and document all requirements',
                'status' => 'completed',
                'due_date' => '2025-02-15',
                'priority' => 'high',
                'assigned_to' => 'Project Manager',
                'parent_id' => 2,
                'depth' => 1,
                'children' => []
            ],
            [
                'id' => 202,
                'title' => 'UI/UX design',
                'description' => 'Design user interface and experience',
                'status' => 'in_progress',
                'due_date' => '2025-03-30',
                'priority' => 'high',
                'assigned_to' => 'Design Team',
                'parent_id' => 2,
                'depth' => 1,
                'children' => []
            ]
        ]
    ],
    [
        'id' => 3,
        'title' => 'Marketing Campaign',
        'description' => 'Plan and execute Q2 marketing campaign',
        'status' => 'pending',
        'due_date' => '2025-05-15',
        'priority' => 'medium',
        'assigned_to' => 'Marketing Team',
        'parent_id' => null,
        'depth' => 0,
        'children' => []
    ]
];

// Get task counts by status
$task_counts = [
    'completed' => 0,
    'in_progress' => 0,
    'pending' => 0,
    'backlogged' => 0
];

// Function to count tasks by status recursively
function countTasksByStatus(&$task_counts, $tasks) {
    foreach ($tasks as $task) {
        if (isset($task_counts[$task['status']])) {
            $task_counts[$task['status']]++;
        }
        if (!empty($task['children'])) {
            countTasksByStatus($task_counts, $task['children']);
        }
    }
}

countTasksByStatus($task_counts, $tasks);

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
        echo '<span>👤</span>';
        echo htmlspecialchars($task['assigned_to']);
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
    
    <div class="task-overview">
        <div class="task-card">
            <div class="stat-title">Total Tasks</div>
            <div class="stat-value"><?php echo array_sum($task_counts); ?></div>
            <div class="task-stats">
                <div><span class="checkmark">✓</span> <?php echo $task_counts['completed']; ?> Completed</div>
                <div><span style="color: #3498db;">●</span> <?php echo $task_counts['in_progress']; ?> In Progress</div>
                <div><span style="color: #f39c12;">●</span> <?php echo $task_counts['pending']; ?> Pending</div>
                <div><span style="color: #7f8c8d;">●</span> <?php echo $task_counts['backlogged']; ?> Backlogged</div>
            </div>
        </div>
        
        <div class="task-card">
            <div class="stat-title">My Tasks</div>
            <div class="stat-value">8</div>
            <div class="task-stats">
                <div><span style="color: #e74c3c;">●</span> 3 High Priority</div>
                <div><span style="color: #f39c12;">●</span> 4 Medium Priority</div>
                <div><span style="color: #3498db;">●</span> 1 Low Priority</div>
            </div>
        </div>
        
        <div class="task-card">
            <div class="stat-title">Upcoming Deadlines</div>
            <div class="stat-value">5</div>
            <div class="task-stats">
                <div><span style="color: #e74c3c;">●</span> 2 This Week</div>
                <div><span style="color: #f39c12;">●</span> 3 Next Week</div>
            </div>
        </div>
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