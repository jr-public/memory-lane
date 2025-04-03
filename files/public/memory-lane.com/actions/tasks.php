<?php
// Fetch task data using API call
if (true) { // TREE
    $res = api_call("Task", "tree", [
        "options" => [
            "with" => ["assignments"]
        ]
    ]);
}
if (!$res['success']) {
    die('Tasks list failed');
}

// Store tasks data
$tasks = $res['data'];

// Helper functions for task display
function getStatusBadgeClass($status) {
    $statusClasses = [
        'completed' => 'status-active',
        'in_progress' => 'status-in-progress',
        'pending' => 'status-pending',
        'backlogged' => 'status-inactive',
        'not_set' => 'status-pending'
    ];
    
    return $statusClasses[$status] ?? 'status-pending';
}

function getPriorityBadgeClass($priority) {
    $priorityClasses = [
        'high' => 'priority-high',
        'medium' => 'priority-medium',
        'low' => 'priority-low',
        'not_set' => 'priority-medium'
    ];
    
    return $priorityClasses[$priority] ?? 'priority-medium';
}

function getStatusIconClass($status) {
    $iconClasses = [
        'completed' => 'status-completed-icon',
        'in_progress' => 'status-in-progress-icon',
        'pending' => 'status-pending-icon',
        'backlogged' => 'status-on-hold-icon',
        'not_set' => 'status-pending-icon'
    ];
    
    return $iconClasses[$status] ?? 'status-pending-icon';
}

// Build task tree data structure for template
function buildTaskTreeData($tasks) {
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
            'status_class' => getStatusIconClass($task['status']),
            'status_badge_class' => getStatusBadgeClass($task['status']),
            'priority_class' => getPriorityBadgeClass($task['priority']),
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

// Encode task data for JavaScript
$taskDataJson = json_encode($taskTreeData);
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
    document.addEventListener('DOMContentLoaded', function() {
        // Task data from PHP
        const taskData = <?php echo $taskDataJson; ?>;
        
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
        
        // If no assignments, show empty state
        if (assignments.length === 0) {
            container.className = 'task-avatars-empty';
            container.textContent = 'No users assigned';
            return container;
        }
        
        // Create avatar container
        container.className = 'task-avatars-container';
        
        // For click event - stringify assignments data
        const assignmentsData = JSON.stringify(assignments);
        container.setAttribute('onclick', `showAssignmentDetails('${escapeString(assignmentsData)}', '${escapeString(task.title)}')`);
        
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
    
    // Function to show assignment details in modal
    function showAssignmentDetails(assignmentsJson, taskTitle) {
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
            
            if (assignments.length === 0) {
                assignmentList.innerHTML = '<div class="assignment-item">No users assigned to this task.</div>';
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
                    
                    item.innerHTML = `
                        <div class="assignment-avatar" style="background-color: ${color};">${initial}</div>
                        <div class="assignment-details">
                            <div class="assignment-name">${username}</div>
                            <div class="assignment-role">${role}</div>
                        </div>
                    `;
                    
                    assignmentList.appendChild(item);
                });
            }
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