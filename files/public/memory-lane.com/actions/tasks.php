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

// Build task tree data structure for template
function buildTaskTreeData($tasks) {
    $statusClasses = [
        'completed' => 'status-active',
        'in_progress' => 'status-in-progress',
        'pending' => 'status-pending',
        'backlogged' => 'status-inactive',
        'not_set' => 'status-pending'
    ];
    $priorityClasses = [
        'high' => 'priority-high',
        'medium' => 'priority-medium',
        'low' => 'priority-low',
        'not_set' => 'priority-medium'
    ];
    $iconClasses = [
        'completed' => 'status-completed-icon',
        'in_progress' => 'status-in-progress-icon',
        'pending' => 'status-pending-icon',
        'backlogged' => 'status-on-hold-icon',
        'not_set' => 'status-pending-icon'
    ];
    
    $result = [];
    foreach ($tasks as $task) {
        // Set default values if not present
        if (!isset($task['status'])) $task['status'] = 'not_set';
        if (!isset($task['priority'])) $task['priority'] = 'not_set';
        
        $taskData = [
            'id' => $task['id'],
            'title' => $task['title'],
            'status' => $task['status'],
            'priority' => $task['priority'],
            'due_date' => $task['due_date'],
            'status_class' => $iconClasses[$task['status']] ?? 'status-pending-icon',
            'status_badge_class' => $statusClasses[$task['status']] ?? 'status-pending',
            'priority_class' => $priorityClasses[$task['priority']] ?? 'priority-medium',
            'has_children' => !empty($task['children']),
            'assignments' => $task['assignments'] ?? [],
            'children' => []
        ];
        
        // Process children if they exist
        if (!empty($task['children'])) {
            $taskData['children'] = buildTaskTreeData($task['children']);
        }
        
        $result[] = $taskData;
    }
    
    return $result;
}

// Process task data
$taskTreeData = buildTaskTreeData($tasks);
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
            <div style="flex: 2;">Task</div>
            <div style="flex: 1; text-align: right;">Details</div>
        </div>
        
        <!-- Task List - Will be populated by JavaScript -->
        <div class="task-list" id="task-list"></div>
    </div>
</div>

<!-- Assignment Details Modal -->
<div id="assignment-modal">
    <div class="assignment-modal-content">
        <div class="assignment-modal-header">
            <div class="assignment-modal-title">Task Assignments</div>
            <span class="assignment-modal-close">&times;</span>
        </div>
        <div class="assignment-list" id="assignment-list">
            <!-- Assignment items will be inserted here by JavaScript -->
        </div>
    </div>
</div>

<!-- Task CSS -->
<?php require_once('tasks-css.php'); ?>

<!-- Task JavaScript -->
<script>
    // Function to generate user options HTML
    function generateUserOptions(users) {
        let options = '<option value="">Select a user</option>';
        users.forEach(user => {
            options += `<option value="${user.id}">${escapeHTML(user.username)}</option>`;
        });
        return options;
    }
    
    // Helper function to escape HTML
    function escapeHTML(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Task data from PHP
        const taskData = <?= json_encode($taskTreeData) ?>;
        
        // Initialize task list
        renderTaskTree(taskData);
        
        // Task filtering functionality
        initTaskFilters();
        
        // Add task button functionality
        document.getElementById('add-task-btn').addEventListener('click', function() {
            // Redirect to task creation page
            window.location.href = 'main.php?action=entity_create&type=task';
        });
        
        // Modal functionality for assignments
        const modal = document.getElementById('assignment-modal');
        const closeBtn = document.querySelector('.assignment-modal-close');
        
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Render task list recursively
    function renderTaskTree(tasks, parentElement = null, level = 0) {
        const taskList = parentElement || document.getElementById('task-list');
        
        tasks.forEach(task => {
            // Create task item container
            const taskItem = document.createElement('div');
            taskItem.className = 'task-item';
            taskItem.dataset.taskId = task.id;
            taskItem.dataset.status = task.status;
            taskItem.dataset.level = level;
            
            // Task info (left side)
            const taskInfo = document.createElement('div');
            taskInfo.className = 'task-info';
            
            // Add indentation based on level
            if (level > 0) {
                const indentation = document.createElement('span');
                indentation.innerHTML = '&nbsp;'.repeat(level * 4);
                taskInfo.appendChild(indentation);
            }
            
            // Toggle icon for expandable tasks
            if (task.has_children) {
                const toggleIcon = document.createElement('span');
                toggleIcon.className = 'toggle-icon';
                toggleIcon.dataset.taskId = task.id;
                toggleIcon.textContent = '▼';
                toggleIcon.addEventListener('click', toggleChildren);
                taskInfo.appendChild(toggleIcon);
            } else {
                const placeholder = document.createElement('span');
                placeholder.className = 'toggle-icon-placeholder';
                taskInfo.appendChild(placeholder);
            }
            
            // Status icon
            const statusIcon = document.createElement('div');
            statusIcon.className = `task-status-icon ${task.status_class}`;
            taskInfo.appendChild(statusIcon);
            
            // Task name
            const taskName = document.createElement('div');
            taskName.className = 'task-name';
            taskName.textContent = task.title;
            taskInfo.appendChild(taskName);
            
            taskItem.appendChild(taskInfo);
            
            // Task meta (right side)
            const taskMeta = document.createElement('div');
            taskMeta.className = 'task-meta';
            
            // Task assignments
            const taskAssignments = document.createElement('div');
            taskAssignments.className = 'task-assignments';
            taskAssignments.appendChild(createAvatarsElement(task));
            taskMeta.appendChild(taskAssignments);
            
            // Priority indicator
            const taskPriority = document.createElement('div');
            taskPriority.className = 'task-priority';
            const priorityIndicator = document.createElement('span');
            priorityIndicator.className = `priority-indicator ${task.priority_class}`;
            priorityIndicator.textContent = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);
            taskPriority.appendChild(priorityIndicator);
            taskMeta.appendChild(taskPriority);
            
            // Due date
            const taskDueDate = document.createElement('div');
            taskDueDate.className = 'task-due-date';
            const dateIcon = document.createElement('span');
            dateIcon.textContent = '📅';
            taskDueDate.appendChild(dateIcon);
            
            const dateText = document.createElement('span');
            const dueDate = new Date(task.due_date);
            dateText.textContent = `${dueDate.toLocaleString('default', { month: 'short' })} ${dueDate.getDate()}`;
            taskDueDate.appendChild(dateText);
            taskMeta.appendChild(taskDueDate);
            
            // Status badge
            const statusBadge = document.createElement('div');
            statusBadge.className = `task-status-badge ${task.status_badge_class}`;
            statusBadge.textContent = task.status.charAt(0).toUpperCase() + task.status.slice(1);
            taskMeta.appendChild(statusBadge);
            
            taskItem.appendChild(taskMeta);
            
            // Add to task list
            taskList.appendChild(taskItem);
            
            // Render children if they exist
            if (task.has_children) {
                // Create container for children
                const childrenContainer = document.createElement('div');
                childrenContainer.className = 'task-children';
                childrenContainer.id = `children-${task.id}`;
                childrenContainer.dataset.visible = 'true';
                taskList.appendChild(childrenContainer);
                
                // Recursively render children within this container
                renderTaskTree(task.children, childrenContainer, level + 1);
            }
        });
    }
    
    // Create avatar elements for task assignments
    function createAvatarsElement(task) {
        const container = document.createElement('div');
        const assignments = task.assignments || [];
        const maxAvatarsToShow = 3;
        
        // If no assignments, show a plus icon instead of empty state text
        if (assignments.length === 0) {
            container.className = 'task-avatars-container';
            
            // Create a plus icon element
            const plusIcon = document.createElement('div');
            plusIcon.className = 'task-avatar-add';
            plusIcon.title = 'Add assignment';
            plusIcon.textContent = '+';
            container.appendChild(plusIcon);
            
            // Make it clickable to open the modal - same as with assigned users
            container.setAttribute('onclick', `showAssignmentDetails('[]', '${escapeString(task.title)}', ${task.id})`);
            
            return container;
        }
        
        // Create avatar container
        container.className = 'task-avatars-container';
        
        // For click event - stringify assignments data
        const assignmentsData = JSON.stringify(assignments);
        container.setAttribute('onclick', `showAssignmentDetails('${escapeString(assignmentsData)}', '${escapeString(task.title)}', ${task.id})`);
        
        // Show avatars up to the maximum
        const avatarsToShow = Math.min(assignments.length, maxAvatarsToShow);
        for (let i = 0; i < avatarsToShow; i++) {
            const username = assignments[i].username || 'User';
            const initial = username.charAt(0).toUpperCase();
            
            const avatar = document.createElement('div');
            avatar.className = 'task-avatar';
            avatar.title = username;
            avatar.textContent = initial;
            
            // Different color for each avatar
            avatar.style.backgroundColor = getAvatarColor(i);
            
            container.appendChild(avatar);
        }
        
        // If there are more avatars than we can show, add the +X indicator
        if (assignments.length > maxAvatarsToShow) {
            const moreIndicator = document.createElement('div');
            moreIndicator.className = 'task-avatar-more';
            moreIndicator.textContent = `+${assignments.length - maxAvatarsToShow}`;
            container.appendChild(moreIndicator);
        }
        
        return container;
    }
    
    // Get avatar color based on index
    function getAvatarColor(index) {
        const colors = [
            '#3498db', // Blue
            '#9b59b6', // Purple
            '#e74c3c', // Red
            '#2ecc71', // Green
            '#f39c12', // Orange
            '#1abc9c', // Teal
            '#d35400'  // Dark Orange
        ];
        return colors[index % colors.length];
    }
    
    // Escape string for safe use in HTML attributes
    function escapeString(str) {
        return str
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"');
    }
    
    // Toggle task children visibility
    function toggleChildren(event) {
        const taskId = this.dataset.taskId;
        const childrenContainer = document.getElementById(`children-${taskId}`);
        
        if (childrenContainer) {
            if (childrenContainer.style.display === 'none') {
                childrenContainer.style.display = 'block';
                this.textContent = '▼';
                childrenContainer.dataset.visible = 'true';
            } else {
                childrenContainer.style.display = 'none';
                this.textContent = '▶';
                childrenContainer.dataset.visible = 'false';
            }
        }
    }
    
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
    
    // Modified showAssignmentDetails function with delete buttons
    function showAssignmentDetails(assignmentsJson, taskTitle, taskId) {
        const modal = document.getElementById('assignment-modal');
        const assignmentList = document.getElementById('assignment-list');
        const modalTitle = document.querySelector('.assignment-modal-title');
        
        // Update modal title with task name
        modalTitle.textContent = `Assignments for: ${taskTitle}`;
        
        // Clear previous assignments
        assignmentList.innerHTML = '';
        
        try {
            // Parse the JSON string to get actual assignments
            const assignments = JSON.parse(assignmentsJson);
            
            // Create the assignment content container
            const contentContainer = document.createElement('div');
            contentContainer.className = 'assignment-content';
            
            if (assignments.length === 0) {
                // Show empty state message
                const emptyState = document.createElement('div');
                emptyState.className = 'assignment-empty';
                emptyState.innerHTML = `
                    <div class="empty-icon">👥</div>
                    <div class="empty-text">No users assigned to this task</div>
                `;
                contentContainer.appendChild(emptyState);
            } else {
                // Create an element for each assignment
                assignments.forEach((assignment, index) => {
                    const item = document.createElement('div');
                    item.className = 'assignment-item';
                    
                    const username = assignment.username || `User ${index + 1}`;
                    const initial = username.charAt(0).toUpperCase();
                    const role = assignment.role_id ? getRoleName(assignment.role_id) : 'Contributor';
                    
                    // Use avatar color based on index
                    const color = getAvatarColor(index);
                    
                    // Create the main assignment content
                    const mainContent = document.createElement('div');
                    mainContent.className = 'assignment-main-content';
                    mainContent.innerHTML = `
                        <div class="assignment-avatar" style="background-color: ${color};">${initial}</div>
                        <div class="assignment-details">
                            <div class="assignment-name">${username}</div>
                            <div class="assignment-role">${role}</div>
                        </div>
                    `;
                    
                    // Create the delete button as a form
                    const deleteForm = document.createElement('form');
                    deleteForm.className = 'assignment-delete-form';
                    deleteForm.action = '<?= $current_url ?>';
                    deleteForm.method = 'post';
                    deleteForm.innerHTML = `
                        <input type="hidden" name="entity_name" value="TaskAssignment">
                        <input type="hidden" name="entity_action" value="delete">
                        <input type="hidden" name="id" value="${assignment.id}">
                        <button type="submit" class="assignment-delete-btn" title="Remove Assignment">×</button>
                    `;
                    
                    // Add both elements to the item
                    item.appendChild(mainContent);
                    item.appendChild(deleteForm);
                    
                    contentContainer.appendChild(item);
                });
            }
            
            // Add the content container to the assignment list
            assignmentList.appendChild(contentContainer);
            
            // Always add the same assignment form at the bottom
            const assignmentForm = document.createElement('form');
            assignmentForm.id = 'assignment-form';
            assignmentForm.className = 'assignment-form';
            assignmentForm.action = '<?= $current_url ?>';
            assignmentForm.method = 'post';
            
            assignmentForm.innerHTML = `
                <input type="hidden" name="entity_name" value="TaskAssignment">
                <input type="hidden" name="entity_action" value="create">
                <input type="hidden" name="task_id" value="${taskId}">
                <input type="hidden" name="user_id" value="1">
                <div class="form-row">
                    <select id="user-select" name="assigned_to" class="user-select" required>
                        ${generateUserOptions(<?= json_encode($users); ?>)}
                    </select>
                    <button type="submit" class="btn-add-assignment">Add Assignment</button>
                </div>
            `;
            
            // Add a separator before the form
            const separator = document.createElement('div');
            separator.className = 'assignment-separator';
            assignmentList.appendChild(separator);
            
            // Add the form to the list
            assignmentList.appendChild(assignmentForm);
            
        } catch (error) {
            console.error("Error parsing assignments:", error);
            assignmentList.innerHTML = '<div class="assignment-item">Error displaying assignments</div>';
        }
        
        // Display the modal
        modal.style.display = 'block';
    }
    
    // Helper function to get role name from role ID
    function getRoleName(roleId) {
        const roles = {
            1: 'Administrator',
            2: 'Manager',
            3: 'User',
            4: 'Guest'
        };
        return roles[roleId] || 'Contributor';
    }
</script>