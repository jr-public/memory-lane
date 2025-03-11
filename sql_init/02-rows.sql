

-- -----------------------------------------------------------------------------
-- Initial data population
-- -----------------------------------------------------------------------------
INSERT INTO roles (name, description) VALUES 
    ('admin', 'Administrator with full system access'),
    ('manager', 'Can manage tasks and assignments for their team'),
    ('user', 'Regular user with standard task management capabilities'),
    ('guest', 'Guest that has minimal access');
INSERT INTO task_statuses (name, description) VALUES 
    ('todo', 'Task is not yet started'),
    ('in_progress', 'Task is currently being worked on'),
    ('review', 'Task is completed and awaiting review'),
    ('done', 'Task is completed and reviewed');
INSERT INTO users (username, email, password, role_id) VALUES 
	( 'Admin User', 'admin@example.com', '1234', 1 ),
	( 'Manager User', 'manager@example.com', '1234', 2 ),
	( 'Regular User', 'user@example.com', '1234', 3 ),
	( 'Jane Smith', 'jane.smith@example.com', '1234', 3 );