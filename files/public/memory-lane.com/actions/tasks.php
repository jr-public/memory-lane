<?php
$tree_res = api_call("Task", "tree", [
    "options" => [
        'root_node' => $_GET['id'],
        'order'     => ['id ASC'],
        'filters'   => $task_filters ?? [],
        "with"      => ["assignments", "comments"],
        "unique"    => true
    ]
]);
if (!$tree_res['success']) {
    echo json_encode($tree_res);
    die();
}




$users_res = api_call("Task", "get_project_asignees", [
    'task_id' => $_GET['id']
]);
if (!$users_res['success']) {
    echo json_encode($users_res);
    die();
}
$statuses_res = api_call("TaskStatus", "list", [
    "options" => [
        "unique" => true
    ]
]);
if (!$statuses_res['success']) {
    echo json_encode($statuses_res);
    die();
}
$priorities_res = api_call("TaskPriority", "list", [
    "options" => [
        "unique" => true
    ]
]);
if (!$priorities_res['success']) {
    echo json_encode($priorities_res);
    die();
}
$difficulty_res = api_call("TaskPriority", "list", [
    "options" => [
        "unique" => true
    ]
]);
if (!$difficulty_res['success']) {
    echo json_encode($difficulty_res);
    die();
}
//
$tasks = $tree_res['data'];
$tasked_users = $users_res['data'];
$status_list = $statuses_res['data'];
$priority_list = $priorities_res['data'];
$difficulty_list = $difficulty_res['data'];
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
        </div>
        
        <!-- Task List - Will be populated by JavaScript -->
        <div class="task-list" id="task-list"></div>
    </div>

<?php 
require_once('includes/task-panel.php');
?>

<!-- Task JavaScript -->
<script>
    const tasked_users = <?= json_encode($tasked_users) ?>;
    const task_tree = <?= json_encode($tasks['tree']) ?>;
    const task_list = <?= json_encode($tasks['list']) ?>;
    const currentUrl = <?= json_encode($current_url) ?>;
    const status_list = <?= json_encode($status_list) ?>;
    const priority_list = <?= json_encode($priority_list) ?>;
    const difficulty_list = <?= json_encode($difficulty_list) ?>;
    
    document.addEventListener('DOMContentLoaded', function() {
        renderTaskTree(task_tree);
        
        // Add expand/collapse all buttons to the task actions
        const taskActions = document.querySelector('.task-actions');
        if (taskActions) {
            const expandCollapseContainer = document.createElement('div');
            expandCollapseContainer.className = 'task-expand-controls';
            
            const expandAllBtn = document.createElement('button');
            expandAllBtn.className = 'btn-expand-all';
            expandAllBtn.textContent = 'Expand All';
            expandAllBtn.addEventListener('click', function() {
                const allContainers = document.querySelectorAll('.task-children');
                const allToggleIcons = document.querySelectorAll('.toggle-icon');
                
                allContainers.forEach(container => {
                    container.style.display = 'block';
                    container.dataset.visible = 'true';
                });
                
                allToggleIcons.forEach(icon => {
                    icon.textContent = '▼';
                });
            });
            
            const collapseAllBtn = document.createElement('button');
            collapseAllBtn.className = 'btn-collapse-all';
            collapseAllBtn.textContent = 'Collapse All';
            collapseAllBtn.addEventListener('click', function() {
                const allContainers = document.querySelectorAll('.task-children');
                const allToggleIcons = document.querySelectorAll('.toggle-icon');
                
                allContainers.forEach(container => {
                    container.style.display = 'none';
                    container.dataset.visible = 'false';
                });
                
                allToggleIcons.forEach(icon => {
                    icon.textContent = '▶';
                });
            });
            
            expandCollapseContainer.appendChild(expandAllBtn);
            expandCollapseContainer.appendChild(collapseAllBtn);
            
            taskActions.appendChild(expandCollapseContainer);
        }
    });
</script>