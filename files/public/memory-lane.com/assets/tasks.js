// Define a configuration object at the top of your tasks.js file
const taskTreeConfig = {
    expandByDefault: false, // Set to true to expand all tasks by default
    maxExpandLevel: 1,      // Maximum level to auto-expand (0 = just root level)
  };
  
  // Modified renderTaskTree function with configurable expansion
  function renderTaskTree(tasks, parentElement = null, level = 0) {
      const taskList = parentElement || document.getElementById('task-list');
     
      tasks.forEach(task => {
          // Create task item
          const taskItem = createTaskItem(task, level);
          taskList.appendChild(taskItem);
         
          // Create container for children (even if no children exist)
          const childrenContainer = document.createElement('div');
          childrenContainer.className = 'task-children';
          childrenContainer.id = `children-${task.id}`;
          
          // Determine if this level should be expanded based on configuration
          const shouldExpand = taskTreeConfig.expandByDefault || level < taskTreeConfig.maxExpandLevel;
          
          childrenContainer.dataset.visible = shouldExpand ? 'true' : 'false';
          childrenContainer.style.display = shouldExpand ? 'block' : 'none';
          
          // Always add a new task creation form
          childrenContainer.appendChild(createNewTaskForm(task.id, level + 1));
          
          // If task has children, render them inside the container
          if (task.has_children) {
              // Recursively render children within this container
              renderTaskTree(task.children, childrenContainer, level + 1);
          }
         
          
          // Append the children container to the task list
          taskList.appendChild(childrenContainer);
          
          // Update toggle icon to match expansion state
          const toggleIcon = taskItem.querySelector('.toggle-icon');
          if (toggleIcon) {
              toggleIcon.textContent = shouldExpand ? '▼' : '▶';
          }
      });
  }
  
  // Modified createTaskInfo function to reflect the expansion state
  function createTaskInfo(task, level) {
      const taskInfo = document.createElement('div');
      taskInfo.className = 'task-info';
      
      // Add indentation based on level
      if (level > 0) {
          const indentation = document.createElement('span');
          indentation.innerHTML = '&nbsp;'.repeat(level * 4);
          taskInfo.appendChild(indentation);
      }
      
      // Add toggle icon for all tasks, regardless of whether they have children
      const toggleIcon = document.createElement('span');
      toggleIcon.className = 'toggle-icon';
      toggleIcon.dataset.taskId = task.id;
      
      // Set icon based on default expansion state
      const shouldExpand = taskTreeConfig.expandByDefault || level < taskTreeConfig.maxExpandLevel;
      toggleIcon.textContent = shouldExpand ? '▼' : '▶';
      
      toggleIcon.addEventListener('click', toggleChildren);
      taskInfo.appendChild(toggleIcon);
      
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
// Create new task input form
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
    form.innerHTML = `
        <input type="hidden" name="entity_name" value="Task">
        <input type="hidden" name="entity_action" value="create">
        <input type="hidden" name="parent_id" value="${parentId}">
        <input type="hidden" name="user_id" value="1">
        <input type="hidden" name="status_id" value="1">
        <div class="task-info" style="width: 100%">
            <span>${'&nbsp;'.repeat((level) * 4)}</span>
            <span class="toggle-icon-placeholder"></span>
            <div class="task-status-icon status-pending-icon"></div>
            <input type="text" name="title" class="ce-form-input" placeholder="New subtask name..." style="flex: 1; margin-left: 5px; height: 30px; padding: 5px 10px;">
        </div>
    `;
    
    // Add event listeners for form submission
    form.addEventListener('submit', function(e) {
        if (form.querySelector('input[name="title"]').value.trim() === '') {
            e.preventDefault();
        }
    });
    
    // Add blur event to submit when clicking outside
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.name = 'title';
    inputField.className = 'ce-form-input';
    inputField.placeholder = 'New subtask name...';
    inputField.style.flex = '1';
    inputField.style.marginLeft = '5px';
    inputField.style.height = '30px';
    inputField.style.padding = '5px 10px';
    
    inputField.addEventListener('blur', function() {
        if (this.value.trim() !== '') {
            form.submit();
        }
    });
    
    // Replace the input in the form with our enhanced input
    form.querySelector('input[name="title"]').replaceWith(inputField);
    
    formContainer.appendChild(form);
    return formContainer;
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

// Create priority element with improved error handling
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
    return taskPriority;
}

// Modified createDueDateElement function to use the popover
function createDueDateElement(task) {
    const taskDueDate = document.createElement('div');
    taskDueDate.className = 'task-due-date';
    taskDueDate.dataset.taskId = task.id;
    
    const dateIcon = document.createElement('span');
    dateIcon.textContent = '📅';
    taskDueDate.appendChild(dateIcon);
    
    const dateText = document.createElement('span');
    if (task.due_date) {
        const dueDate = new Date(task.due_date);
        dateText.textContent = `${dueDate.toLocaleString('default', { month: 'short' })} ${dueDate.getDate()}`;
    } else {
        dateText.textContent = 'No date';
    }
    taskDueDate.appendChild(dateText);
    
    // Add click event to show date popover
    taskDueDate.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent event bubbling to task toggle
        showDatePopover(this, task.due_date);
    });
    
    return taskDueDate;
}

// Create status badge element with improved error handling
function createStatusBadge(task) {
    const statusBadge = document.createElement('div');
    
    // Safely access status with default fallback
    const status = (task && task.status) ? task.status : 'not_set';
    const statusBadgeClass = (task && task.status_badge_class) ? task.status_badge_class : 'status-pending';
    
    statusBadge.className = `task-status-badge ${statusBadgeClass}`;
    
    // Format the status text with fallback
    statusBadge.textContent = status !== 'not_set' ? 
        status.charAt(0).toUpperCase() + status.slice(1) : 
        'Pending'; // Default display text
    
    return statusBadge;
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
    const statusClasses = {
        'completed': 'status-active',
        'in_progress': 'status-in-progress',
        'pending': 'status-pending',
        'backlogged': 'status-inactive',
        'not_set': 'status-pending'
    };
    
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
        const status = task.status || 'not_set';
        const priority = task.priority || 'not_set';
        const dueDate = task.due_date || null;
        
        const taskData = {
            id: task.id || 0,
            title: task.title || 'Untitled Task',
            status: status,
            priority: priority,
            due_date: dueDate,
            status_class: iconClasses[status] || 'status-pending-icon',
            status_badge_class: statusClasses[status] || 'status-pending',
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