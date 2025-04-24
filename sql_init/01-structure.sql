-- =============================================================================
-- Task Management System Database Schema
-- =============================================================================
-- This schema defines the tables for a task management system, including
-- user authentication, role-based permissions, and task tracking.
-- =============================================================================

CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Roles table for role-based access control
-- Stores different types of roles users can have in the system
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL
);

-- Task statuses lookup table
-- Defines the possible states a task can be in during its lifecycle
CREATE TABLE task_statuses (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(50) NOT NULL DEFAULT '#95a5a6'
);

-- First create the task_priority table
CREATE TABLE task_priority (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(50) NOT NULL DEFAULT '#95a5a6'
);
-- First create the task_difficulty table
CREATE TABLE task_difficulty (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(50) NOT NULL DEFAULT '#95a5a6'
);

-- Users table to store user information
-- Contains all registered users of the system
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL REFERENCES clients(id), -- Client that owns the user
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INTEGER NOT NULL REFERENCES roles(id),
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Tasks table to store task information
-- The core table for all tasks in the system
CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id), -- User that created the task
    title VARCHAR(255) NOT NULL,              -- Short title describing the task
    description TEXT NULL,                    -- Detailed task description
    parent_id INTEGER REFERENCES tasks(id) ON DELETE CASCADE,
    depth INTEGER NOT NULL DEFAULT 0,
    status_id INTEGER NOT NULL REFERENCES task_statuses(id),
    priority_id INTEGER NOT NULL DEFAULT 3 REFERENCES task_priority(id),
    difficulty_id INTEGER NOT NULL DEFAULT 3 REFERENCES task_difficulty(id),
    due_date TIMESTAMP NULL,                  -- When the task is due (optional)
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);
ALTER TABLE users ADD CONSTRAINT unique_username_per_client UNIQUE (client_id, username);

-- Task assignments table
-- Tracks which users are assigned to which tasks
CREATE TABLE task_assignments (
    id SERIAL PRIMARY KEY,
    task_id INTEGER NOT NULL REFERENCES tasks(id) ON DELETE CASCADE, -- Associated task
    user_id INTEGER NOT NULL REFERENCES users(id), -- User that created the assignment
    assigned_to INTEGER NOT NULL REFERENCES users(id), -- User that has been assigned the task
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
ALTER TABLE task_assignments ADD CONSTRAINT unique_task_assignment UNIQUE (task_id, assigned_to);

-- Task comments table
-- Stores comments and updates made on tasks
CREATE TABLE task_comments (
    id SERIAL PRIMARY KEY,
    task_id INTEGER NOT NULL REFERENCES tasks(id) ON DELETE CASCADE, -- Associated task
    user_id INTEGER NOT NULL REFERENCES users(id), -- User that commented
    text TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);



-- -----------------------------------------------------------------------------
-- Database functions and triggers
-- -----------------------------------------------------------------------------

-- Function to automatically update the updated_at timestamp
-- Used by triggers to keep the updated_at column current
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger to automatically update the updated_at column
CREATE TRIGGER update_tasks_updated_at
    BEFORE UPDATE ON tasks
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_task_comments_updated_at
    BEFORE UPDATE ON task_comments
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Function to automatically create a task assignment when a top-level task is created
CREATE OR REPLACE FUNCTION create_task_assignment_on_top_level_task()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.parent_id IS NULL THEN
        INSERT INTO task_assignments (task_id, user_id, assigned_to)
        VALUES (NEW.id, NEW.user_id, NEW.user_id);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger to automatically create a task assignment for top-level tasks
CREATE TRIGGER create_task_assignment_for_top_level_tasks
    AFTER INSERT ON tasks
    FOR EACH ROW
    WHEN (NEW.parent_id IS NULL)
    EXECUTE FUNCTION create_task_assignment_on_top_level_task();

-- -----------------------------------------------------------------------------
-- Indexes
-- -----------------------------------------------------------------------------
CREATE INDEX idx_tasks_user_id ON tasks(user_id);
CREATE INDEX idx_tasks_parent_id ON tasks(parent_id);
CREATE INDEX idx_tasks_depth ON tasks(depth);
CREATE INDEX idx_tasks_status_id ON tasks(status_id);
CREATE INDEX idx_task_assignments_task_id ON task_assignments(task_id);
CREATE INDEX idx_task_assignments_assigned_to ON task_assignments(assigned_to);
CREATE INDEX idx_task_comments_task_id ON task_comments(task_id);
CREATE INDEX idx_task_comments_user_id ON task_comments(user_id);
