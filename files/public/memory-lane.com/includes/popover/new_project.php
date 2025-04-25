<div class="new-project-popover-content">
    <!-- Popover Header -->
    <div class="new-project-header">
        <h3>Create New Project</h3>
    </div>
    
    <!-- New Project Form -->
    <form id="new-project-form" class="new-project-form" action="" method="post">
        <input type="hidden" name="entity_name" value="Task">
        <input type="hidden" name="entity_action" value="create">
		<input type="hidden" name="user_id" value="<?= get_auth_user('id') ?>">
		<input type="hidden" name="status_id" value="1">
		

        
        <div class="new-project-body">
            <div class="form-group">
                <label for="project-title" class="form-label">Project Name</label>
                <input type="text" id="project-title" name="title" class="form-input" placeholder="Enter project name" required>
            </div>
            
            <div class="form-group">
                <label for="project-description" class="form-label">Description</label>
                <textarea id="project-description" name="description" class="form-textarea" placeholder="Enter project description" rows="3"></textarea>
            </div>
        </div>
        
        <div class="new-project-footer">
            <button type="submit" class="btn-create-project">Create</button>
            <button type="button" class="btn-cancel-project">Cancel</button>
        </div>
    </form>
</div>

<style>
    /* New Project Popover Specific Styles */
    .new-project-popover {
        width: 320px;
        max-width: 95vw;
    }
    
    .new-project-header {
        padding: 12px 15px;
        border-bottom: 1px solid #444;
        background-color: #333;
    }
    
    .new-project-header h3 {
        margin: 0;
        font-size: 14px;
        font-weight: 500;
        color: #3498db;
    }
    
    .new-project-body {
        padding: 15px;
    }
    
    .new-project-form {
        width: 100%;
    }
    
    .form-group {
        margin-bottom: 12px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        color: #e0e0e0;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 8px 10px;
        border-radius: 4px;
        border: 1px solid #444;
        background-color: #333;
        color: #e0e0e0;
        font-size: 14px;
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
    }
    
    .new-project-footer {
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
        border-top: 1px solid #444;
        background-color: #333;
    }
    
    .new-project-footer button {
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        font-size: 12px;
        cursor: pointer;
        font-weight: 500;
    }
    
    .btn-create-project {
        background-color: #3498db;
        color: #121212;
    }
    
    .btn-cancel-project {
        background-color: #5a5a5a;
        color: #e0e0e0;
    }
    
    .btn-create-project:hover {
        background-color: #2980b9;
    }
    
    .btn-cancel-project:hover {
        background-color: #666;
    }
</style>

<script>


document.addEventListener('DOMContentLoaded', function() {
	// href="main.php?action=entity_create&type=task"

	// Get the Add New Project button
	const addProjectBtn = document.querySelector('.project-actions .btn-action');

	if (addProjectBtn) {
		// Replace the default link behavior with our popover
		addProjectBtn.addEventListener('click', function() {
			// Clone template
			let cloned = popoverTemplates.new_project.cloneNode(true);
			cloned.style.display = '';
			
			// Set up form action
			const form = cloned.querySelector('#new-project-form');
			if (form) {
				form.action = currentUrl;
				
				// Add cancel button handler
				const cancelButton = form.querySelector('.btn-cancel-project');
				if (cancelButton) {
					cancelButton.addEventListener('click', function(e) {
						e.preventDefault();
						// Close popover using the controller
						popoverController.close();
					});
				}
				
				// Prevent default form submission and handle manually
				form.addEventListener('submit', function(e) {
					// Validate form
					const projectTitle = form.querySelector('#project-title').value.trim();
					
					if (!projectTitle) {
						e.preventDefault();
						alert('Please enter a project name');
						return false;
					}
					
					// Allow form submission to proceed
					return true;
				});
			}
			
			// Show the popover with the content we created
			const popoverController = showPopover(addProjectBtn, cloned, {
				position: 'bottom',
				className: 'new-project-popover',
				onOpen: (popoverEl) => {
					// Focus on title input when popover opens
					setTimeout(() => {
						const titleInput = popoverEl.querySelector('#project-title');
						if (titleInput) titleInput.focus();
					}, 100);
				}
			});
			
			return popoverController;
		});
	}
});


</script>