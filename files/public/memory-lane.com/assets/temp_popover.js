
// Date popover helper for the task date element
function showDatePopover(dateElement, currentDate) {
    // Create the date selection content
    const dateContent = document.createElement('div');
    dateContent.className = 'date-popover-content';
    
    // Parse the current date or use today
    let selectedDate;
    try {
        selectedDate = currentDate ? new Date(currentDate) : new Date();
    } catch (e) {
        selectedDate = new Date();
    }
    
    // Format date for input value
    const formattedDate = selectedDate.toISOString().split('T')[0];
    
    // Create a simple date picker
    dateContent.innerHTML = `
        <div class="date-picker-header">
            <h3>Select Due Date</h3>
        </div>
        <div class="date-picker-body">
            <input type="date" class="date-picker-input" value="${formattedDate}">
        </div>
        <div class="date-picker-footer">
            <button class="btn-apply-date">Apply</button>
            <button class="btn-clear-date">Clear</button>
            <button class="btn-cancel-date">Cancel</button>
        </div>
    `;
    
    // Show the popover
    const popover = showPopover(dateElement, dateContent, {
        position: 'bottom',
        className: 'date-popover',
        onOpen: (popoverEl) => {
            // Setup event listeners for date picker actions
            const applyBtn = popoverEl.querySelector('.btn-apply-date');
            const clearBtn = popoverEl.querySelector('.btn-clear-date');
            const cancelBtn = popoverEl.querySelector('.btn-cancel-date');
            const dateInput = popoverEl.querySelector('.date-picker-input');
            
            // Apply button handler
            applyBtn.addEventListener('click', () => {
                const newDate = new Date(dateInput.value);
                updateTaskDate(dateElement, newDate);
                popover.close();
            });
            
            // Clear button handler
            clearBtn.addEventListener('click', () => {
                updateTaskDate(dateElement, null);
                popover.close();
            });
            
            // Cancel button handler
            cancelBtn.addEventListener('click', () => {
                popover.close();
            });
        }
    });
    
    return popover;
}

// Helper function to update task date display
function updateTaskDate(dateElement, newDate) {
    // Find the text span inside the date element
    const dateText = dateElement.querySelector('span:last-child');
    
    if (!dateText) return;
    
    if (newDate) {
        // Format the date for display
        const formattedDate = `${newDate.toLocaleString('default', { month: 'short' })} ${newDate.getDate()}`;
        dateText.textContent = formattedDate;
        
        // Here you would also update the task in your backend
        // This is a placeholder for the integration with your task system
        console.log('Date updated to:', newDate.toISOString());
    } else {
        // Clear the date
        dateText.textContent = 'No date';
        console.log('Date cleared');
    }
}
/**
 * Shows assignment popover with dynamic content
 * 
 * @param {HTMLElement} clickedElement - The element that was clicked to trigger the popover
 * @param {string|number} taskId - The ID of the task whose assignments to show
 * @returns {Object} - The popover controller
 */
function showAssignmentPopover(clickedElement, taskId) {
    // Get the task data
    const taskData = task_list[taskId];
    if (!taskData) {
        console.error(`Task with ID ${taskId} not found`);
        return;
    }
    
    // Clone the template
    const template = document.getElementById('assignment_popover');
    if (!template) {
        console.error('Assignment popover template not found');
        return;
    }
    
    const contentContainer = template.cloneNode(true);
    contentContainer.style.display = ''; // Make visible
    
    // Update task title
    const titleElement = contentContainer.querySelector('.task-title-placeholder');
    if (titleElement) {
        titleElement.textContent = `Assignments for: ${escapeHTML(taskData.title)}`;
    }
    
    // Update task ID in the form
    const taskIdInputs = contentContainer.querySelectorAll('.task-id-input');
    taskIdInputs.forEach(input => {
        input.value = taskId;
    });
    
    // Set the form action
    const forms = contentContainer.querySelectorAll('form');
    forms.forEach(form => {
        form.action = currentUrl;
    });
    
    // Handle assignments
    const assignments = taskData.assignments || [];
    const emptyState = contentContainer.querySelector('.assignment-empty');
    const itemsContainer = contentContainer.querySelector('.assignment-items-container');
    const itemTemplate = contentContainer.querySelector('.assignment-item-template');
    
    if (assignments.length === 0) {
        // Show empty state
        if (emptyState) emptyState.style.display = '';
        if (itemsContainer) itemsContainer.style.display = 'none';
    } else {
        // Hide empty state, show items container
        if (emptyState) emptyState.style.display = 'none';
        if (itemsContainer) itemsContainer.style.display = '';
        
        // Populate assignments
        if (itemTemplate && itemsContainer) {
            assignments.forEach((assignment, index) => {
                // Clone the template
                const item = itemTemplate.cloneNode(true);
                item.classList.remove('assignment-item-template');
                item.style.display = '';
                
                // Get user data
                const users = (typeof tasked_users === 'undefined') ? {} : tasked_users;
                const user = users[assignment.assigned_to] ?? {};
                const username = user.username ?? 'User ' + (index + 1);
                const role = assignment.role_id ? getRoleName(assignment.role_id) : 'Contributor';
                
                // Set assignment ID for deletion
                const idInput = item.querySelector('.assignment-id-input');
                if (idInput) {
                    idInput.value = assignment.id;
                }
                
                // Set avatar
                const avatarElement = item.querySelector('.assignment-avatar');
                if (avatarElement) {
                    // Get initial letter
                    const initial = username.charAt(0).toUpperCase();
                    avatarElement.textContent = initial;
                    
                    // Set background color
                    avatarElement.style.backgroundColor = getAvatarColor(index);
                }
                
                // Set name and role
                const nameElement = item.querySelector('.assignment-name');
                if (nameElement) {
                    nameElement.textContent = username;
                }
                
                const roleElement = item.querySelector('.assignment-role');
                if (roleElement) {
                    roleElement.textContent = role;
                }
                
                // Add to container
                itemsContainer.appendChild(item);
            });
            
            // Remove the template from the DOM
            if (itemTemplate.parentNode) {
                itemTemplate.parentNode.removeChild(itemTemplate);
            }
        }
    }
    
    // Populate user select dropdown
    const userSelect = contentContainer.querySelector('.user-select');
    if (userSelect) {
        let options = '<option value="">Select a user</option>';
        const usersArray = Array.isArray(tasked_users) ? tasked_users : Object.values(tasked_users);
        
        usersArray.forEach(user => {
            if (user && user.id && user.username) {
                options += `<option value="${user.id}">${escapeHTML(user.username)}</option>`;
            }
        });
        userSelect.innerHTML = options;
    }
    
    // Show the popover with the content we created
    return showPopover(clickedElement, contentContainer, {
        position: 'bottom',
        className: 'assignment-popover',
        onOpen: (popoverEl) => {
            // Any additional initialization after popover is shown
        }
    });
}

// Helper function to escape HTML
function escapeHTML(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
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
