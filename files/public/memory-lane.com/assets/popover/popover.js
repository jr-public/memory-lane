/**
 * Popover Module - Creates contextual panels positioned relative to trigger elements
 * 
 * @author Memory Lane Admin Panel
 * @version 1.0.0
 */

/**
 * Create and show a popover next to a trigger element
 * 
 * @param {HTMLElement} triggerElement - The element that triggered the popover
 * @param {HTMLElement|String} content - The content to show in the popover (HTML Element or HTML string)
 * @param {Object} options - Configuration options
 * @param {String} options.position - Position of popover (top, right, bottom, left), defaults to 'bottom'
 * @param {Number} options.offset - Distance from trigger element in pixels, defaults to 5
 * @param {String} options.className - Additional class names for the popover
 * @param {Function} options.onOpen - Callback function when popover opens
 * @param {Function} options.onClose - Callback function when popover closes
 * @returns {Object} - Popover controller with close method
 */
function showPopover(triggerElement, content, options = {}) {
    // Default options
    const defaultOptions = {
        position: 'bottom',
        offset: 5,
        className: '',
        onOpen: null,
        onClose: null
    };
    
    // Merge default options with provided options
    const config = { ...defaultOptions, ...options };
    
    // Generate a unique ID for this popover
    const popoverId = 'popover-' + Math.random().toString(36).substr(2, 9);
    
    // Create popover element
    const popoverElement = document.createElement('div');
    popoverElement.className = `popover ${config.className}`;
    popoverElement.id = popoverId;
    
    // Add content to popover
    if (typeof content === 'string') {
        popoverElement.innerHTML = content;
    } else if (content instanceof HTMLElement) {
        popoverElement.appendChild(content);
    }
    
    // Add popover to DOM
    document.body.appendChild(popoverElement);
    
    // Position popover relative to trigger element
    positionPopover(popoverElement, triggerElement, config.position, config.offset);
    
    // Add active class for animation
    setTimeout(() => {
        popoverElement.classList.add('active');
    }, 10);
    
    // Handle click outside to close popover
    const handleClickOutside = (event) => {
        if (!popoverElement.contains(event.target) && 
            event.target !== triggerElement && 
            !triggerElement.contains(event.target)) {
            closePopover();
        }
    };
    
    // Handle escape key to close popover
    const handleEscKey = (event) => {
        if (event.key === 'Escape') {
            closePopover();
        }
    };
    
    // Attach event listeners
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscKey);
    
    // Call onOpen callback if provided
    if (typeof config.onOpen === 'function') {
        config.onOpen(popoverElement);
    }
    
    // Function to close the popover
    function closePopover() {
        // Remove the active class first (for animation)
        popoverElement.classList.remove('active');
        
        // Wait for animation to complete before removing from DOM
        setTimeout(() => {
            // Remove event listeners
            document.removeEventListener('click', handleClickOutside);
            document.removeEventListener('keydown', handleEscKey);
            
            // Remove the popover from DOM
            if (popoverElement.parentNode) {
                popoverElement.parentNode.removeChild(popoverElement);
            }
            
            // Call onClose callback if provided
            if (typeof config.onClose === 'function') {
                config.onClose();
            }
        }, 200); // Match this with CSS transition time
    }
    
    // Return controller with methods
    return {
        close: closePopover,
        element: popoverElement
    };
}

/**
 * Position the popover relative to the trigger element
 * 
 * @param {HTMLElement} popoverElement - The popover element
 * @param {HTMLElement} triggerElement - The element that triggered the popover
 * @param {String} position - Position of popover (top, right, bottom, left)
 * @param {Number} offset - Distance from trigger element in pixels
 */
function positionPopover(popoverElement, triggerElement, position, offset) {
    // Get the rectangle of the trigger element
    const triggerRect = triggerElement.getBoundingClientRect();
    
    // Get the dimensions of the popover
    const popoverWidth = popoverElement.offsetWidth;
    const popoverHeight = popoverElement.offsetHeight;
    
    // Calculate scroll position
    const scrollX = window.scrollX || window.pageXOffset;
    const scrollY = window.scrollY || window.pageYOffset;
    
    // Calculate position based on the specified direction
    let top, left;
    
    switch (position) {
        case 'top':
            top = triggerRect.top + scrollY - popoverHeight - offset;
            left = triggerRect.left + scrollX + (triggerRect.width / 2) - (popoverWidth / 2);
            popoverElement.classList.add('position-top');
            break;
        case 'right':
            top = triggerRect.top + scrollY + (triggerRect.height / 2) - (popoverHeight / 2);
            left = triggerRect.right + scrollX + offset;
            popoverElement.classList.add('position-right');
            break;
        case 'left':
            top = triggerRect.top + scrollY + (triggerRect.height / 2) - (popoverHeight / 2);
            left = triggerRect.left + scrollX - popoverWidth - offset;
            popoverElement.classList.add('position-left');
            break;
        case 'bottom':
        default:
            top = triggerRect.bottom + scrollY + offset;
            left = triggerRect.left + scrollX + (triggerRect.width / 2) - (popoverWidth / 2);
            popoverElement.classList.add('position-bottom');
            break;
    }
    
    // Set the position
    popoverElement.style.top = `${top}px`;
    popoverElement.style.left = `${left}px`;
    
    // Adjust position if popover is outside viewport
    adjustPositionForViewport(popoverElement);
}

/**
 * Adjust the position of the popover to ensure it stays within the viewport
 * 
 * @param {HTMLElement} popoverElement - The popover element
 */
function adjustPositionForViewport(popoverElement) {
    const rect = popoverElement.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    
    // Adjust horizontal position if needed
    if (rect.right > viewportWidth) {
        const overflowRight = rect.right - viewportWidth;
        popoverElement.style.left = `${parseInt(popoverElement.style.left) - overflowRight - 10}px`;
    }
    
    if (rect.left < 0) {
        popoverElement.style.left = '10px';
    }
    
    // Adjust vertical position if needed
    if (rect.bottom > viewportHeight) {
        const overflowBottom = rect.bottom - viewportHeight;
        popoverElement.style.top = `${parseInt(popoverElement.style.top) - overflowBottom - 10}px`;
    }
    
    if (rect.top < 0) {
        popoverElement.style.top = '10px';
    }
}

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

// Function to show assignment details in a popover instead of a modal
function showAssignmentPopover(clickedElement, assignmentsJson, taskTitle, taskId) {
    // Create the assignment content container
    const contentContainer = document.createElement('div');
    contentContainer.className = 'assignment-popover-content';
    
    try {
        // Parse the JSON string to get assignments
        const assignments = JSON.parse(assignmentsJson);
        
        // Add a header with task name
        const header = document.createElement('div');
        header.className = 'assignment-popover-header';
        header.innerHTML = `<h3>Assignments for: ${escapeHTML(taskTitle)}</h3>`;
        contentContainer.appendChild(header);
        
        // Create the assignment list container
        const listContainer = document.createElement('div');
        listContainer.className = 'assignment-popover-list';
        
        if (assignments.length === 0) {
            // Show empty state message
            const emptyState = document.createElement('div');
            emptyState.className = 'assignment-empty';
            emptyState.innerHTML = `
                <div class="empty-icon">👥</div>
                <div class="empty-text">No users assigned to this task</div>
            `;
            listContainer.appendChild(emptyState);
        } else {
            // Create an element for each assignment
            assignments.forEach((assignment, index) => {
                const item = document.createElement('div');
                item.className = 'assignment-item';
                
                const users = (typeof tasked_users == 'undefined') ? {} : tasked_users;
                const user = users[assignment.assigned_to] ?? {};
                const username = user.username ?? 'User ' + (index+1);
                const role = assignment.role_id ? getRoleName(assignment.role_id) : 'Contributor';
                
                // Create the main assignment content
                const mainContent = document.createElement('div');
                mainContent.className = 'assignment-main-content';
                
                // Use the createAvatarElement function from tasks.js
                const avatarElement = createAvatarElement(assignment, index);
                avatarElement.style.marginRight = '10px';
                avatarElement.classList.add('assignment-avatar');
                
                mainContent.appendChild(avatarElement);
                
                const detailsDiv = document.createElement('div');
                detailsDiv.className = 'assignment-details';
                detailsDiv.innerHTML = `
                    <div class="assignment-name">${escapeHTML(username)}</div>
                    <div class="assignment-role">${escapeHTML(role)}</div>
                `;
                
                mainContent.appendChild(detailsDiv);
                
                // Create the delete button as a form
                const deleteForm = document.createElement('form');
                deleteForm.className = 'assignment-delete-form';
                deleteForm.action = currentUrl;
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
                
                listContainer.appendChild(item);
            });
        }
        
        contentContainer.appendChild(listContainer);
        
        // Add a separator
        const separator = document.createElement('div');
        separator.className = 'assignment-separator';
        contentContainer.appendChild(separator);
        
        // Add the assignment form
        const assignmentForm = document.createElement('form');
        assignmentForm.id = 'assignment-form';
        assignmentForm.className = 'assignment-form';
        assignmentForm.action = currentUrl;
        assignmentForm.method = 'post';
        
        assignmentForm.innerHTML = `
            <input type="hidden" name="entity_name" value="TaskAssignment">
            <input type="hidden" name="entity_action" value="create">
            <input type="hidden" name="task_id" value="${taskId}">
            <input type="hidden" name="user_id" value="1">
            <div class="form-row">
                <select id="user-select" name="assigned_to" class="user-select" required>
                    ${generateUserOptions(tasked_users)}
                </select>
                <button type="submit" class="btn-add-assignment">Add</button>
            </div>
        `;
        
        contentContainer.appendChild(assignmentForm);
        
    } catch (error) {
        console.error("Error parsing assignments:", error);
        contentContainer.innerHTML = '<div class="assignment-error">Error displaying assignments</div>';
    }
    
    // Show the popover with the content we created
    return showPopover(clickedElement, contentContainer, {
        position: 'bottom',
        className: 'assignment-popover',
        onOpen: (popoverEl) => {
            // Add any event listeners needed for the popover content
        }
    });
}

// Function to generate user options HTML
function generateUserOptions(users) {
    if (!users || typeof users !== 'object') return '<option value="">No users available</option>';
    
    let options = '<option value="">Select a user</option>';
    const usersArray = Array.isArray(users) ? users : Object.values(users);
    
    usersArray.forEach(user => {
        if (user && user.id && user.username) {
            options += `<option value="${user.id}">${escapeHTML(user.username)}</option>`;
        }
    });
    
    return options;
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