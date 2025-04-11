<div class="assignment-popover-content">
    <!-- Popover Header -->
    <div class="assignment-popover-header">
        <h3 class="task-title-placeholder">Assignments for: Task Title</h3>
    </div>
    
    <!-- Assignment List Container -->
    <div class="assignment-popover-list">
        <!-- Empty State Message (shown when no assignments) -->
        <div class="assignment-empty">
            <div class="empty-icon">👥</div>
            <div class="empty-text">No users assigned to this task</div>
        </div>
        
        <!-- Assignment List (populated by JavaScript) -->
        <div class="assignment-items-container" style="display:none;">
            <!-- Template for a single assignment item -->
            <div class="assignment-item-template" style="display:none;">
                <div class="assignment-main-content">
                    <div class="assignment-avatar"></div>
                    <div class="assignment-details">
                        <div class="assignment-name"></div>
                        <div class="assignment-role"></div>
                    </div>
                </div>
                <div class="assignment-actions">
                    <form class="assignment-delete-form" action="" method="post">
                        <input type="hidden" name="entity_name" value="TaskAssignment">
                        <input type="hidden" name="entity_action" value="delete">
                        <input type="hidden" name="id" value="" class="assignment-id-input">
                        <button type="submit" class="assignment-delete-btn" title="Remove Assignment">×</button>
                    </form>
                </div>
            </div>
            <!-- Actual assignment items will be cloned from the template and inserted here -->
        </div>
    </div>
    
    <!-- Separator -->
    <div class="assignment-separator"></div>
    
    <!-- Assignment Form -->
    <form id="assignment-form" class="assignment-form" action="" method="post">
        <input type="hidden" name="entity_name" value="TaskAssignment">
        <input type="hidden" name="entity_action" value="create">
        <input type="hidden" name="task_id" value="" class="task-id-input"> <!-- Will be set by JS -->
        <input type="hidden" name="user_id" value="1">
        <div class="form-row">
            <select id="user-select" name="assigned_to" class="user-select" required>
                <option value="">Select a user</option>
                <!-- User options will be populated by JavaScript -->
            </select>
            <button type="submit" class="btn-add-assignment">Add</button>
        </div>
    </form>
</div>
<script>
/**
 * Shows assignment popover with improved data flow patterns
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
    
    // Clone template
    let cloned = popoverTemplates.assignment.cloneNode(true);
    cloned.style.display = '';
    
    // Update task title
    const titleElement = cloned.querySelector('.task-title-placeholder');
    if (titleElement) {
        titleElement.textContent = `Assignments for: ${escapeHTML(taskData.title)}`;
    }
    
    // Setup forms and data containers
    setupAssignmentForms(cloned, taskId);
    
    // Populate assignments list
    populateAssignmentsList(cloned, taskData.assignments || []);
    
    // Setup user selection dropdown
    setupUserSelectionDropdown(cloned);
    
    // Show the popover with the content we created
    return showPopover(clickedElement, cloned, {
        position: 'bottom',
        className: 'assignment-popover',
        onOpen: (popoverEl) => {
            // Additional initialization if needed
        }
    });
}

/**
 * Sets up assignment form elements with proper IDs and actions
 * 
 * @param {HTMLElement} container - The popover container
 * @param {string|number} taskId - The task ID
 */
function setupAssignmentForms(container, taskId) {
    // Update task ID in all forms
    const taskIdInputs = container.querySelectorAll('.task-id-input');
    taskIdInputs.forEach(input => {
        input.value = taskId;
    });
    
    // Set the form action
    const forms = container.querySelectorAll('form');
    forms.forEach(form => {
        form.action = currentUrl;
    });
}

/**
 * Populates the assignments list in the popover
 * 
 * @param {HTMLElement} container - The popover container
 * @param {Array} assignments - Array of assignment objects
 */
function populateAssignmentsList(container, assignments) {
    const emptyState = container.querySelector('.assignment-empty');
    const itemsContainer = container.querySelector('.assignment-items-container');
    const itemTemplate = container.querySelector('.assignment-item-template');
    
    if (!assignments.length) {
        // Show empty state
        if (emptyState) emptyState.style.display = '';
        if (itemsContainer) itemsContainer.style.display = 'none';
        return;
    }
    
    // Hide empty state, show items container
    if (emptyState) emptyState.style.display = 'none';
    if (itemsContainer) itemsContainer.style.display = '';
    
    // Populate assignments
    if (itemTemplate && itemsContainer) {
        assignments.forEach((assignment, index) => {
            const item = createAssignmentItem(itemTemplate, assignment, index);
            itemsContainer.appendChild(item);
        });
        
        // Remove the template from the DOM
        if (itemTemplate.parentNode) {
            itemTemplate.parentNode.removeChild(itemTemplate);
        }
    }
}

/**
 * Creates a single assignment item from the template
 * 
 * @param {HTMLElement} template - The template element to clone
 * @param {Object} assignment - The assignment data
 * @param {number} index - The index for color calculation
 * @returns {HTMLElement} - The populated assignment item
 */
function createAssignmentItem(template, assignment, index) {
    // Clone the template
    const item = template.cloneNode(true);
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
        const initial = username.charAt(0).toUpperCase();
        avatarElement.textContent = initial;
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
    
    return item;
}

/**
 * Sets up the user selection dropdown
 * 
 * @param {HTMLElement} container - The popover container
 */
function setupUserSelectionDropdown(container) {
    const userSelect = container.querySelector('.user-select');
    if (!userSelect) return;
    
    let options = '<option value="">Select a user</option>';
    const usersArray = Array.isArray(tasked_users) ? tasked_users : Object.values(tasked_users);
    
    usersArray.forEach(user => {
        if (user && user.id && user.username) {
            options += `<option value="${user.id}">${escapeHTML(user.username)}</option>`;
        }
    });
    userSelect.innerHTML = options;
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

</script>
<style>
    /* Additional CSS styles to fix assignment item layout */
    .assignment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        border-bottom: 1px solid #444;
        transition: all 0.2s ease;
    }

    .assignment-main-content {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .assignment-actions {
        display: flex;
        align-items: center;
    }

    /* Fix for delete button */
    .assignment-delete-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-left: 10px;
    }

    /* Make sure the avatar is displayed correctly */
    .assignment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: bold;
        margin-right: 12px;
        flex-shrink: 0;
    }
</style>