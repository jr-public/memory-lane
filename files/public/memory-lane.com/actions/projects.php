<?php
// Project Management action file
// This would be placed in the actions directory and included via main.php or index.php

// Sample project data - in a real implementation, this would come from a database
$projects = [
    [
        'id' => 1,
        'name' => 'Website Redesign',
        'client_id' => 2,
        'client_name' => 'Smith Global Industries',
        'start_date' => '2025-01-15',
        'due_date' => '2025-04-30',
        'status' => 'in_progress',
        'completion' => 65,
        'priority' => 'high',
        'manager' => 'Jane Smith',
        'description' => 'Complete redesign of the corporate website with new branding guidelines and improved UX.',
        'tasks' => [
            ['id' => 101, 'name' => 'Design mockups approval', 'status' => 'completed', 'assigned_to' => 'Design Team', 'due_date' => '2025-02-01'],
            ['id' => 102, 'name' => 'Frontend implementation', 'status' => 'in_progress', 'assigned_to' => 'Development Team', 'due_date' => '2025-03-15'],
            ['id' => 103, 'name' => 'Backend integration', 'status' => 'in_progress', 'assigned_to' => 'Development Team', 'due_date' => '2025-04-01'],
            ['id' => 104, 'name' => 'Content migration', 'status' => 'pending', 'assigned_to' => 'Content Team', 'due_date' => '2025-04-15'],
            ['id' => 105, 'name' => 'Testing and QA', 'status' => 'pending', 'assigned_to' => 'QA Team', 'due_date' => '2025-04-25']
        ]
    ],
    [
        'id' => 2,
        'name' => 'Mobile App Development',
        'client_id' => 3,
        'client_name' => 'Tech Innovate Solutions',
        'start_date' => '2025-02-01',
        'due_date' => '2025-06-30',
        'status' => 'in_progress',
        'completion' => 30,
        'priority' => 'high',
        'manager' => 'John Doe',
        'description' => 'Development of a cross-platform mobile application for inventory management with offline capabilities.',
        'tasks' => [
            ['id' => 201, 'name' => 'Requirements gathering', 'status' => 'completed', 'assigned_to' => 'Project Manager', 'due_date' => '2025-02-15'],
            ['id' => 202, 'name' => 'App architecture design', 'status' => 'completed', 'assigned_to' => 'Architecture Team', 'due_date' => '2025-03-01'],
            ['id' => 203, 'name' => 'UI/UX design', 'status' => 'in_progress', 'assigned_to' => 'Design Team', 'due_date' => '2025-03-30'],
            ['id' => 204, 'name' => 'Core functionality development', 'status' => 'in_progress', 'assigned_to' => 'Development Team', 'due_date' => '2025-05-15'],
            ['id' => 205, 'name' => 'Offline sync implementation', 'status' => 'pending', 'assigned_to' => 'Development Team', 'due_date' => '2025-06-01'],
            ['id' => 206, 'name' => 'Testing and QA', 'status' => 'pending', 'assigned_to' => 'QA Team', 'due_date' => '2025-06-20']
        ]
    ],
    [
        'id' => 3,
        'name' => 'Sustainability Reporting Tool',
        'client_id' => 4,
        'client_name' => 'Green Energy Collective',
        'start_date' => '2025-01-10',
        'due_date' => '2025-05-15',
        'status' => 'on_hold',
        'completion' => 45,
        'priority' => 'medium',
        'manager' => 'Sarah Johnson',
        'description' => 'Development of a custom dashboard for tracking and reporting environmental impact metrics.',
        'tasks' => [
            ['id' => 301, 'name' => 'Requirements analysis', 'status' => 'completed', 'assigned_to' => 'Business Analyst', 'due_date' => '2025-01-25'],
            ['id' => 302, 'name' => 'Data schema design', 'status' => 'completed', 'assigned_to' => 'Data Architect', 'due_date' => '2025-02-15'],
            ['id' => 303, 'name' => 'Backend API development', 'status' => 'completed', 'assigned_to' => 'Backend Team', 'due_date' => '2025-03-20'],
            ['id' => 304, 'name' => 'Dashboard implementation', 'status' => 'in_progress', 'assigned_to' => 'Frontend Team', 'due_date' => '2025-04-10'],
            ['id' => 305, 'name' => 'Integration with data sources', 'status' => 'on_hold', 'assigned_to' => 'Integration Team', 'due_date' => '2025-04-30'],
            ['id' => 306, 'name' => 'User acceptance testing', 'status' => 'pending', 'assigned_to' => 'Client', 'due_date' => '2025-05-10']
        ]
    ],
    [
        'id' => 4,
        'name' => 'Marketing Campaign Automation',
        'client_id' => 5,
        'client_name' => 'Marketing Professionals Ltd',
        'start_date' => '2025-03-01',
        'due_date' => '2025-05-30',
        'status' => 'planned',
        'completion' => 10,
        'priority' => 'medium',
        'manager' => 'Mike Wilson',
        'description' => 'Implementation of an automated marketing campaign system with analytics and reporting.',
        'tasks' => [
            ['id' => 401, 'name' => 'Campaign strategy definition', 'status' => 'completed', 'assigned_to' => 'Marketing Strategist', 'due_date' => '2025-03-15'],
            ['id' => 402, 'name' => 'System requirements specification', 'status' => 'in_progress', 'assigned_to' => 'Business Analyst', 'due_date' => '2025-03-30'],
            ['id' => 403, 'name' => 'Integration planning', 'status' => 'pending', 'assigned_to' => 'Systems Architect', 'due_date' => '2025-04-15'],
            ['id' => 404, 'name' => 'Automation workflow setup', 'status' => 'pending', 'assigned_to' => 'Development Team', 'due_date' => '2025-05-01'],
            ['id' => 405, 'name' => 'Analytics dashboard setup', 'status' => 'pending', 'assigned_to' => 'Data Team', 'due_date' => '2025-05-15'],
            ['id' => 406, 'name' => 'User training', 'status' => 'pending', 'assigned_to' => 'Training Team', 'due_date' => '2025-05-25']
        ]
    ]
];

// Get the selected project ID from the query parameter
$selected_project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : 1;

// Find the selected project
$selected_project = null;
foreach ($projects as $project) {
    if ($project['id'] == $selected_project_id) {
        $selected_project = $project;
        break;
    }
}

// If no project is found, default to the first one
if ($selected_project === null && !empty($projects)) {
    $selected_project = $projects[0];
}

// Get counts for status overview
$task_counts = [
    'completed' => 0,
    'in_progress' => 0,
    'pending' => 0,
    'on_hold' => 0
];

if ($selected_project) {
    foreach ($selected_project['tasks'] as $task) {
        if (isset($task_counts[$task['status']])) {
            $task_counts[$task['status']]++;
        }
    }
}

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'completed':
            return 'status-active';
        case 'in_progress':
            return 'status-in-progress';
        case 'pending':
            return 'status-pending';
        case 'on_hold':
            return 'status-inactive';
        default:
            return 'status-pending';
    }
}

// Custom function to style priority
function getPriorityBadgeClass($priority) {
    switch ($priority) {
        case 'high':
            return 'priority-high';
        case 'medium':
            return 'priority-medium';
        case 'low':
            return 'priority-low';
        default:
            return 'priority-medium';
    }
}

// Add custom CSS for new elements
?>
<style>
    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .project-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .project-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .project-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .status-in-progress {
        background-color: #3498db;
        color: #121212;
    }

    .status-pending {
        background-color: #f39c12;
        color: #121212;
    }

    .priority-high {
        background-color: #e74c3c;
        color: white;
    }

    .priority-medium {
        background-color: #f39c12;
        color: #121212;
    }

    .priority-low {
        background-color: #3498db;
        color: #121212;
    }

    .project-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .project-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .project-card {
        background-color: #2a2a2a;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .progress-container {
        margin-top: 0.5rem;
    }

    .task-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .task-filters {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        background-color: #333;
        border: none;
        color: #e0e0e0;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .filter-btn.active {
        background-color: #3498db;
        color: #121212;
    }

    .task-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #444;
        transition: all 0.2s ease;
    }

    .task-item:hover {
        background-color: #333;
    }

    .task-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .task-status-icon {
        width: 16px;
        height: 16px;
        border-radius: 50%;
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

    .task-name {
        font-weight: 500;
    }

    .task-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #909090;
        font-size: 0.85rem;
    }

    .task-assignee, .task-due-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .project-description {
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .project-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
    }

    .meta-label {
        font-size: 0.8rem;
        color: #909090;
        margin-bottom: 0.25rem;
    }

    .meta-value {
        font-weight: 500;
    }

    .timeline-container {
        margin-top: 1rem;
    }

    .timeline-markers {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }

    .timeline-bar {
        height: 8px;
        background-color: #444;
        border-radius: 4px;
        position: relative;
    }

    .timeline-progress {
        position: absolute;
        height: 100%;
        background-color: #3498db;
        border-radius: 4px;
        top: 0;
        left: 0;
    }

    .timeline-today {
        position: absolute;
        height: 16px;
        width: 4px;
        background-color: #e74c3c;
        top: -4px;
        border-radius: 2px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .project-overview, .project-details {
            grid-template-columns: 1fr;
        }
        
        .task-meta {
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }
    }
</style>

<div class="project-container">
    <!-- Project Header with Selector -->
    <div class="project-header">
        <div class="project-title">
            <h1><?php echo htmlspecialchars($selected_project['name']); ?></h1>
            <span class="project-badge <?php echo getStatusBadgeClass($selected_project['status']); ?>">
                <?php echo ucfirst(str_replace('_', ' ', $selected_project['status'])); ?>
            </span>
            <span class="project-badge <?php echo getPriorityBadgeClass($selected_project['priority']); ?>">
                <?php echo ucfirst($selected_project['priority']); ?> Priority
            </span>
        </div>
        
        <div class="project-selector">
            <label for="project-select">Project:</label>
            <select id="project-select" onchange="window.location.href='?action=projects&project_id='+this.value">
                <?php foreach ($projects as $project): ?>
                <option value="<?php echo $project['id']; ?>" <?php echo ($project['id'] == $selected_project_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($project['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <!-- Project Overview Stats -->
    <div class="project-overview">
        <div class="project-card">
            <div class="stat-title">Overall Progress</div>
            <div class="stat-value"><?php echo $selected_project['completion']; ?>%</div>
            <div class="progress-container">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $selected_project['completion']; ?>%" aria-valuenow="<?php echo $selected_project['completion']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        
        <div class="project-card">
            <div class="stat-title">Task Status</div>
            <div class="stat-value"><?php echo count($selected_project['tasks']); ?> Tasks</div>
            <div class="task-stats">
                <div><span class="checkmark">✓</span> <?php echo $task_counts['completed']; ?> Completed</div>
                <div><span style="color: #3498db;">●</span> <?php echo $task_counts['in_progress']; ?> In Progress</div>
                <div><span style="color: #f39c12;">●</span> <?php echo $task_counts['pending']; ?> Pending</div>
                <div><span style="color: #7f8c8d;">●</span> <?php echo $task_counts['on_hold']; ?> On Hold</div>
            </div>
        </div>
        
        <div class="project-card">
            <div class="stat-title">Timeline</div>
            <div class="timeline-container">
                <?php
                // Calculate timeline positions
                $start_date = new DateTime($selected_project['start_date']);
                $due_date = new DateTime($selected_project['due_date']);
                $today = new DateTime();
                
                $total_days = $start_date->diff($due_date)->days;
                $days_passed = $start_date->diff($today)->days;
                
                // Calculate timeline percentage (capped at 0-100%)
                $timeline_percent = min(100, max(0, ($days_passed / $total_days) * 100));
                ?>
                
                <div class="timeline-markers">
                    <div><?php echo $start_date->format('M d, Y'); ?></div>
                    <div><?php echo $due_date->format('M d, Y'); ?></div>
                </div>
                <div class="timeline-bar">
                    <div class="timeline-progress" style="width: <?php echo $selected_project['completion']; ?>%;"></div>
                    <div class="timeline-today" style="left: <?php echo $timeline_percent; ?>%;"></div>
                </div>
                <div class="stat-value" style="margin-top: 0.5rem;">
                    <?php
                    $days_remaining = $today->diff($due_date)->days;
                    $is_overdue = $today > $due_date;
                    
                    if ($is_overdue) {
                        echo '<span style="color: #e74c3c;">' . $days_remaining . ' days overdue</span>';
                    } else {
                        echo $days_remaining . ' days remaining';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="project-card">
            <div class="stat-title">Project Team</div>
            <div class="stat-value"><?php echo htmlspecialchars($selected_project['manager']); ?></div>
            <div style="color: #909090;">Project Manager</div>
            <div style="margin-top: 0.75rem;">
                <div>Client: <?php echo htmlspecialchars($selected_project['client_name']); ?></div>
                <div>Team Members: 8</div>
            </div>
        </div>
    </div>
    
    <!-- Project Details -->
    <div class="project-details">
        <div class="project-card">
            <h3>Project Description</h3>
            <div class="project-description">
                <?php echo htmlspecialchars($selected_project['description']); ?>
            </div>
            
            <div class="project-meta">
                <div class="meta-item">
                    <div class="meta-label">Start Date</div>
                    <div class="meta-value"><?php echo date('M d, Y', strtotime($selected_project['start_date'])); ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Due Date</div>
                    <div class="meta-value"><?php echo date('M d, Y', strtotime($selected_project['due_date'])); ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Client</div>
                    <div class="meta-value"><?php echo htmlspecialchars($selected_project['client_name']); ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Priority</div>
                    <div class="meta-value"><?php echo ucfirst($selected_project['priority']); ?></div>
                </div>
            </div>
        </div>
        
        <div class="project-card">
            <div class="task-list-header">
                <h3>Tasks</h3>
                <div class="task-filters">
                    <button class="filter-btn active" onclick="filterTasks('all')">All</button>
                    <button class="filter-btn" onclick="filterTasks('completed')">Completed</button>
                    <button class="filter-btn" onclick="filterTasks('in_progress')">In Progress</button>
                    <button class="filter-btn" onclick="filterTasks('pending')">Pending</button>
                </div>
            </div>
            
            <div class="task-list">
                <?php foreach ($selected_project['tasks'] as $task): ?>
                <div class="task-item" data-status="<?php echo $task['status']; ?>">
                    <div class="task-info">
                        <div class="task-status-icon status-<?php echo $task['status']; ?>-icon"></div>
                        <div class="task-name"><?php echo htmlspecialchars($task['name']); ?></div>
                    </div>
                    <div class="task-meta">
                        <div class="task-assignee">
                            <span>👤</span>
                            <?php echo htmlspecialchars($task['assigned_to']); ?>
                        </div>
                        <div class="task-due-date">
                            <span>📅</span>
                            <?php echo date('M d', strtotime($task['due_date'])); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Task filtering functionality
    function filterTasks(status) {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Filter tasks
        const taskItems = document.querySelectorAll('.task-item');
        taskItems.forEach(item => {
            if (status === 'all' || item.dataset.status === status) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>