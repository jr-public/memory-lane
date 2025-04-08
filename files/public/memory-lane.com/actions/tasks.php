<?php
$tree_res = api_call("Task", "tree", [
    "options" => [
        'order' => ['id ASC'],
        'filters' => $task_filters ?? [],
        "with" => ["assignments"],
        "unique" => true
    ]
]);
if (!$tree_res['success']) {
    echo json_encode($tree_res);
    die();
}
$users_res = api_call("User", "list", [
    "options" => [
        "unique" => true
    ]
]);
if (!$users_res['success']) {
    echo json_encode($users_res);
    die();
}

//
$tasks = $tree_res['data'];
$tasked_users = $users_res['data'];
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
            <a class="btn-action" id="add-task-btn" href="main.php?action=entity_create&type=task">+ Add New Task</a>
            
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

<?php 
require_once('actions/tasks-css.php'); 
?>

<!-- Task JavaScript -->
<script>
    const tasked_users = <?= json_encode($tasked_users) ?>;
    const rawTaskData = <?= json_encode($tasks['tree']) ?>;
    const task_list = <?= json_encode($tasks['list']) ?>;
    const currentUrl = <?= json_encode($current_url) ?>;
    
    // Initialize popover functionality
    document.addEventListener('DOMContentLoaded', function() {
        // If you need to add any global initialization for popovers,
        // you can do it here.
        
        // Example: Delegate clicks to dynamically created task dates
        document.addEventListener('click', function(event) {
            // Check if clicked element or its parent has the task-due-date class
            const dateElement = event.target.closest('.task-due-date');
            if (dateElement && !event.target.closest('.popover')) {
                // Get task id from the date element
                const taskId = dateElement.dataset.taskId;
                
                // Find the task data with this ID
                const taskData = task_list[taskId];
                
                if (taskData) {
                    // Show the date popover for this task
                    showDatePopover(dateElement, taskData.due_date);
                }
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Add event delegation for assignment containers
        document.addEventListener('click', function(event) {
            // Find the closest assignment container if clicked on or inside one
            const container = event.target.closest('[data-action="show-assignments"]');
            if (container) {
                const taskId = container.dataset.taskId;
                const taskTitle = container.dataset.taskTitle;
                
                // Show assignments in the popover instead of modal
                showAssignmentPopover(container, taskTitle, taskId);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const taskData = buildTaskTreeData(rawTaskData);
        renderTaskTree(taskData);
        
        // Task filtering functionality
        initTaskFilters();
        
        // Add expand/collapse all buttons to the task actions
        const taskActions = document.querySelector('.task-actions');
        if (taskActions) {
            const expandCollapseContainer = document.createElement('div');
            expandCollapseContainer.className = 'task-expand-controls';
            
            const expandAllBtn = document.createElement('button');
            expandAllBtn.className = 'btn-expand-all';
            expandAllBtn.textContent = 'Expand All';
            expandAllBtn.addEventListener('click', expandAllTasks);
            
            const collapseAllBtn = document.createElement('button');
            collapseAllBtn.className = 'btn-collapse-all';
            collapseAllBtn.textContent = 'Collapse All';
            collapseAllBtn.addEventListener('click', collapseAllTasks);
            
            expandCollapseContainer.appendChild(expandAllBtn);
            expandCollapseContainer.appendChild(collapseAllBtn);
            
            taskActions.appendChild(expandCollapseContainer);
        }
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
        const taskItems = document.querySelectorAll('.task-item:not(.new-task-form)');
        
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