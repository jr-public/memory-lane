

-- -----------------------------------------------------------------------------
-- Initial data population
-- -----------------------------------------------------------------------------
INSERT INTO public.clients ( client_name, password_hash, email ) VALUES 
    ('jr-client', '1234', 'jr-client@example.com'),
    ('smith_industries', '12341234', 'ceo@smithindustries.com'),
    ('tech_innovate', '12341234', 'contact@techinnovate.com'),
    ('green_energy_co', '12341234', 'info@greenenergy.org'),
    ('marketing_pros', '12341234', 'admin@marketingpros.net');
INSERT INTO roles (name, description) VALUES 
    ('admin', 'Administrator with full system access'),
    ('manager', 'Can manage tasks and assignments for their team'),
    ('user', 'Regular user with standard task management capabilities'),
    ('guest', 'Guest that has minimal access');
INSERT INTO task_statuses (name, description, color) VALUES 
    ('todo', 'Task is not yet started', '#95a5a6'),
    ('in_progress', 'Task is currently being worked on', '#f39c12'),
    ('review', 'Task is completed and awaiting review', '#3498db'),
    ('done', 'Task is completed and reviewed', '#2ecc71');
-- Insert default priority levels
INSERT INTO task_priority (id, name, description, color) VALUES
    (1, 'urgent', 'Urgent priority tasks that need immediate attention', '#e74c3c'),
    (2, 'high', 'High priority tasks that should be completed soon', '#f39c12'),
    (3, 'medium', 'Medium priority tasks with standard importance', '#3498db'),
    (4, 'low', 'Low priority tasks that can be done when time permits', '#2ecc71'),
    (5, 'none', 'Tasks with no specific priority', '#95a5a6');
    -- Insert default difficulty levels (matching your JavaScript difficulty_list)
INSERT INTO task_difficulty (id, name, description, color) VALUES
    (1, 'trivial', 'Tasks that require minimal effort', '#2ecc71'),
    (2, 'easy', 'Tasks that are straightforward to complete', '#3498db'),
    (3, 'medium', 'Tasks with moderate complexity', '#f39c12'),
    (4, 'hard', 'Tasks that are challenging and require significant effort', '#e67e22'),
    (5, 'complex', 'Complex tasks that require careful planning and execution', '#e74c3c');
INSERT INTO users (client_id, username, email, password, role_id) VALUES 
	( 1, 'admin', 'admin@example.com', '1234', 1 ),
	( 1, 'Manager User', 'manager@example.com', '1234', 2 ),
	( 1, 'Regular User', 'user@example.com', '1234', 3 ),
	( 1, 'Jane Smith', 'jane.smith@example.com', '1234', 3 );
    
    