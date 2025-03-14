<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System - Technical Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            height: 100%;
            padding: 20px;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .table-schema {
            margin-bottom: 30px;
        }
        details {
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
            background-color: white;
        }
        summary {
            padding: 15px;
            cursor: pointer;
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 1.2rem;
        }
        summary:hover {
            background-color: #e9ecef;
        }
        .details-content {
            padding: 15px;
            border-top: 1px solid #dee2e6;
        }
        .endpoint-card {
            border-left: 4px solid #0d6efd;
            margin-bottom: 15px;
        }
        .endpoint-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
        }
        .endpoint-body {
            padding: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">PHP Architect Portfolio Project</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#overview">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#roadmap">Roadmap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#resources">Resources</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Documentation</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 sidebar d-none d-lg-block">
                <h5>Documentation</h5>
                <ul class="list-unstyled">
                    <li><a href="#auth-api">Authentication Service API</a></li>
                    <li><a href="#task-api">Task Service API</a></li>
                    <li><a href="#gateway-api">API Gateway</a></li>
                    <li><a href="#database-schemas">Database Schemas</a></li>
                    <li><a href="#architecture">Architecture</a></li>
                    <li><a href="#deployment">Deployment</a></li>
                </ul>
                
                <div class="mt-4 p-3 bg-light rounded">
                    <h6>Documentation Status</h6>
                    <p class="small text-muted">This documentation is a work in progress and will be updated throughout development.</p>
                </div>
                
                <div class="mt-4">
                    <a href="index.php" class="btn btn-primary btn-sm">← Back to Dashboard</a>
                </div>
            </div>
            
            <div class="col-lg-9 p-4">
                <h2 class="mb-4">Technical Documentation</h2>
                
                <details id="auth-api" class="card">
                    <summary>Authentication Service API</summary>
                    <div class="details-content">
                        <p>The Authentication Service manages user identity, registration, login, and token validation. All endpoints return JSON responses.</p>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>POST /register</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Register a new user in the system.</p>
                                
                                <h6>Request Body:</h6>
                                <pre>{
  "email": "user@example.com",
  "password": "securepassword",
  "name": "John Doe"
}</pre>

                                <h6>Response (201 Created):</h6>
                                <pre>{
  "status": "success",
  "message": "User registered successfully",
  "user": {
    "id": 123,
    "email": "user@example.com",
    "name": "John Doe",
    "created_at": "2023-09-15T14:30:45Z"
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>400 Bad Request</strong> - Invalid input data</li>
                                    <li><strong>409 Conflict</strong> - Email already exists</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>POST /login</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Authenticate a user and receive a JWT token.</p>
                                
                                <h6>Request Body:</h6>
                                <pre>{
  "email": "user@example.com",
  "password": "securepassword"
}</pre>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expires_at": "2023-09-15T16:30:45Z",
  "user": {
    "id": 123,
    "email": "user@example.com",
    "name": "John Doe"
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>400 Bad Request</strong> - Invalid credentials</li>
                                    <li><strong>401 Unauthorized</strong> - Invalid email or password</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>GET /validate-token</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Validate JWT token and get user information.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "valid": true,
  "user": {
    "id": 123,
    "email": "user@example.com",
    "name": "John Doe",
    "roles": ["user"]
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>GET /user/profile</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Get detailed profile information for the authenticated user.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "user": {
    "id": 123,
    "email": "user@example.com",
    "name": "John Doe",
    "created_at": "2023-09-15T14:30:45Z",
    "last_login": "2023-09-15T14:35:10Z",
    "roles": ["user"],
    "preferences": {
      "notifications": true,
      "theme": "light"
    }
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>404 Not Found</strong> - User not found</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </details>
                
                <details id="task-api" class="card">
                    <summary>Task Service API</summary>
                    <div class="details-content">
                        <p>The Task Service manages task creation, retrieval, update, and deletion. All endpoints return JSON responses and require authentication.</p>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>GET /tasks</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Retrieve a list of tasks with optional filtering.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>
                                
                                <h6>Query Parameters:</h6>
                                <ul>
                                    <li><code>status</code> - Filter by status (e.g., "todo", "in_progress", "done")</li>
                                    <li><code>priority</code> - Filter by priority (e.g., "low", "medium", "high")</li>
                                    <li><code>assigned_to</code> - Filter by user ID assigned to the task</li>
                                    <li><code>page</code> - Page number for pagination (default: 1)</li>
                                    <li><code>limit</code> - Number of tasks per page (default: 20)</li>
                                </ul>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "data": {
    "tasks": [
      {
        "id": 1,
        "title": "Implement user login",
        "description": "Create login form and backend authentication",
        "status": "in_progress",
        "priority": "high",
        "created_at": "2023-09-15T10:30:00Z",
        "due_date": "2023-09-20T17:00:00Z",
        "created_by": 123,
        "assigned_to": 456
      },
      // More tasks...
    ],
    "pagination": {
      "total": 45,
      "page": 1,
      "limit": 20,
      "pages": 3
    }
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>POST /tasks</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Create a new task.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>
                                
                                <h6>Request Body:</h6>
                                <pre>{
  "title": "Implement user dashboard",
  "description": "Create a user dashboard with task overview",
  "status": "todo",
  "priority": "medium",
  "due_date": "2023-09-25T17:00:00Z",
  "assigned_to": 456
}</pre>

                                <h6>Response (201 Created):</h6>
                                <pre>{
  "status": "success",
  "message": "Task created successfully",
  "task": {
    "id": 2,
    "title": "Implement user dashboard",
    "description": "Create a user dashboard with task overview",
    "status": "todo",
    "priority": "medium",
    "created_at": "2023-09-15T11:45:00Z",
    "due_date": "2023-09-25T17:00:00Z",
    "created_by": 123,
    "assigned_to": 456
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>400 Bad Request</strong> - Invalid input data</li>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>GET /tasks/{id}</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Get detailed information about a specific task.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>
                                
                                <h6>Path Parameters:</h6>
                                <ul>
                                    <li><code>id</code> - Task ID</li>
                                </ul>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "task": {
    "id": 1,
    "title": "Implement user login",
    "description": "Create login form and backend authentication",
    "status": "in_progress",
    "priority": "high",
    "created_at": "2023-09-15T10:30:00Z",
    "updated_at": "2023-09-15T14:20:00Z",
    "due_date": "2023-09-20T17:00:00Z",
    "created_by": {
      "id": 123,
      "name": "John Doe"
    },
    "assigned_to": {
      "id": 456,
      "name": "Jane Smith"
    },
    "comments": [
      {
        "id": 1,
        "text": "Working on the login form now",
        "created_at": "2023-09-15T13:30:00Z",
        "created_by": {
          "id": 456,
          "name": "Jane Smith"
        }
      }
    ]
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>404 Not Found</strong> - Task not found</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>PUT /tasks/{id}</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Update an existing task.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>
                                
                                <h6>Path Parameters:</h6>
                                <ul>
                                    <li><code>id</code> - Task ID</li>
                                </ul>
                                
                                <h6>Request Body:</h6>
                                <pre>{
  "title": "Implement user login and registration",
  "description": "Create login form, registration form, and backend authentication",
  "status": "in_progress",
  "priority": "high",
  "due_date": "2023-09-22T17:00:00Z",
  "assigned_to": 456
}</pre>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "message": "Task updated successfully",
  "task": {
    "id": 1,
    "title": "Implement user login and registration",
    "description": "Create login form, registration form, and backend authentication",
    "status": "in_progress",
    "priority": "high",
    "created_at": "2023-09-15T10:30:00Z",
    "updated_at": "2023-09-15T15:00:00Z",
    "due_date": "2023-09-22T17:00:00Z",
    "created_by": 123,
    "assigned_to": 456
  }
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>400 Bad Request</strong> - Invalid input data</li>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>403 Forbidden</strong> - Insufficient permissions</li>
                                    <li><strong>404 Not Found</strong> - Task not found</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="endpoint-card card">
                            <div class="endpoint-header">
                                <code>DELETE /tasks/{id}</code>
                            </div>
                            <div class="endpoint-body">
                                <p>Delete a task.</p>
                                
                                <h6>Headers:</h6>
                                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>
                                
                                <h6>Path Parameters:</h6>
                                <ul>
                                    <li><code>id</code> - Task ID</li>
                                </ul>

                                <h6>Response (200 OK):</h6>
                                <pre>{
  "status": "success",
  "message": "Task deleted successfully"
}</pre>

                                <h6>Error Responses:</h6>
                                <ul>
                                    <li><strong>401 Unauthorized</strong> - Invalid or expired token</li>
                                    <li><strong>403 Forbidden</strong> - Insufficient permissions</li>
                                    <li><strong>404 Not Found</strong> - Task not found</li>
                                    <li><strong>500 Internal Server Error</strong> - Server error</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </details>
                
                <details id="gateway-api" class="card">
                    <summary>API Gateway</summary>
                    <div class="details-content">
                        <p>The API Gateway routes requests to the appropriate microservices and handles authentication verification. All client applications should interact with the system through this gateway.</p>
                        
                        <h5 class="mt-4">Gateway Routes</h5>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Authentication Service Routes:</h6>
                                <pre>
POST /api/auth/register     → Authentication Service: /register
POST /api/auth/login        → Authentication Service: /login
GET  /api/auth/validate     → Authentication Service: /validate-token
GET  /api/auth/profile      → Authentication Service: /user/profile</pre>

                                <h6 class="mt-3">Task Service Routes:</h6>
                                <pre>
GET    /api/tasks           → Task Service: /tasks
POST   /api/tasks           → Task Service: /tasks
GET    /api/tasks/:id       → Task Service: /tasks/:id
PUT    /api/tasks/:id       → Task Service: /tasks/:id
DELETE /api/tasks/:id       → Task Service: /tasks/:id
GET    /api/users/:id/tasks → Task Service: /users/:id/tasks</pre>
                            </div>
                        </div>
                        
                        <h5 class="mt-4">Authentication</h5>
                        <p>The API Gateway enforces authentication for protected routes:</p>
                        <ol>
                            <li>Extracts the JWT token from the Authorization header</li>
                            <li>Verifies the token with the Authentication Service</li>
                            <li>Forwards the request to the appropriate service if authentication is successful</li>
                            <li>Returns appropriate error responses if authentication fails</li>
                        </ol>
                        
                        <h5 class="mt-4">Error Handling</h5>
                        <p>The API Gateway standardizes error responses across all services:</p>
                        <pre>{
  "status": "error",
  "code": 401,
  "message": "Unauthorized: Invalid token"
}</pre>
                    </div>
                </details>
                
                <details id="database-schemas" class="card">
                    <summary>Database Schemas</summary>
                    <div class="details-content">
                        <p>The system uses PostgreSQL databases for each microservice. Here are the database schemas:</p>
                        
                        <h4 class="mt-4">Authentication Service Database</h4>
                        
                        <div class="table-schema">
                            <h5>Table: users</h5>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Type</th>
                                        <th>Constraints</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>SERIAL</td>
                                        <td>PRIMARY KEY</td>
                                        <td>Unique identifier for the user</td>
                                    </tr>
                                    <tr>
                                        <td>email</td>
                                        <td>VARCHAR(255)</td>
                                        <td>UNIQUE, NOT NULL</td>
                                        <td>User's email address</td>
                                    </tr>
                                    <tr>
                                        <td>password</td>
                                        <td>VARCHAR(255)</td>
                                        <td>NOT NULL</td>
                                        <td>Hashed password using bcrypt</td>
                                    </tr>
                                    <tr>
                                        <td>name</td>
                                        <td>VARCHAR(100)</td>
                                        <td>NOT NULL</td>
                                        <td>User's full name</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td>TIMESTAMP</td>
                                        <td>NOT NULL, DEFAULT NOW()</td>
                                        <td>When the user was created</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td>TIMESTAMP</td>
                                        <td>NOT NULL, DEFAULT NOW()</td>
                                        <td>When the user was last updated</td>
                                    </tr>
                                    <tr>
                                        <td>last_login</td>
                                        <td>TIMESTAMP</td>
                                        <td>NULL</td>
                                        <td>When the user last logged in</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-schema">
                            <h5>Table: roles</h5>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Type</th>
                                        <th>Constraints</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>SERIAL</td>
                                        <td>PRIMARY KEY</td>
                                        <td>Unique identifier for the role</td>
                                    </tr>
                                    <tr>
                                        <td>name</td>
                                        <td>VARCHAR(50)</td>
                                        <td>UNIQUE, NOT NULL</td>
                                        <td>Name of the role (e.g., "admin", "user")</td>
                                    </tr>
                                    <tr>
                                        <td>description</td>
                                        <td>TEXT</td>
                                        <td>NULL</td>
                                        <td>Description of the role</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-schema">
                            <h5>Table: user_roles</h5>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Type</th>
                                        <th>Constraints</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>user_id</td>
                                        <td>INTEGER</td>
                                        <td>FOREIGN KEY (users.id), NOT NULL</td>
                                        <td>Reference to the user</td>
                                    </tr>
                                    <tr>
                                        <td>role_id</td>
                                        <td>INTEGER</td>
                                        <td>FOREIGN KEY (roles.id), NOT NULL</td>
                                        <td>Reference to the role</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td>TIMESTAMP</td>
                                        <td>NOT NULL, DEFAULT NOW()</td>
                                        <td>When the assignment was created</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="mt-2"><small>Note: PRIMARY KEY (user_id, role_id)</small></p>
                        </div>
                        
                        <h4 class="mt-5">Task Service Database</h4>
                        
                        <div class="table-schema">
                            <h5>Table: tasks</h5>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Type</th>
                                        <th>Constraints</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>SERIAL</td>
                                        <td>PRIMARY KEY</td>
                                        <td>Unique identifier for the task</td>
                                    </tr>
                                    <tr>
                                        <td>title</td>
                                        <td>VARCHAR(255)</td>
                                        <td>NOT NULL</td>
                                        <td>Task title</td>
                                    </tr>
                                    <tr>
                                        <td>description</td>
                                        <td>TEXT</td>
                                        <td>NULL</td>
                                        <td>Detailed description of the task</td>
                                    </tr>
                                    <tr>
                                        <td>status</td>
                                        <td>VARCHAR(50)</td>
                                        <td>NOT NULL, DEFAULT 'todo'</td>
                                        <td>Current status (e.g., "todo", "in_progress", "done")</td>
                                    </tr>
                                    <tr>
                                        <td>priority</td>
                                        <td>VARCHAR(20)</td>
                                        <td>NOT NULL, DEFAULT 'medium'</td>
                                        <td>Priority level (e.g., "low", "medium", "high")</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td>TIMESTAMP</td>
                                        <td>NOT NULL, DEFAULT NOW()</td>
                                        <td>When the task was last updated</td>
                                    </tr>
                                    <tr>
                                        <td>due_date</td>
                                        <td>TIMESTAMP</td>
                                        <td>NULL</td>
                                        <td>When the task is due</td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td>INTEGER</td>
                                        <td>NOT NULL</td>
                                        <td>ID of the user who created the task</td>
                                    </tr>
                                    <tr>
                                        <td>assigned_to</td>
                                        <td>INTEGER</td>
                                        <td>NULL</td>
                                        <td>ID of the user assigned to the task</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-schema">
                            <h5>Table: task_comments</h5>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Type</th>
                                        <th>Constraints</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>SERIAL</td>
                                        <td>PRIMARY KEY</td>
                                        <td>Unique identifier for the comment</td>
                                    </tr>
                                    <tr>
                                        <td>task_id</td>
                                        <td>INTEGER</td>
                                        <td>FOREIGN KEY (tasks.id), NOT NULL</td>
                                        <td>Reference to the task</td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td>INTEGER</td>
                                        <td>NOT NULL</td>
                                        <td>ID of the user who created the comment</td>
                                    </tr>
                                    <tr>
                                        <td>text</td>
                                        <td>TEXT</td>
                                        <td>NOT NULL</td>
                                        <td>Comment text</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td>TIMESTAMP</td>
                                        <td>NOT NULL, DEFAULT NOW()</td>
                                        <td>When the comment was created</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </details>
                
                <details id="architecture" class="card">
                    <summary>Architecture</summary>
                    <div class="details-content">
                        <p>The Task Management System is built using a microservices architecture with the following key components:</p>
                        
                        <div class="text-center my-4">
                            <img src="https://via.placeholder.com/800x400" alt="Architecture Diagram Placeholder" class="img-fluid border">
                            <p class="text-muted mt-2"><small>Replace with your actual architecture diagram</small></p>
                        </div>
                        
                        <h5>Service Components</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">API Gateway</div>
                                    <div class="card-body">
                                        <p>Routes requests to appropriate services and handles authentication verification.</p>
                                        <p><strong>Technologies:</strong></p>
                                        <ul>
                                            <li>Pure PHP</li>
                                            <li>JWT Verification</li>
                                            <li>HTTP Routing</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">Authentication Service</div>
                                    <div class="card-body">
                                        <p>Manages user identities, authentication, and authorization.</p>
                                        <p><strong>Technologies:</strong></p>
                                        <ul>
                                            <li>Pure PHP</li>
                                            <li>PostgreSQL</li>
                                            <li>JWT</li>
                                            <li>Bcrypt/Argon2 Hashing</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">Task Service</div>
                                    <div class="card-body">
                                        <p>Manages task creation, retrieval, updates, and deletion.</p>
                                        <p><strong>Technologies:</strong></p>
                                        <ul>
                                            <li>Pure PHP</li>
                                            <li>PostgreSQL</li>
                                            <li>RESTful API</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mt-4">Design Patterns Used</h5>
                        <ul>
                            <li><strong>Repository Pattern</strong> - For database interactions</li>
                            <li><strong>Dependency Injection</strong> - For service composition</li>
                            <li><strong>Factory Pattern</strong> - For object creation</li>
                            <li><strong>Strategy Pattern</strong> - For flexible algorithms</li>
                            <li><strong>Observer Pattern</strong> - For event handling</li>
                        </ul>
                        
                        <h5 class="mt-4">Communication</h5>
                        <p>Services communicate via HTTP/REST APIs. The API Gateway routes client requests to the appropriate service and handles cross-service communication when needed.</p>
                    </div>
                </details>
                
                <details id="deployment" class="card">
                    <summary>Deployment</summary>
                    <div class="details-content">
                        <p>The Task Management System is deployed using Docker containers. Each microservice runs in its own container and communicates with others via the internal Docker network.</p>
                        
                        <h5>Docker Compose Setup</h5>
                        <pre>version: '3'

services:
  # API Gateway
  api-gateway:
    build: ./api-gateway
    ports:
      - "8000:80"
    networks:
      - task-management-network
    depends_on:
      - auth-service
      - task-service

  # Authentication Service
  auth-service:
    build: ./auth-service
    environment:
      - DB_HOST=auth-db
      - DB_PORT=5432
      - DB_NAME=auth_db
      - DB_USER=postgres
      - DB_PASSWORD=secret
    networks:
      - task-management-network
    depends_on:
      - auth-db

  # Task Service
  task-service:
    build: ./task-service
    environment:
      - DB_HOST=task-db
      - DB_PORT=5432
      - DB_NAME=task_db
      - DB_USER=postgres
      - DB_PASSWORD=secret
    networks:
      - task-management-network
    depends_on:
      - task-db

  # Authentication Database
  auth-db:
    image: postgres:13
    environment:
      - POSTGRES_DB=auth_db
      - POSTGRES_USER=postgres
      - POSTGRES_PASSWORD=secret
    volumes:
      - auth-db-data:/var/lib/postgresql/data
    networks:
      - task-management-network

  # Task Database
  task-db:
    image: postgres:13
    environment:
      - POSTGRES_DB=task_db
      - POSTGRES_USER=postgres
      - POSTGRES_PASSWORD=secret
    volumes:
      - task-db-data:/var/lib/postgresql/data
    networks:
      - task-management-network

networks:
  task-management-network:
    driver: bridge

volumes:
  auth-db-data:
  task-db-data:</pre>
                        
                        <h5 class="mt-4">Deployment Steps</h5>
                        <ol>
                            <li>Clone the repository</li>
                            <li>Configure environment variables in <code>.env</code> files for each service</li>
                            <li>Run <code>docker-compose up -d</code> to start all services</li>
                            <li>Initialize databases using provided migration scripts</li>
                            <li>Access the API Gateway at <code>http://localhost:8000</code></li>
                        </ol>
                        
                        <h5 class="mt-4">Scaling Considerations</h5>
                        <p>The microservices architecture allows for independent scaling of services based on demand:</p>
                        <ul>
                            <li>Use Docker Swarm or Kubernetes for orchestration in production</li>
                            <li>Implement load balancing for high-traffic services</li>
                            <li>Consider database replication for increased read performance</li>
                            <li>Add Redis caching for frequently accessed data</li>
                        </ul>
                    </div>
                </details>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p>Task Management System - PHP Architecture Portfolio Project</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> created</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td>TIMESTAMP</td>
                                        <td>NOT NULL, DEFAULT NOW()</td>
                                        <td>When the task was