<?php
$ass_res = api_call("User", "list");
if (!$ass_res['success']) {
    echo json_encode($ass_res);
    die();
}

$ass_users = $ass_res['data'];
?>

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
    
    // Initialize modal functionality
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Add event delegation for assignment containers
        document.addEventListener('click', function(event) {
            // Find the closest assignment container if clicked on or inside one
            const container = event.target.closest('[data-action="show-assignments"]');
            if (container) {
                const taskId = container.dataset.taskId;
                const taskTitle = container.dataset.taskTitle;
                const assignments = container.dataset.assignments;
                
                // Show assignments in the modal
                showAssignmentDetails(assignments, taskTitle, taskId);
            }
        });
    });
    
    // Escape string for safe use in HTML attributes
    function escapeString(str) {
        return str
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"');
    }
    
    // Note: We're using createAvatarElement from tasks.js instead of defining our own
    // The getAvatarColor function is also used from tasks.js
    
    // Show assignment details in the modal
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
                    const role = assignment.role_id ? getRoleName(assignment.role_id) : 'Contributor';
                    
                    // Create the main assignment content
                    const mainContent = document.createElement('div');
                    mainContent.className = 'assignment-main-content';
                    
                    // Use the createAvatarElement function from tasks.js
                    const avatarElement = createAvatarElement(assignment, index);
                    // Adjust any styling needed for the modal context
                    avatarElement.style.marginRight = '10px';
                    avatarElement.classList.add('assignment-avatar');
                    
                    mainContent.appendChild(avatarElement);
                    
                    const detailsDiv = document.createElement('div');
                    detailsDiv.className = 'assignment-details';
                    detailsDiv.innerHTML = `
                        <div class="assignment-name">${username}</div>
                        <div class="assignment-role">${role}</div>
                    `;
                    
                    mainContent.appendChild(detailsDiv);
                    
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
                        ${generateUserOptions(<?= json_encode($ass_users); ?>)}
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