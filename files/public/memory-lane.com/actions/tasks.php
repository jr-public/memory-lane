<?php
$res = api_call("Task", "tree", [
    "options" => [
        "with" => ["assignments"]
    ]
]);
if (!$res['success']) {
    echo json_encode($res);
    die();
}
$tasks = $res['data'];
// Fetch users from API for the select dropdown
$users_res = api_call("User", "list");
if (!$users_res['success']) {
    $users = [];
} else {
    $users = $users_res['data'];
}
?>

<!-- Task Management Container -->
<div class="task-container">
    <!-- Header -->
    <div class="task-header">
        <h1>Task Management</h1>
    </div>
    
    <!-- Task List Container -->
    <div class="task-list-container">
        <!-- Actions & Filters -->
        <div class="task-actions">
            <button class="btn-action" id="add-task-btn">+ Add New Task</button>
            
            <div class="task-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
                <button class="filter-btn" data-filter="in_progress">In Progress</button>
                <button class="filter-btn" data-filter="pending">Pending</button>
            </div>
        </div>
        
        <!-- Table Header -->
        <div class="task-list-header">
            <!-- <div style="flex: 2;">Task</div>
            <div style="flex: 1; text-align: right;">Details</div> -->
        </div>
        
        <!-- Task List - Will be populated by JavaScript -->
        <div class="task-list" id="task-list"></div>
    </div>
</div>

<!-- Include assignment modal code (if available) -->
<?php 
require_once('actions/assignment_modal.php');
require_once('tasks-css.php'); 
?>

<!-- Task JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize task list
        const taskData = <?= json_encode($tasks) ?>;
        renderTaskTree(taskData);
        
        // Task filtering functionality
        initTaskFilters();
        
        // Add task button functionality
        document.getElementById('add-task-btn').addEventListener('click', function() {
            // Redirect to task creation page
            window.location.href = 'main.php?action=entity_create&type=task';
        });
    });
        

    // Initialize task filters
    function initTaskFilters() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Get filter value
                const filter = this.getAttribute('data-filter');
                
                // Apply filter
                filterTasks(filter);
            });
        });
    }
    // Filter tasks by status
    function filterTasks(filter) {
        const taskItems = document.querySelectorAll('.task-item');
        
        taskItems.forEach(item => {
            const status = item.getAttribute('data-status');
            
            if (filter === 'all' || status === filter) {
                item.style.display = 'flex';
                
                // Also show parent containers if they were previously visible
                const taskId = item.getAttribute('data-task-id');
                const childrenContainer = document.getElementById(`children-${taskId}`);
                
                if (childrenContainer && childrenContainer.dataset.visible === 'true') {
                    childrenContainer.style.display = 'block';
                }
            } else {
                item.style.display = 'none';
                
                // Hide children containers
                const taskId = item.getAttribute('data-task-id');
                const childrenContainer = document.getElementById(`children-${taskId}`);
                
                if (childrenContainer) {
                    childrenContainer.style.display = 'none';
                }
            }
        });
    }
</script>