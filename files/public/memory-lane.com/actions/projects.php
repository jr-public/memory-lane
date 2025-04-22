<?php
// Project Management action file
// This displays top-level tasks as projects

// Get only top-level tasks (parent_id is NULL)
$tree_res = api_call("Task", "list", [
    "options" => [
        'order' => ['id ASC'],
        'filters' => ['parent_id IS NULL'],
        "with" => ["assignments", "comments"],
        "unique" => true
    ]
]);

if (!$tree_res['success']) {
    echo json_encode($tree_res);
    die();
}

// Get users for assignment display
$users_res = api_call("User", "list", [
    "options" => [
        "unique" => true
    ]
]);

if (!$users_res['success']) {
    echo json_encode($users_res);
    die();
}

// Get task statuses
$statuses_res = api_call("TaskStatus", "list", [
    "options" => [
        "unique" => true
    ]
]);

if (!$statuses_res['success']) {
    echo json_encode($statuses_res);
    die();
}

// Get task priorities
$priorities_res = api_call("TaskPriority", "list", [
    "options" => [
        "unique" => true
    ]
]);

if (!$priorities_res['success']) {
    echo json_encode($priorities_res);
    die();
}

// Convert to variables that will be used in JavaScript
$projects = $tree_res['data'];
$tasked_users = $users_res['data'];
$status_list = $statuses_res['data'];
$priority_list = $priorities_res['data'];

// Add some project-specific styles
?>


<!-- Project Management Container -->
<div class="project-container">
    <!-- Header -->
    <div class="project-header">
        <h1>Project Management</h1>
    </div>
    
    <!-- Project List Container -->
    <div class="project-list-container">
        <!-- Actions -->
        <div class="project-actions">
            <a class="btn-action" href="main.php?action=entity_create&type=task">+ Add New Project</a>
        </div>
        
        <!-- Project List - Will be populated -->
        <div class="project-list" id="project-list"></div>
    </div>
</div>

<?php 
// Include task panel component
require_once('includes/task-panel.php');
?>

<script>
    // Make data available to JavaScript
    const tasked_users = <?= json_encode($tasked_users) ?>;
    const projects_list = <?= json_encode($projects) ?>;
    const status_list = <?= json_encode($status_list) ?>;
    const priority_list = <?= json_encode($priority_list) ?>;
    const currentUrl = <?= json_encode($current_url) ?>;
    
    // For compatibility with task panel
    const task_list = <?= json_encode($projects) ?>;
    
    document.addEventListener('DOMContentLoaded', function() {
        renderProjects(projects_list);
    });
    
    /**
     * Render project list
     * 
     * @param {Array} projects - List of projects to display
     */
    function renderProjects(u_projects) {
		const projects = Object.values(u_projects);
        const projectListContainer = document.getElementById('project-list');
        
        // Clear existing content
        projectListContainer.innerHTML = '';
        
        // Check if there are projects to display
        if (!projects || !projects.length) {
            projectListContainer.innerHTML = `
                <div class="project-empty-state">
                    <p>No projects found. Create your first project using the button above.</p>
                </div>
            `;
            return;
        }
        
        // Render each project
        projects.forEach(project => {
            const projectItem = document.createElement('div');
            projectItem.className = 'project-item';
            projectItem.dataset.projectId = project.id;
            
            // Project info (left side)
            const projectInfo = document.createElement('div');
            projectInfo.className = 'project-info';
            
            // Add project status icon
            const statusIcon = document.createElement('div');
            statusIcon.className = `project-status-icon status-${getStatusClass(project.status_id)}-icon`;
            projectInfo.appendChild(statusIcon);
            
            // Project name
            const projectName = document.createElement('div');
            projectName.className = 'project-name';
            projectName.textContent = project.title;
            projectName.addEventListener('click', () => {
                // Open task panel with this project
                window.taskPanel.open(project);
            });
            projectInfo.appendChild(projectName);
            
            // Project meta (right side)
            const projectMeta = document.createElement('div');
            projectMeta.className = 'project-meta';
            
            // Reuse createAvatarsElement function from tasks.js
            const avatarsElement = createAvatarsElement(project);
            if (avatarsElement) {
                projectMeta.appendChild(avatarsElement);
            }
            
            // Assemble project item
            projectItem.appendChild(projectInfo);
            projectItem.appendChild(projectMeta);
            
            // Add to container
            projectListContainer.appendChild(projectItem);
        });
    }
    
    // We can use the existing createAvatarsElement function from tasks.js
    
    /**
     * Get CSS class for status
     * 
     * @param {number} statusId - The status ID
     * @returns {string} - The status class
     */
    function getStatusClass(statusId) {
        const status = status_list[statusId]?.name || 'pending';
        
        switch (status) {
            case 'done':
            case 'completed':
                return 'completed';
            case 'in_progress':
                return 'in-progress';
            case 'on_hold':
                return 'on-hold';
            case 'todo':
            case 'pending':
            default:
                return 'pending';
        }
    }
    
    // We can use the existing getAvatarColor function from tasks.js
</script>


<style>
    .project-container {
        padding: 20px;
    }

    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .project-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .project-list-container {
        background-color: #2a2a2a;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .project-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #444;
        transition: all 0.2s ease;
    }

    .project-item:hover {
        background-color: #333;
    }

    .project-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 2;
    }

    .project-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #909090;
        font-size: 0.85rem;
        flex: 1;
        justify-content: flex-end;
    }

    .project-name {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
        cursor: pointer;
    }

    .project-status-icon {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-completed-icon {
        background-color: #2ecc71;
    }

    .status-in-progress-icon {
        background-color: #3498db;
    }

    .status-pending-icon {
        background-color: #f39c12;
    }

    .status-on-hold-icon {
        background-color: #7f8c8d;
    }
</style>