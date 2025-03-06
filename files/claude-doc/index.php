<?php


    // Task completion tracking (Edit these arrays to update progress)
    $phases = [
        'phase1' => [
            'name' => 'Project Setup and Architecture Design',
            'tasks' => [
                'Create project structure' => true,
                'Design database schemas' => false,
                'Plan API endpoints' => false,
                'Set up Docker environment' => true,
            ]
        ],
        'phase2' => [
            'name' => 'Authentication Service Implementation',
            'tasks' => [
                'Create users and roles tables' => false,
                'Implement user registration and login' => false,
                'Build JWT token generation' => false,
                'Create role-based permission system' => false,
                'Implement API endpoints' => false
            ]
        ],
        'phase3' => [
            'name' => 'Task Service Implementation',
            'tasks' => [
                'Create tasks tables' => false,
                'Implement task CRUD operations' => false,
                'Build assignment and status management' => false,
                'Implement filtering and sorting' => false,
                'Create API endpoints' => false
            ]
        ],
        'phase4' => [
            'name' => 'API Gateway and Integration',
            'tasks' => [
                'Build API gateway service' => false,
                'Implement request routing' => false,
                'Set up authentication middleware' => false,
                'Configure inter-service communication' => false
            ]
        ],
        'phase5' => [
            'name' => 'Testing and Refinement',
            'tasks' => [
                'Write unit tests' => false,
                'Create integration tests' => false,
                'Perform security review' => false,
                'Optimize database queries' => false,
                'Implement caching strategies' => false
            ]
        ],
        'phase6' => [
            'name' => 'Documentation and Presentation',
            'tasks' => [
                'Create architecture diagrams' => false,
                'Document API endpoints' => false,
                'Write database schema documentation' => false,
                'Create setup instructions' => false,
                'Prepare portfolio presentation' => false
            ]
        ]
    ];

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Architect Portfolio - Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .phase-card {
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .resource-card {
            border-left: 4px solid #198754;
        }
        .task-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .task-item:last-child {
            border-bottom: none;
        }
        .sidebar {
            background-color: #f8f9fa;
            height: 100%;
            padding: 20px;
        }
        .progress {
            height: 25px;
        }
        .checkmark {
            color: #198754;
            margin-right: 8px;
        }
        .pending {
            color: #6c757d;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">PHP Architect Portfolio Project</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#overview">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#roadmap">Roadmap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#resources">Resources</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#architecture">Architecture</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 sidebar d-none d-lg-block">
                <h5>Project Progress</h5>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                
                <h5 class="mt-4">Project Overview</h5>
                <ul class="list-unstyled">
					<li><a href="prerequisites.php">Project Prerequisites</a></li>
                    <li><a href="#overview">Project Description</a></li>
                    <li><a href="#features">Key Features</a></li>
                    <li><a href="#architecture">System Architecture</a></li>
                    <li><a href="#roadmap">Development Roadmap</a></li>
                    <li><a href="#resources">Resources & Links</a></li>
                </ul>
                
                <h5 class="mt-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#auth-service">Authentication Service</a></li>
                    <li><a href="#task-service">Task Service</a></li>
                    <li><a href="#api-gateway">API Gateway</a></li>
                    <li><a href="docs.php">Documentation</a></li>
                </ul>
                
                <div class="mt-4 p-3 bg-light rounded">
                    <h6>Update Project Status</h6>
                    <p class="small text-muted">Edit the PHP arrays in this file to update your project's status and progress.</p>
                </div>
            </div>
            
            <div class="col-lg-9 p-4">
                <section id="overview" class="mb-5">
                    <h2>Task Management System</h2>
                    <p class="lead">A microservice-based task management system built with pure PHP to showcase software architecture skills.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-layers"></i> Microservice Architecture</h5>
                                    <p class="card-text">Built with separate, independent services communicating via APIs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-code-slash"></i> Framework-Free</h5>
                                    <p class="card-text">Demonstrates pure PHP skills and architecture knowledge without frameworks.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-database"></i> PostgreSQL</h5>
                                    <p class="card-text">Leverages advanced PostgreSQL features with optimized schemas.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section id="features" class="mb-5">
                    <h3>System Features</h3>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5>User Management</h5>
                            <ul>
                                <li>User registration and authentication</li>
                                <li>JWT-based secure authentication</li>
                                <li>Role-based access control</li>
                                <li>User profiles and preferences</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Task Management</h5>
                            <ul>
                                <li>Task creation, editing, and deletion</li>
                                <li>Task assignment and reassignment</li>
                                <li>Status tracking and updates</li>
                                <li>Priority and deadline management</li>
                                <li>Filtering and sorting capabilities</li>
                            </ul>
                        </div>
                    </div>
                </section>
                
                <section id="architecture" class="mb-5">
                    <h3>System Architecture</h3>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Microservices Architecture</h5>
                            <p>The system is composed of the following independent services:</p>
                            <ul>
                                <li><strong>Authentication Service:</strong> Handles user registration, login, and token management</li>
                                <li><strong>Task Service:</strong> Manages tasks, assignments, and status updates</li>
                                <li><strong>API Gateway:</strong> Routes requests to appropriate services and handles authentication verification</li>
                            </ul>
                            <div class="text-center mt-3">
                                <img src="https://via.placeholder.com/800x400" alt="Architecture Diagram Placeholder" class="img-fluid border">
                                <p class="text-muted mt-2"><small>Replace with your actual architecture diagram</small></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header">Authentication Service</div>
                                <div class="card-body">
                                    <h6>Components:</h6>
                                    <ul>
                                        <li>User Controller</li>
                                        <li>Authentication Service</li>
                                        <li>User Repository</li>
                                        <li>JWT Token Generator</li>
                                    </ul>
                                    <h6>Database Tables:</h6>
                                    <ul>
                                        <li>users</li>
                                        <li>roles</li>
                                        <li>user_roles</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header">Task Service</div>
                                <div class="card-body">
                                    <h6>Components:</h6>
                                    <ul>
                                        <li>Task Controller</li>
                                        <li>Task Service</li>
                                        <li>Task Repository</li>
                                        <li>Assignment Manager</li>
                                    </ul>
                                    <h6>Database Tables:</h6>
                                    <ul>
                                        <li>tasks</li>
                                        <li>task_assignments</li>
                                        <li>task_statuses</li>
                                        <li>task_comments</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section id="roadmap" class="mb-5">
                    <h3>Development Roadmap</h3>
                    
                    <div class="card phase-card" id="phase1">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phase 1: Project Setup and Architecture Design</h5>
                        </div>
                        <div class="card-body">
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create project structure and directories
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Design database schemas
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Plan API endpoints and data formats
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Set up Docker environment
                            </div>
                        </div>
                    </div>
                    
                    <div class="card phase-card" id="phase2">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phase 2: Authentication Service Implementation</h5>
                        </div>
                        <div class="card-body">
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create users and roles tables
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Implement user registration and login
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Build JWT token generation and validation
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create role-based permission system
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Implement API endpoints
                            </div>
                        </div>
                    </div>
                    
                    <div class="card phase-card" id="phase3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phase 3: Task Service Implementation</h5>
                        </div>
                        <div class="card-body">
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create tasks and related tables
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Implement task CRUD operations
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Build assignment and status management
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Implement filtering and sorting
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create API endpoints
                            </div>
                        </div>
                    </div>
                    
                    <div class="card phase-card" id="phase4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phase 4: API Gateway and Integration</h5>
                        </div>
                        <div class="card-body">
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Build API gateway service
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Implement request routing
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Set up authentication middleware
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Configure inter-service communication
                            </div>
                        </div>
                    </div>
                    
                    <div class="card phase-card" id="phase5">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phase 5: Testing and Refinement</h5>
                        </div>
                        <div class="card-body">
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Write unit tests for core logic
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create integration tests for APIs
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Perform security review
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Optimize database queries
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Implement caching strategies
                            </div>
                        </div>
                    </div>
                    
                    <div class="card phase-card" id="phase6">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phase 6: Documentation and Presentation</h5>
                        </div>
                        <div class="card-body">
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create system architecture diagrams
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Document API endpoints
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Write database schema documentation
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Create setup and deployment instructions
                            </div>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                Prepare portfolio presentation
                            </div>
                        </div>
                    </div>
                </section>
                
                <section id="resources" class="mb-5">
                    <h3>Resources and Links</h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card resource-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">PHP Best Practices</h5>
                                    <ul>
                                        <li><a href="https://phptherightway.com/" target="_blank">PHP The Right Way</a></li>
                                        <li><a href="https://www.php-fig.org/psr/" target="_blank">PHP-FIG PSR Standards</a></li>
                                        <li><a href="https://github.com/jupeter/clean-code-php" target="_blank">Clean Code PHP</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card resource-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Architecture References</h5>
                                    <ul>
                                        <li><a href="https://martinfowler.com/articles/microservices.html" target="_blank">Microservices by Martin Fowler</a></li>
                                        <li><a href="https://12factor.net/" target="_blank">The Twelve-Factor App</a></li>
                                        <li><a href="https://refactoring.guru/design-patterns" target="_blank">Design Patterns</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card resource-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">PostgreSQL</h5>
                                    <ul>
                                        <li><a href="https://www.postgresql.org/docs/current/index.html" target="_blank">PostgreSQL Documentation</a></li>
                                        <li><a href="https://www.postgresql.org/docs/current/sql-commands.html" target="_blank">SQL Commands</a></li>
                                        <li><a href="https://www.postgresql.org/docs/current/performance-tips.html" target="_blank">Performance Tips</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card resource-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Development Tools</h5>
                                    <ul>
                                        <li><a href="https://www.docker.com/get-started" target="_blank">Docker Getting Started</a></li>
                                        <li><a href="https://getcomposer.org/doc/" target="_blank">Composer Documentation</a></li>
                                        <li><a href="https://phpunit.de/documentation.html" target="_blank">PHPUnit Documentation</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="documentation" class="mb-5">
                    <h3>Technical Documentation</h3>
                    
                    <div class="accordion" id="technicalDocs">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAuth">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAuth">
                                    Authentication Service API
                                </button>
                            </h2>
                            <div id="collapseAuth" class="accordion-collapse collapse" data-bs-parent="#technicalDocs">
                                <div class="accordion-body">
                                    <h5>Endpoints:</h5>
                                    <ul>
                                        <li><code>POST /register</code> - Register new user</li>
                                        <li><code>POST /login</code> - Authenticate user</li>
                                        <li><code>GET /validate-token</code> - Validate JWT token</li>
                                        <li><code>GET /user/profile</code> - Get user profile information</li>
                                    </ul>
                                    <p>Add more detailed documentation as you develop the API.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTask">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTask">
                                    Task Service API
                                </button>
                            </h2>
                            <div id="collapseTask" class="accordion-collapse collapse" data-bs-parent="#technicalDocs">
                                <div class="accordion-body">
                                    <h5>Endpoints:</h5>
                                    <ul>
                                        <li><code>GET /tasks</code> - List all tasks</li>
                                        <li><code>POST /tasks</code> - Create new task</li>
                                        <li><code>GET /tasks/{id}</code> - Get task details</li>
                                        <li><code>PUT /tasks/{id}</code> - Update task</li>
                                        <li><code>DELETE /tasks/{id}</code> - Delete task</li>
                                        <li><code>GET /users/{id}/tasks</code> - Get tasks for specific user</li>
                                    </ul>
                                    <p>Add more detailed documentation as you develop the API.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingDB">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDB">
                                    Database Schemas
                                </button>
                            </h2>
                            <div id="collapseDB" class="accordion-collapse collapse" data-bs-parent="#technicalDocs">
                                <div class="accordion-body">
                                    <p>Document your database schemas here as you design them.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p>Task Management System - PHP Architecture Portfolio Project</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php

    // Calculate progress (Edit the $phases array above to mark tasks as true when completed)
    function calculateProgress($phases) {
        $totalTasks = 0;
        $completedTasks = 0;
        
        foreach ($phases as $phase) {
            foreach ($phase['tasks'] as $task => $completed) {
                $totalTasks++;
                if ($completed) {
                    $completedTasks++;
                }
            }
        }
        
        return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    }

    // Update the progress display
    $progress = calculateProgress($phases);
    
    // Update task status in the UI based on PHP array
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update progress bar
            const progressBar = document.querySelector('.progress-bar');
            progressBar.style.width = '{$progress}%';
            progressBar.textContent = '{$progress}%';
            progressBar.setAttribute('aria-valuenow', '{$progress}');
            
            // Loop through phases and update task status icons
            const phases = " . json_encode($phases) . ";
            
            for (const phaseId in phases) {
                const phase = phases[phaseId];
                const tasks = phase.tasks;
                
                let taskIndex = 0;
                const phaseElement = document.getElementById(phaseId);
                if (phaseElement) {
                    const taskItems = phaseElement.querySelectorAll('.task-item');
                    
                    for (const taskName in tasks) {
                        if (taskIndex < taskItems.length) {
                            const statusIcon = taskItems[taskIndex].querySelector('span');
                            
                            if (tasks[taskName]) {
                                // Task completed
                                statusIcon.className = 'checkmark';
                                statusIcon.innerHTML = '<i class=\"bi bi-check-circle-fill\"></i>';
                            } else {
                                // Task pending
                                statusIcon.className = 'pending';
                                statusIcon.innerHTML = '<i class=\"bi bi-circle\"></i>';
                            }
                            
                            taskIndex++;
                        }
                    }
                }
            }
        });
    </script>";
    ?>
</body>
</html>