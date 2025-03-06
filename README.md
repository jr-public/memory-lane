# Task Management System - PHP Architecture Portfolio

A microservice-based task management system built with pure PHP to demonstrate software architecture skills. This project showcases my ability to design and implement a clean, modern architecture without relying on frameworks.

![Project Banner](https://via.placeholder.com/1200x300)

## 🌟 Project Overview

This Task Management System is designed to demonstrate architectural expertise in PHP development. It features:

- **Microservice Architecture**: Independent services communicating via RESTful APIs
- **Framework-Free Implementation**: Pure PHP to showcase core language knowledge
- **PostgreSQL Database**: Leveraging advanced features with optimized schemas
- **Clean Code Principles**: Following SOLID principles and design patterns
- **API Gateway**: Centralized request routing and authentication
- **JWT Authentication**: Secure, stateless authentication
- **Docker Deployment**: Containerized services for easy deployment

## 🏗️ Architecture

The system is composed of the following microservices:

### Authentication Service
Handles user registration, login, and token management:
- User registration and account management
- JWT token generation and validation
- Role-based access control
- User profile management

### Task Service
Manages tasks and their lifecycle:
- Task CRUD operations
- Assignment and status management
- Task filtering and sorting
- Comments and activity tracking

### API Gateway
Routes client requests to the appropriate service:
- Request routing
- Authentication verification
- Cross-origin resource sharing
- Rate limiting

## 🛠️ Technical Stack

- **Backend**: Pure PHP 8.1+
- **Database**: PostgreSQL 13+
- **Authentication**: JWT (JSON Web Tokens)
- **Containerization**: Docker & Docker Compose
- **API**: RESTful API design
- **Testing**: PHPUnit for unit and integration tests

## 📋 Features

- **User Management**
  - Registration and authentication
  - Role-based permissions
  - Profile management

- **Task Management**
  - Create, update, and delete tasks
  - Assign tasks to users
  - Set priorities and deadlines
  - Track status changes

- **Filtering & Sorting**
  - Filter tasks by status, priority, assignee
  - Sort by various fields (created date, due date, priority)
  - Pagination for large result sets

## 🚀 Getting Started

### Prerequisites

- Docker and Docker Compose
- Git
- Postman (for API testing)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/task-management-system.git
   cd task-management-system
   ```

2. Start the services using Docker Compose:
   ```bash
   docker-compose up -d
   ```

3. Initialize the databases:
   ```bash
   docker-compose exec auth-service php migrations/run.php
   docker-compose exec task-service php migrations/run.php
   ```

4. Access the API at `http://localhost:8000/api`

### API Documentation

Comprehensive API documentation is available at `http://localhost:8000/documentation.php` after starting the services.

## 🧩 Project Structure

```
task-management-system/
├── api-gateway/                  # API Gateway Service
│   ├── config/                   # Configuration files
│   ├── public/                   # Public-facing code
│   ├── src/                      # Source code
│   └── Dockerfile                # Docker configuration
│
├── auth-service/                 # Authentication Service
│   ├── config/                   # Configuration files
│   ├── migrations/               # Database migrations
│   ├── public/                   # Public-facing code
│   ├── src/                      # Source code
│   │   ├── Controllers/          # Request handlers
│   │   ├── Models/               # Domain models
│   │   ├── Repositories/         # Data access layer
│   │   ├── Services/             # Business logic
│   │   └── Utils/                # Utility classes
│   └── Dockerfile                # Docker configuration
│
├── task-service/                 # Task Service
│   ├── config/                   # Configuration files
│   ├── migrations/               # Database migrations
│   ├── public/                   # Public-facing code
│   ├── src/                      # Source code
│   │   ├── Controllers/          # Request handlers
│   │   ├── Models/               # Domain models
│   │   ├── Repositories/         # Data access layer
│   │   ├── Services/             # Business logic
│   │   └── Utils/                # Utility classes
│   └── Dockerfile                # Docker configuration
│
├── documentation/                # Project documentation
│   ├── index.php                 # Dashboard
│   └── documentation.php         # Technical documentation
│
├── docker-compose.yml            # Docker Compose configuration
└── README.md                     # Project README
```

## 🧪 Testing

Run the test suite for each service:

```bash
docker-compose exec auth-service vendor/bin/phpunit
docker-compose exec task-service vendor/bin/phpunit
```

## 🛡️ Security Considerations

- All passwords are hashed using bcrypt/Argon2
- JWT tokens are signed and have expiration times
- Input validation is performed on all API endpoints
- Prepared statements are used for all database queries
- CORS is properly configured on the API Gateway

## 🔍 Architectural Patterns Used

- **Repository Pattern**: For database abstraction
- **Dependency Injection**: For service composition
- **Factory Pattern**: For object creation
- **Strategy Pattern**: For flexible algorithms
- **Observer Pattern**: For event handling

## 🔮 Future Enhancements

- Notification Service for email/push notifications
- Real-time updates using WebSockets
- Advanced reporting and analytics
- Mobile application using the same API

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 About the Author

I'm a PHP architect with a focus on building scalable, maintainable systems. This project demonstrates my approach to software architecture, clean code, and modern PHP development.

---

*This project was created as a portfolio piece to demonstrate PHP architectural skills.*
