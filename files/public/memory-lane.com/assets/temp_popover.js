
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
function showAssignmentPopover(clickedElement, taskTitle, taskId) {
	if ( !task_list ) {
		console.error("NO TASKS");
		return false;
	}
    // Create the assignment content container
    const contentContainer = document.createElement('div');
    contentContainer.className = 'assignment-popover-content';
    
    try {
        // Parse the JSON string to get assignments
        const assignments = task_list[taskId].assignments ?? [];
        
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