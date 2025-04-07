// Render task list recursively
function renderTaskTree(tasks, parentElement = null, level = 0) {
    const taskList = parentElement || document.getElementById('task-list');
    
    tasks.forEach(task => {
        // Create task item
        const taskItem = createTaskItem(task, level);
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

// Create the main task item element
function createTaskItem(task, level) {
    // Create task item container
    const taskItem = document.createElement('div');
    taskItem.className = 'task-item';
    taskItem.dataset.taskId = task.id;
    taskItem.dataset.status = task.status;
    taskItem.dataset.level = level;
    
    // Build left side (task info)
    taskItem.appendChild(createTaskInfo(task, level));
    
    // Build right side (task meta)
    taskItem.appendChild(createTaskMeta(task));
    
    return taskItem;
}

// Create the task info section (left side with title and status)
function createTaskInfo(task, level) {
    const taskInfo = document.createElement('div');
    taskInfo.className = 'task-info';
    
    // Add indentation based on level
    if (level > 0) {
        const indentation = document.createElement('span');
        indentation.innerHTML = '&nbsp;'.repeat(level * 4);
        taskInfo.appendChild(indentation);
    }
    
    // Add toggle icon or placeholder
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
    
    return taskInfo;
}

// Create the task meta section (right side with details)
function createTaskMeta(task) {
    const taskMeta = document.createElement('div');
    taskMeta.className = 'task-meta';
    
    // Add assignments
    const taskAssignments = document.createElement('div');
    taskAssignments.className = 'task-assignments';
    taskAssignments.appendChild(createAvatarsElement(task));
    taskMeta.appendChild(taskAssignments);
    
    // Add priority indicator
    taskMeta.appendChild(createPriorityElement(task));
    
    // Add due date
    taskMeta.appendChild(createDueDateElement(task));
    
    // Add status badge
    taskMeta.appendChild(createStatusBadge(task));
    
    return taskMeta;
}

// Create priority element
function createPriorityElement(task) {
    const taskPriority = document.createElement('div');
    taskPriority.className = 'task-priority';
    const priorityIndicator = document.createElement('span');
    priorityIndicator.className = `priority-indicator ${task.priority_class}`;
    priorityIndicator.textContent = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);
    taskPriority.appendChild(priorityIndicator);
    return taskPriority;
}

// Create due date element
function createDueDateElement(task) {
    const taskDueDate = document.createElement('div');
    taskDueDate.className = 'task-due-date';
    const dateIcon = document.createElement('span');
    dateIcon.textContent = '📅';
    taskDueDate.appendChild(dateIcon);
    
    const dateText = document.createElement('span');
    const dueDate = new Date(task.due_date);
    dateText.textContent = `${dueDate.toLocaleString('default', { month: 'short' })} ${dueDate.getDate()}`;
    taskDueDate.appendChild(dateText);
    
    return taskDueDate;
}

// Create status badge element
function createStatusBadge(task) {
    const statusBadge = document.createElement('div');
    statusBadge.className = `task-status-badge ${task.status_badge_class}`;
    statusBadge.textContent = task.status.charAt(0).toUpperCase() + task.status.slice(1);
    return statusBadge;
}

// Toggle task children visibility (no changes needed)
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

// Create avatar elements for task assignments (minimal changes)
function createAvatarsElement(task) {
    const container = document.createElement('div');
    const assignments = task.assignments || [];
    const maxAvatarsToShow = 3;
    
    // If no assignments, show a plus icon
    if (assignments.length === 0) {
        container.className = 'task-avatars-container';
        
        // Create a plus icon element
        const plusIcon = document.createElement('div');
        plusIcon.className = 'task-avatar-add';
        plusIcon.title = 'Add assignment';
        plusIcon.textContent = '+';
        container.appendChild(plusIcon);
        
        // Add data attributes instead of onclick
        container.dataset.taskId = task.id;
        container.dataset.taskTitle = task.title;
        container.dataset.assignments = '[]';
        container.dataset.action = 'show-assignments';
        
        return container;
    }
    
    // Create avatar container
    container.className = 'task-avatars-container';
    
    // Add data attributes
    container.dataset.taskId = task.id;
    container.dataset.taskTitle = task.title;
    container.dataset.assignments = JSON.stringify(assignments);
    container.dataset.action = 'show-assignments';
    
    // Show avatars up to the maximum
    const avatarsToShow = Math.min(assignments.length, maxAvatarsToShow);
    for (let i = 0; i < avatarsToShow; i++) {
        container.appendChild(createAvatarElement(assignments[i], i));
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

// Create a single avatar element
function createAvatarElement(assignment, index) {
    const username = assignment.username || 'User';
    const initial = username.charAt(0).toUpperCase();
    
    const avatar = document.createElement('div');
    avatar.className = 'task-avatar';
    avatar.title = username;
    avatar.textContent = initial;
    
    // Different color for each avatar
    avatar.style.backgroundColor = getAvatarColor(index);
    
    return avatar;
}

// Get avatar color based on index (unchanged)
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

