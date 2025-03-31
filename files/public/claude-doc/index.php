<?php
    // Task completion tracking with restructured format
    $phases = [
        'phase1' => [
            'name' => 'Project Setup and Architecture Design',
            'title' => 'Phase 1: Project Setup and Architecture Design',
            'tasks' => [
                'create_project_structure' => [
                    'name' => 'Create project structure',
                    'value' => true,
                    'description' => 'Create project structure and directories'
                ],
                'design_database_schemas' => [
                    'name' => 'Design database schemas',
                    'value' => true,
                    'description' => 'Design database schemas'
                ],
                'plan_api_endpoints' => [
                    'name' => 'Plan API endpoints',
                    'value' => true,
                    'description' => 'Plan API endpoints and data formats'
                ],
                'setup_docker_environment' => [
                    'name' => 'Set up Docker environment',
                    'value' => true,
                    'description' => 'Set up Docker environment'
                ]
            ]
        ],
        'phase2' => [
            'name' => 'Authentication Service Implementation',
            'title' => 'Phase 2: Authentication Service Implementation',
            'tasks' => [
                'create_users_tables' => [
                    'name' => 'Create users and roles tables',
                    'value' => true,
                    'description' => 'Create users and roles tables'
                ],
                'implement_user_registration' => [
                    'name' => 'Implement user registration and login',
                    'value' => true,
                    'description' => 'Implement user registration and login'
                ],
                'build_jwt_token' => [
                    'name' => 'Build JWT token generation',
                    'value' => true,
                    'description' => 'Build JWT token generation and validation - JWT must be tied to context. Have the client make a new Auth request before continuing if context is not right.'
                ],
                'create_permission_system' => [
                    'name' => 'Create role-based permission system (SKIPPED)',
                    'value' => true,
                    'description' => 'Create role-based permission system (SKIPPED)'
                ],
                'implement_api_endpoints' => [
                    'name' => 'Implement API endpoints',
                    'value' => true,
                    'description' => 'Implement API endpoints'
                ]
            ]
        ],
        'phase3' => [
            'name' => 'Task Service Implementation',
            'title' => 'Phase 3: Task Service Implementation',
            'tasks' => [
                'create_tasks_tables' => [
                    'name' => 'Create tasks tables',
                    'value' => true,
                    'description' => 'Create tasks and related tables'
                ],
                'implement_task_crud' => [
                    'name' => 'Implement task CRUD operations',
                    'value' => true,
                    'description' => 'Implement task CRUD operations'
                ],
                'build_assignment_management' => [
                    'name' => 'Build assignment and status management',
                    'value' => false,
                    'description' => 'Build assignment and status management'
                ],
                'implement_filtering' => [
                    'name' => 'Implement filtering and sorting',
                    'value' => false,
                    'description' => 'Implement filtering and sorting'
                ],
                'create_api_endpoints' => [
                    'name' => 'Create API endpoints',
                    'value' => true,
                    'description' => 'Create API endpoints'
                ]
            ]
        ],
        'phase4' => [
            'name' => 'API Gateway and Integration',
            'title' => 'Phase 4: API Gateway and Integration',
            'tasks' => [
                'build_api_gateway' => [
                    'name' => 'Build API gateway service',
                    'value' => true,
                    'description' => 'Build API gateway service'
                ],
                'implement_request_routing' => [
                    'name' => 'Implement request routing',
                    'value' => true,
                    'description' => 'Implement request routing'
                ],
                'setup_auth_middleware' => [
                    'name' => 'Set up authentication middleware',
                    'value' => true,
                    'description' => 'Set up authentication middleware'
                ],
                'configure_service_communication' => [
                    'name' => 'Configure inter-service communication',
                    'value' => true,
                    'description' => 'Configure inter-service communication'
                ]
            ]
        ],
        'phase5' => [
            'name' => 'Testing and Refinement',
            'title' => 'Phase 5: Testing and Refinement',
            'tasks' => [
                'write_unit_tests' => [
                    'name' => 'Write unit tests',
                    'value' => false,
                    'description' => 'Write unit tests for core logic'
                ],
                'create_integration_tests' => [
                    'name' => 'Create integration tests',
                    'value' => false,
                    'description' => 'Create integration tests for APIs'
                ],
                'perform_security_review' => [
                    'name' => 'Perform security review',
                    'value' => false,
                    'description' => 'Perform security review'
                ],
                'optimize_database_queries' => [
                    'name' => 'Optimize database queries',
                    'value' => false,
                    'description' => 'Optimize database queries'
                ],
                'implement_caching' => [
                    'name' => 'Implement caching strategies',
                    'value' => false,
                    'description' => 'Implement caching strategies'
                ]
            ]
        ],
        'phase6' => [
            'name' => 'Documentation and Presentation',
            'title' => 'Phase 6: Documentation and Presentation',
            'tasks' => [
                'create_architecture_diagrams' => [
                    'name' => 'Create architecture diagrams',
                    'value' => false,
                    'description' => 'Create system architecture diagrams'
                ],
                'document_api_endpoints' => [
                    'name' => 'Document API endpoints',
                    'value' => false,
                    'description' => 'Document API endpoints'
                ],
                'write_database_documentation' => [
                    'name' => 'Write database schema documentation',
                    'value' => false,
                    'description' => 'Write database schema documentation'
                ],
                'create_setup_instructions' => [
                    'name' => 'Create setup instructions',
                    'value' => false,
                    'description' => 'Create setup and deployment instructions'
                ],
                'prepare_portfolio_presentation' => [
                    'name' => 'Prepare portfolio presentation',
                    'value' => false,
                    'description' => 'Prepare portfolio presentation'
                ]
            ]
        ],
        'phase-clients' => [
            'name' => 'Overlooked implementations - CLIENTS',
            'title' => 'Phase CLIENTS: Things i missed on my first design #1',
            'tasks' => [
                'clients_class' => [
                    'name' => 'Clients',
                    'value' => true,
                    'description' => 'Create model for clients, update user table'
                ],
                'clients_crud' => [
                    'name' => 'Clients CRUD',
                    'value' => true,
                    'description' => 'Class for managing clients'
                ]
            ]
        ],
        'phase-websites' => [
            'name' => 'Overlooked implementations - Front ends',
            'title' => 'Websites / Tools',
            'tasks' => [
                'porftolio-index' => [
                    'name' => 'Portfolio website',
                    'value' => true,
                    'description' => 'A simple tool or website that gives me some cheat functionality for logging in to or managing memory lane'
                ],
                'admin' => [
                    'name' => 'Administrative website',
                    'value' => true,
                    'description' => 'Administrative website, mostly for CRUD interaction'
                ]
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
        :root {
            --dark-bg: #121212;
            --dark-surface: #1e1e1e;
            --dark-surface-light: #2d2d2d;
            --dark-primary: #bb86fc;
            --dark-secondary: #03dac6;
            --dark-error: #cf6679;
            --dark-text-primary: rgba(255, 255, 255, 0.87);
            --dark-text-secondary: rgba(255, 255, 255, 0.6);
            --dark-border: #333;
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--dark-text-primary);
        }
        
        .card {
            background-color: var(--dark-surface);
            border-color: var(--dark-border);
        }
        
        .card-header {
            background-color: var(--dark-surface-light) !important;
            border-color: var(--dark-border);
        }
        
        .phase-card {
            margin-bottom: 20px;
            border-left: 4px solid var(--dark-primary);
        }
        
        .resource-card {
            border-left: 4px solid var(--dark-secondary);
        }
        
        .task-item {
            padding: 8px 0;
            border-bottom: 1px solid var(--dark-border);
        }
        
        .task-item:last-child {
            border-bottom: none;
        }
        
        .sidebar {
            background-color: var(--dark-surface);
            height: 100%;
            padding: 20px;
        }
        
        .progress {
            height: 25px;
            background-color: var(--dark-surface-light);
        }
        
        .progress-bar {
            background-color: var(--dark-primary);
        }
        
        .checkmark {
            color: var(--dark-secondary);
            margin-right: 8px;
        }
        
        .pending {
            color: var(--dark-text-secondary);
            margin-right: 8px;
        }
        
        .navbar {
            background-color: #343a40 !important;
        }
        
        .accordion-button {
            background-color: var(--dark-surface);
            color: var(--dark-text-primary);
        }
        
        .accordion-button:not(.collapsed) {
            background-color: var(--dark-surface-light);
            color: var(--dark-text-primary);
        }
        
        .accordion-button::after {
            filter: invert(1);
        }
        
        footer {
            background-color: var(--dark-surface) !important;
            color: var(--dark-text-primary);
        }
        
        a {
            color: var(--dark-primary);
        }
        
        a:hover {
            color: var(--dark-secondary);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .nav-link.active, .nav-link:hover {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .bg-light {
            background-color: var(--dark-surface-light) !important;
        }
        
        .text-muted {
            color: var(--dark-text-secondary) !important;
        }
        
        code {
            background-color: #2d2d2d;
            color: #e83e8c;
            padding: 2px 4px;
            border-radius: 3px;
        }
        
        /* Go to top button styles */
        #goToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            border: none;
            outline: none;
            background-color: var(--dark-primary);
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        #goToTopBtn:hover {
            background-color: var(--dark-secondary);
            transform: translateY(-3px);
        }
        
        #goToTopBtn i {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                    
                    <?php foreach ($phases as $phaseId => $phase): ?>
                    <div class="card phase-card" id="<?php echo $phaseId; ?>">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><?php echo $phase['title']; ?></h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($phase['tasks'] as $taskId => $task): ?>
                            <div class="task-item">
                                <span class="pending"><i class="bi bi-circle"></i></span>
                                <?php echo $task['description']; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
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

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p>Task Management System - PHP Architecture Portfolio Project</p>
        </div>
    </footer>

    <!-- Go to top button -->
    <button id="goToTopBtn" title="Go to top"><i class="bi bi-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Go to top button functionality -->
    <script>
    // Modular "Go to Top" button functionality
    const goToTopModule = (function() {
        // Private variables
        const btn = document.getElementById("goToTopBtn");
        const scrollThreshold = 300; // Show button after scrolling this many pixels
        
        // Private methods
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
        
        function toggleButtonVisibility() {
            if (document.body.scrollTop > scrollThreshold || document.documentElement.scrollTop > scrollThreshold) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }
        
        // Public methods (API)
        return {
            initialize: function() {
                // Add event listeners
                window.addEventListener("scroll", toggleButtonVisibility);
                btn.addEventListener("click", scrollToTop);
                
                // Initial check
                toggleButtonVisibility();
            },
            updateThreshold: function(newThreshold) {
                // Allow threshold to be updated if needed
                if (typeof newThreshold === 'number') {
                    scrollThreshold = newThreshold;
                }
            },
            updateAppearance: function(options) {
                // Allow styling to be modified
                if (options.backgroundColor) btn.style.backgroundColor = options.backgroundColor;
                if (options.color) btn.style.color = options.color;
                if (options.size) {
                    btn.style.width = options.size + 'px';
                    btn.style.height = options.size + 'px';
                }
                if (options.bottom) btn.style.bottom = options.bottom + 'px';
                if (options.right) btn.style.right = options.right + 'px';
            }
        };
    })();
    
    // Initialize the module when the page is loaded
    document.addEventListener('DOMContentLoaded', function() {
        goToTopModule.initialize();
        
        // Example of how to use the module to modify the button
        // Uncomment to customize
        /*
        goToTopModule.updateAppearance({
            backgroundColor: '#9c27b0',
            size: 60,
            bottom: 30,
            right: 30
        });
        */
    });
    </script>
    
    <?php
    // Calculate progress with the restructured task array
    function calculateProgress($phases) {
        $totalTasks = 0;
        $completedTasks = 0;
        
        foreach ($phases as $phase) {
            foreach ($phase['tasks'] as $taskId => $task) {
                $totalTasks++;
                if ($task['value']) {
                    $completedTasks++;
                }
            }
        }
        
        return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    }

    // Update the progress display
    $progress = calculateProgress($phases);
    
    // Update task status in the UI based on restructured PHP array
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
                    
                    for (const taskId in tasks) {
                        if (taskIndex < taskItems.length) {
                            const statusIcon = taskItems[taskIndex].querySelector('span');
                            
                            if (tasks[taskId].value) {
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