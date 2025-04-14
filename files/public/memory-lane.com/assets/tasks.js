// Define a configuration object at the top of your tasks.js file
const taskTreeConfig = {
    expandByDefault: false, // Set to true to expand all tasks by default
    maxExpandLevel: 1,      // Maximum level to auto-expand (0 = just root level)
};

// Toggle task children visibility (modified to handle empty containers with forms)
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

// Modified renderTaskTree function with configurable expansion
function renderTaskTree(tasks, parentElement = null, level = 0) {
    const taskList = parentElement || document.getElementById('task-list');
    tasks.forEach(task => {
        // Create task item
        const taskItem      = document.createElement('div');
        taskItem.className  = 'task-item';
        taskItem.dataset.taskId = task.id;
        taskItem.dataset.status = task.status;
        taskItem.dataset.level  = level;
        taskItem.appendChild(createTaskInfo(task, level)); // Build left side (task info)
        taskItem.appendChild(createTaskMeta(task)); // Build right side (task meta)
        
        // Create container for children (even if no children exist)
        const childrenContainer = document.createElement('div');
        childrenContainer.className = 'task-children';
        childrenContainer.id = `children-${task.id}`;
        // Determine if this level should be expanded based on configuration
        const shouldExpand = taskTreeConfig.expandByDefault || level < taskTreeConfig.maxExpandLevel;
        childrenContainer.dataset.visible = shouldExpand ? 'true' : 'false';
        childrenContainer.style.display = shouldExpand ? 'block' : 'none';
        // Update toggle icon to match expansion state
        const toggleIcon = taskItem.querySelector('.toggle-icon');
        toggleIcon.textContent = shouldExpand ? '▼' : '▶';
        toggleIcon.addEventListener('click', toggleChildren);

        // If task has children, recursively render them inside the container
        if (task.has_children) {
            renderTaskTree(task.children, childrenContainer, level + 1);
        }
        // Always add! New task creation form
        childrenContainer.appendChild(createNewTaskForm(task.id, level + 1));
        
        // Add the task item to the full task list
        taskList.appendChild(taskItem);
        // Append the children container to the task list
        taskList.appendChild(childrenContainer);
        
    });
}

// Modified createTaskInfo function to reflect the expansion state
function createTaskInfo(task, level) {
    const htmlString = `
    <div class="task-info">
        <span class="toggle-icon" data-task-id="">▼</span>
        <div class="task-name"></div>
    </div>
    `;
    const template = document.createElement('template');
    template.innerHTML = htmlString.trim();
    const taskInfo = template.content.firstElementChild;
    
    // Add indentation based on level
    if (level > 0) {
        const indentation = document.createElement('span');
        indentation.innerHTML = '&nbsp;'.repeat(level * 4);
        taskInfo.prepend(indentation);
    }
    
    // Add toggle icon for all tasks, regardless of whether they have children
    const toggleIcon = taskInfo.querySelector('span.toggle-icon');
    toggleIcon.dataset.taskId = task.id;
    // Set icon based on default expansion state
    const shouldExpand = taskTreeConfig.expandByDefault || level < taskTreeConfig.maxExpandLevel;
    toggleIcon.textContent = shouldExpand ? '▼' : '▶';
    
    // Task name
    const taskName = taskInfo.querySelector('div.task-name');
    taskName.textContent = task.title;
    
    return taskInfo;
}
// Create the task meta section (right side with details)
function createTaskMeta(task) {
    const taskMeta = document.createElement('div');
    taskMeta.className = 'task-meta';
    
    // Add assignments
    taskMeta.appendChild(createAvatarsElement(task));
    
    // Add due date
    taskMeta.appendChild(createDueDateElement(task));

    // 
    taskMeta.appendChild(createPriorityElement(task));
    taskMeta.appendChild(createStatusElement(task));
    taskMeta.appendChild(createDifficultyElement(task));
    
    
    return taskMeta;
}

/*
** Element creation
** This section is involved with the creation of each interactable element in the task item
*/
function createDueDateElement(task) {
    function updateTaskDueDate(dateElement, taskId, newDate) {

        const dateText = dateElement.querySelector('span.due-date-text');
        if (newDate) {
            // Format the date for display
            const formattedDate = `${newDate.toLocaleString('default', { month: 'short' })} ${newDate.getDate()}`;
            dateText.textContent = formattedDate;
        } else {
            // Clear the date
            dateText.textContent = 'No date';
        }
        
        apiProxyRequest(
            { 
                controller: 'task',
                action: 'update', 
                params: {
                    id: taskId,
                    data: {
                        due_date: newDate
                    }
                }
            },
            function(result) {
                // This will run when the data comes back
                console.log('Success:', result);
            },
            function(result) {
                // This will run when the data comes back
                console.error('Error:', result);
            }
        );
    
    
    }

    const htmlString = `
        <div class="task-due-date" data-task-id="">
            <span>📅</span>
            <span class="due-date-text">No date</span>
        </div>
    `;
    const template = document.createElement('template');
    template.innerHTML = htmlString.trim();
    const taskDueDate = template.content.firstElementChild;
  
    const dueDate = new Date(task.due_date);
    if (isNaN(dueDate.getTime())) {
      dueDate = new Date();
    }
    const dateText = taskDueDate.querySelector('span.due-date-text');
    if (task.due_date) {
        dateText.textContent = `${dueDate.toLocaleString('default', { month: 'short' })} ${dueDate.getDate()}`;
    } else {
        dateText.textContent = 'No date';
    }

    taskDueDate.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Check it out; superman's uncle anchor-el
        const anchorEl = e.currentTarget; // This is the correct reference
    
        let cloned = popoverTemplates.due_date.cloneNode(true);
        cloned.style.display = '';
    
        const dateInput = cloned.querySelector('.date-picker-input');
        dateInput.value = (task.due_date && !isNaN(dueDate.getTime())) 
                            ? dueDate.toISOString().split('T')[0] 
                            : '';
      
    
        const popover = showPopover(anchorEl, cloned, {
            position: 'bottom',
            onOpen: (popoverEl) => {
                const applyBtn = popoverEl.querySelector('.btn-apply-date');
                const clearBtn = popoverEl.querySelector('.btn-clear-date');
                const cancelBtn = popoverEl.querySelector('.btn-cancel-date');
                const dateInput = popoverEl.querySelector('.date-picker-input');
                applyBtn.addEventListener('click', () => {
                    const newDate = new Date(dateInput.value);
                    updateTaskDueDate(anchorEl, task.id, newDate);
                    popover.close();
                });
    
                clearBtn.addEventListener('click', () => {
                    updateTaskDueDate(anchorEl, task.id, null);
                    popover.close();
                });
    
                cancelBtn.addEventListener('click', () => {
                    popover.close();
                });
            }
        });
    
        return popover;
    });
    
    
    return taskDueDate;
}
// Create status badge element with improved error handling and clickable behavior
function createStatusElement(task) {
    const statusBadge = document.createElement('div');
    // Safely access status with default fallback
    const status = status_list[task.status_id].name || 'not_set';
    const statusBadgeClass = statusClasses[status] || 'status-pending';
    // const statusBadgeClass = (task && task.status_badge_class) ? task.status_badge_class : 'status-pending';
    statusBadge.className = `task-status-badge ${statusBadgeClass}`;
    // Format the status text with fallback
    statusBadge.textContent = status !== 'not_set' ? 
        status.charAt(0).toUpperCase() + status.slice(1) : 
        'Pending'; // Default display text
    
    statusBadge.style.cursor = 'pointer';
    statusBadge.dataset.taskId = task.id;
    
    // Add click event listener to show status popover
    statusBadge.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        
        let cloned = popoverTemplates.status.cloneNode(true);
        cloned.style.display = '';
        
        // Populate status options from the statusOptions array
        const optionsContainer = cloned.querySelector('.status-options-container');
        // Clear any existing options
        optionsContainer.innerHTML = '';
        
        // Create and append each status option
        status_list.forEach(option => {
            const statusOption = document.createElement('div');
            statusOption.className = 'status-option';
            statusOption.dataset.status = option.id || option.value || option.status;
            statusOption.textContent = option.name || option.label || option.text;
            
            // Set background color if provided
            if (option.color) {
                statusOption.style.backgroundColor = option.color;
            }
            
            // Append to container
            optionsContainer.appendChild(statusOption);
        });
        
        const popover = showPopover(this, cloned, {
            position: 'bottom',
            className: 'status-popover'
        });
        
        return popover;
    });
    
    return statusBadge;
}
// Create priority element with improved error handling and clickable behavior
function createPriorityElement(task) {
    const taskPriority = document.createElement('div');
    taskPriority.className = 'task-priority';
    
    // Safely access priority with default fallback
    const priority = (task && task.priority) ? task.priority : 'not_set';
    const priorityClass = (task && task.priority_class) ? task.priority_class : 'priority-medium';
    
    const priorityIndicator = document.createElement('span');
    priorityIndicator.className = `priority-indicator ${priorityClass}`;
    
    // Format the priority text with fallback
    priorityIndicator.textContent = priority !== 'not_set' ? 
        priority.charAt(0).toUpperCase() + priority.slice(1) : 
        'Medium'; // Default display text
    
    taskPriority.appendChild(priorityIndicator);
    
    // Make priority indicator clickable
    priorityIndicator.style.cursor = 'pointer';
    
    // Add data attribute for task ID
    priorityIndicator.dataset.taskId = task.id;
    
    // Add click event listener to show priority popover
    priorityIndicator.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        
        let cloned = popoverTemplates.priority.cloneNode(true);
        cloned.style.display = '';
        
        // Populate priority options from the priority_list array
        const optionsContainer = cloned.querySelector('.priority-options-container');
        // Clear any existing options
        optionsContainer.innerHTML = '';
        
        // Create and append each priority option
        priority_list.forEach(option => {
            const priorityOption = document.createElement('div');
            priorityOption.className = 'priority-option';
            priorityOption.dataset.priority = option.id || option.value || option.priority;
            priorityOption.textContent = option.name || option.label || option.text;
            
            // Set background color if provided
            if (option.color) {
                priorityOption.style.backgroundColor = option.color;
            }
            
            // Append to container
            optionsContainer.appendChild(priorityOption);
        });
        
        const popover = showPopover(this, cloned, {
            position: 'bottom',
            className: 'priority-popover'
        });
        
        return popover;
    });
    
    return taskPriority;
}
// Create difficulty element with improved error handling and clickable behavior
function createDifficultyElement(task) {
    const taskDifficulty = document.createElement('div');
    taskDifficulty.className = 'task-difficulty';
    
    // Safely access difficulty with default fallback
    const difficulty = (task && task.difficulty) ? task.difficulty : 'not_set';
    const difficultyClass = (task && task.difficulty_class) ? task.difficulty_class : 'difficulty-medium';
    
    const difficultyIndicator = document.createElement('span');
    difficultyIndicator.className = `difficulty-indicator ${difficultyClass}`;
    
    // Format the difficulty text with fallback
    difficultyIndicator.textContent = difficulty !== 'not_set' ? 
        difficulty.charAt(0).toUpperCase() + difficulty.slice(1) : 
        'Medium'; // Default display text
    
    taskDifficulty.appendChild(difficultyIndicator);
    
    // Make difficulty indicator clickable
    difficultyIndicator.style.cursor = 'pointer';
    
    // Add data attribute for task ID
    difficultyIndicator.dataset.taskId = task.id;
    
    // Add click event listener to show difficulty popover
    difficultyIndicator.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        
        let cloned = popoverTemplates.difficulty.cloneNode(true);
        cloned.style.display = '';
        
        // Populate difficulty options from the difficulty_list array
        const optionsContainer = cloned.querySelector('.difficulty-options-container');
        // Clear any existing options
        optionsContainer.innerHTML = '';
        
        // Create and append each difficulty option
        difficulty_list.forEach(option => {
            const difficultyOption = document.createElement('div');
            difficultyOption.className = 'difficulty-option';
            difficultyOption.dataset.difficulty = option.id || option.value || option.difficulty;
            difficultyOption.textContent = option.name || option.label || option.text;
            
            // Set background color if provided
            if (option.color) {
                difficultyOption.style.backgroundColor = option.color;
            }
            
            // Append to container
            optionsContainer.appendChild(difficultyOption);
        });
        
        const popover = showPopover(this, cloned, {
            position: 'bottom',
            className: 'difficulty-popover'
        });
        
        return popover;
    });
    
    return taskDifficulty;
}





// Enhanced createNewTaskForm function
function createNewTaskForm(parentId, level) {
    const formContainer = document.createElement('div');
    formContainer.className = 'task-item new-task-form';
    formContainer.dataset.level = level;
    
    // Create form element
    const form = document.createElement('form');
    form.action = currentUrl;
    form.method = 'post';
    form.className = 'task-new-form';
    form.style.width = '100%';
    
    // Create hidden inputs
    const hiddenInputs = `
        <input type="hidden" name="entity_name" value="Task">
        <input type="hidden" name="entity_action" value="create">
        <input type="hidden" name="parent_id" value="${parentId}">
        <input type="hidden" name="user_id" value="1">
        <input type="hidden" name="status_id" value="1">
    `;
    
    // Create task info container
    const taskInfo = document.createElement('div');
    taskInfo.className = 'task-info';
    taskInfo.style.width = '100%';
    
    // Add proper indentation
    const indentation = document.createElement('span');
    indentation.innerHTML = '&nbsp;'.repeat((level) * 4);
    taskInfo.appendChild(indentation);
    
    // Add placeholder for toggle icon
    const togglePlaceholder = document.createElement('span');
    togglePlaceholder.className = 'toggle-icon-placeholder';
    taskInfo.appendChild(togglePlaceholder);
    
    // Add status icon
    const statusIcon = document.createElement('div');
    statusIcon.className = 'task-status-icon status-pending-icon';
    taskInfo.appendChild(statusIcon);
    
    // Create enhanced input field
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.name = 'title';
    inputField.className = 'ce-form-input';
    inputField.placeholder = 'Add new subtask...';
    inputField.style.flex = '1';
    inputField.style.marginLeft = '5px';
    
    // Add event listeners
    inputField.addEventListener('focus', function() {
        formContainer.classList.add('focused');
    });
    
    inputField.addEventListener('blur', function() {
        formContainer.classList.remove('focused');
        if (this.value.trim() !== '') {
            form.submit();
        }
    });
    
    // Add keypress event for better UX
    inputField.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (this.value.trim() !== '') {
                form.submit();
            }
        }
    });
    
    // Add elements to form
    taskInfo.appendChild(inputField);
    form.innerHTML = hiddenInputs;
    form.appendChild(taskInfo);
    formContainer.appendChild(form);
    
    return formContainer;
}



/**
 * Creates avatar elements for task assignments with improved structure
 * 
 * @param {Object} task - Task data object
 * @returns {HTMLElement} - Container with avatars
 */
function createAvatarsElement(task) {
    // Create container with proper data attributes
    const container = document.createElement('div');
    container.className = 'task-avatars-container';
    container.dataset.taskId = task.id;
    
    // Setup standardized click handler
    container.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        const clickedElement = event.currentTarget;
        const popover = showAssignmentPopover(clickedElement, task.id);
        return popover;
    });
    
    // Get assignments with fallback
    const assignments = task.assignments || [];
    
    // Handle empty state
    if (assignments.length === 0) {
        const plusIcon = document.createElement('div');
        plusIcon.className = 'task-avatar-add';
        plusIcon.title = 'Add assignment';
        plusIcon.textContent = '+';
        container.appendChild(plusIcon);
        return container;
    }
    
    // Handle populated state
    const maxAvatarsToShow = 3;
    
    // Show avatars up to the maximum
    const avatarsToShow = Math.min(assignments.length, maxAvatarsToShow);
    for (let i = 0; i < avatarsToShow; i++) {
        container.appendChild(createAvatarElement(assignments[i], i));
    }
    
    // Add overflow indicator if needed
    if (assignments.length > maxAvatarsToShow) {
        const moreIndicator = document.createElement('div');
        moreIndicator.className = 'task-avatar-more';
        moreIndicator.textContent = `+${assignments.length - maxAvatarsToShow}`;
        container.appendChild(moreIndicator);
    }
    return container;
}

// The existing createAvatarElement function remains unchanged
// function createAvatarElement(assignment, index) { ... }

// Create a single avatar element
function createAvatarElement(assignment, index) {
    const users = (typeof tasked_users == 'undefined') ? {} : tasked_users;
    const user = users[assignment.assigned_to] ?? {};
    const username = user.username ?? 'User';
    const initial = username.charAt(0).toUpperCase();

    const av = document.createElement('div');
    av.className = 'task-avatar';
    av.title = username;
    av.textContent = initial;
    
    // Different color for each avatar
    av.style.backgroundColor = getAvatarColor(index);
    
    return av;
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


// Transform raw task data into the format needed for rendering
function buildTaskTreeData(tasks) {
    // Define mapping for classes
    // const statusClasses = {
    //     'completed': 'status-active',
    //     'in_progress': 'status-in-progress',
    //     'pending': 'status-pending',
    //     'backlogged': 'status-inactive',
    //     'not_set': 'status-pending'
    // };
    
    const priorityClasses = {
        'high': 'priority-high',
        'medium': 'priority-medium',
        'low': 'priority-low',
        'not_set': 'priority-medium'
    };
    
    const iconClasses = {
        'completed': 'status-completed-icon',
        'in_progress': 'status-in-progress-icon',
        'pending': 'status-pending-icon',
        'backlogged': 'status-on-hold-icon',
        'not_set': 'status-pending-icon'
    };
    
    // Guard against tasks not being an array
    if (!Array.isArray(tasks)) {
        console.error('Expected tasks to be an array, got:', typeof tasks);
        return [];
    }
    
    return tasks.map(task => {
        // Guard against null/undefined task
        if (!task) {
            console.error('Encountered null or undefined task');
            return null;
        }
        
        // Safely get properties with defaults
        // const status = task.status || 'not_set';
        const priority = task.priority || 'not_set';
        const dueDate = task.due_date || null;
        
        const taskData = {
            id: task.id || 0,
            title: task.title || 'Untitled Task',
            status_id: task.status_id, //
            // status: status,
            priority: priority,
            due_date: dueDate,
            // status_class: iconClasses[status] || 'status-pending-icon',
            // status_badge_class: statusClasses[status] || 'status-pending',
            priority_class: priorityClasses[priority] || 'priority-medium',
            has_children: Array.isArray(task.children) && task.children.length > 0,
            assignments: Array.isArray(task.assignments) ? task.assignments : [],
            children: []
        };
        
        // Process children if they exist
        if (Array.isArray(task.children) && task.children.length > 0) {
            taskData.children = buildTaskTreeData(task.children);
        }
        
        return taskData;
    }).filter(task => task !== null); // Remove any null tasks
}




// Helper function to expand all tasks (can be called from a button or programmatically)
function expandAllTasks() {
    const allContainers = document.querySelectorAll('.task-children');
    const allToggleIcons = document.querySelectorAll('.toggle-icon');
    
    allContainers.forEach(container => {
        container.style.display = 'block';
        container.dataset.visible = 'true';
    });
    
    allToggleIcons.forEach(icon => {
        icon.textContent = '▼';
    });
}

// Helper function to collapse all tasks
function collapseAllTasks() {
    const allContainers = document.querySelectorAll('.task-children');
    const allToggleIcons = document.querySelectorAll('.toggle-icon');
    
    allContainers.forEach(container => {
        container.style.display = 'none';
        container.dataset.visible = 'false';
    });
    
    allToggleIcons.forEach(icon => {
        icon.textContent = '▶';
    });
}