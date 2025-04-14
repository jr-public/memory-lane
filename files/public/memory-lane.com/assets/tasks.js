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
    
    taskName.style.cursor = 'pointer';
    taskName.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent triggering parent elements
        openTaskPanel(task);
    });
    
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
function openTaskPanel(task) {
    // Find the complete task data from our task list
    const fullTaskData = task_list[task.id];
    
    if (!fullTaskData) {
        console.error(`Task with ID ${task.id} not found in task list`);
        return;
    }
    
    // Open the task panel with the full task data
    if (window.taskPanel) {
        window.taskPanel.open(fullTaskData);
    } else {
        console.error('Task panel not initialized');
    }
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
function createStatusElement(task) {
    const status = status_list[task.status_id];

    const statusBadge = document.createElement('div');
    statusBadge.className = 'task-status-badge';
    statusBadge.style.backgroundColor = status.color;
    statusBadge.textContent = status.name;
    statusBadge.style.cursor = 'pointer';
    statusBadge.dataset.taskId = task.id;
    
    statusBadge.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        
        let cloned = popoverTemplates.status.cloneNode(true);
        cloned.style.display = '';
        
        // Populate status options from the statusOptions array
        const optionsContainer = cloned.querySelector('.status-options-container');
        // Clear any existing options
        optionsContainer.innerHTML = '';
        
        // Create and append each status option as a form
        Object.values(status_list).forEach(option => {
            // Create form element
            const form = document.createElement('form');
            form.action = currentUrl;
            form.method = 'post';
            
            // Add necessary hidden inputs
            form.innerHTML = `
                <input type="hidden" name="entity_name" value="Task">
                <input type="hidden" name="entity_action" value="update">
                <input type="hidden" name="id" value="${task.id}">
                <input type="hidden" name="status_id" value="${option.id}">
            `;
            
            // Create the status option that will act as a submit button
            const statusOption = document.createElement('button');
            statusOption.type = 'submit';
            statusOption.className = 'status-option';
            statusOption.textContent = option.name;
            
            // Style the button to look like the previous div
            statusOption.style.width = '100%';
            statusOption.style.textAlign = 'center';
            statusOption.style.border = 'none';
            statusOption.style.cursor = 'pointer';
            
            // Set background color if provided
            if (option.color) {
                statusOption.style.backgroundColor = option.color;
            }
            
            // Append the button to the form
            form.appendChild(statusOption);
            
            // Append the form to the container
            optionsContainer.appendChild(form);
        });
        
        // Here's the AJAXY version
        // optionsContainer.addEventListener('click', function(e) {
        //     const option = e.target.closest('.status-option');
        //     if (option) {
        //         let newId = option.dataset.id;
        //         let taskId = option.dataset.task_id;
        //         apiProxyRequest(
        //             { 
        //                 controller: 'task',
        //                 action: 'update', 
        //                 params: {
        //                     id: taskId,
        //                     data: {
        //                         status_id: newId
        //                     }
        //                 }
        //             },
        //             function(result) {
        //                 // This will run when the data comes back
        //                 console.log('Success:', result);
        //             },
        //             function(result) {
        //                 // This will run when the data comes back
        //                 console.error('Error:', result);
        //             }
        //         );
        //     }
        // });

        const popover = showPopover(this, cloned, {
            position: 'bottom',
            className: 'status-popover'
        });
        
        return popover;
    });
    
    return statusBadge;
}
function createPriorityElement(task) {
    
    const priority = priority_list[task.priority_id];
    
    // Create priority badge element
    const priorityBadge = document.createElement('div');
    priorityBadge.className = 'priority-indicator';
    priorityBadge.style.backgroundColor = priority.color;
    // priorityBadge.classList.add(`priority-${priority.name}`);
    priorityBadge.textContent = priority.name;
    priorityBadge.style.cursor = 'pointer';
    priorityBadge.dataset.taskId = task.id;
    
    // Add click event listener to show priority popover
    priorityBadge.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        
        // Clone the priority popover template
        let cloned = popoverTemplates.priority.cloneNode(true);
        cloned.style.display = '';
        
        // Populate priority options container
        const optionsContainer = cloned.querySelector('.priority-options-container');
        optionsContainer.innerHTML = ''; // Clear any existing options
        
        // Create and append each priority option as a form
        Object.values(priority_list).forEach(option => {
            // Create form element
            const form = document.createElement('form');
            form.action = currentUrl;
            form.method = 'post';
            
            // Add necessary hidden inputs
            form.innerHTML = `
                <input type="hidden" name="entity_name" value="Task">
                <input type="hidden" name="entity_action" value="update">
                <input type="hidden" name="id" value="${task.id}">
                <input type="hidden" name="priority_id" value="${option.id}">
            `;
            
            // Create the priority option that will act as a submit button
            const priorityOption = document.createElement('button');
            priorityOption.type = 'submit';
            priorityOption.className = 'priority-option';
            priorityOption.textContent = option.name;
            
            // Style the button to match design
            priorityOption.style.width = '100%';
            priorityOption.style.textAlign = 'center';
            priorityOption.style.border = 'none';
            priorityOption.style.cursor = 'pointer';
            
            // Set background color
            if (option.color) {
                priorityOption.style.backgroundColor = option.color;
            }
            
            // Append the button to the form
            form.appendChild(priorityOption);
            
            // Append the form to the container
            optionsContainer.appendChild(form);
        });
        
        // Show the popover
        const popover = showPopover(this, cloned, {
            position: 'bottom',
            className: 'priority-popover'
        });
        
        return popover;
    });
    
    return priorityBadge;
}
function createDifficultyElement(task) {

    
    const difficulty = difficulty_list[task.difficulty_id];

    const taskDifficulty = document.createElement('div');
    taskDifficulty.textContent = difficulty.name;
    taskDifficulty.className = 'difficulty-indicator';
    taskDifficulty.style.backgroundColor = difficulty.color;
    
    
    // Make difficulty indicator clickable
    taskDifficulty.style.cursor = 'pointer';
    
    // Add data attribute for task ID
    taskDifficulty.dataset.taskId = task.id;
    
    // Add click event listener to show difficulty popover
    taskDifficulty.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling to task toggle
        
        let cloned = popoverTemplates.difficulty.cloneNode(true);
        cloned.style.display = '';
        
        // Populate difficulty options from the difficulty_list array
        const optionsContainer = cloned.querySelector('.difficulty-options-container');
        // Clear any existing options
        optionsContainer.innerHTML = '';
        
        // Create and append each difficulty option as a form
        Object.values(difficulty_list).forEach(option => {
            // Create form element
            const form = document.createElement('form');
            form.action = currentUrl;
            form.method = 'post';
            
            // Add necessary hidden inputs
            form.innerHTML = `
                <input type="hidden" name="entity_name" value="Task">
                <input type="hidden" name="entity_action" value="update">
                <input type="hidden" name="id" value="${task.id}">
                <input type="hidden" name="difficulty_id" value="${option.id}">
            `;
            
            // Create the difficulty option button
            const difficultyOption = document.createElement('button');
            difficultyOption.type = 'submit';
            difficultyOption.className = 'difficulty-option';
            difficultyOption.textContent = option.name;
            
            // Style the button
            difficultyOption.style.width = '100%';
            difficultyOption.style.textAlign = 'center';
            difficultyOption.style.border = 'none';
            difficultyOption.style.cursor = 'pointer';
            difficultyOption.style.backgroundColor = option.color;
            
            // Append the button to the form
            form.appendChild(difficultyOption);
            
            // Append the form to the container
            optionsContainer.appendChild(form);
        });
        
        // Hook up the Edit difficulties button if needed
        const editButton = cloned.querySelector('.difficulty-edit-btn');
        if (editButton) {
            editButton.addEventListener('click', function() {
                // Implement difficulty management if needed
                alert('Difficulty management feature coming soon');
            });
        }
        
        // Show the popover
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
        
        const taskData = {
            id: task.id || 0,
            title: task.title || 'Untitled Task',
            status_id: task.status_id,
            priority_id: task.priority_id,
            difficulty_id: task.difficulty_id,
            due_date: task.due_date,
            has_children: Array.isArray(task.children) && task.children.length > 0,
            assignments: Array.isArray(task.assignments) ? task.assignments : [],
            comments: task.comments,
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