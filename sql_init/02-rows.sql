

-- -----------------------------------------------------------------------------
-- Initial data population
-- -----------------------------------------------------------------------------
INSERT INTO public.clients ( client_id, client_name, password_hash, email ) VALUES 
    ('jr-client', 'jr-client', '1234', 'jr-client@example.com'),
    ('smith_industries', 'Smith Global Industries', '12341234', 'ceo@smithindustries.com'),
    ('tech_innovate', 'Tech Innovate Solutions', '12341234', 'contact@techinnovate.com'),
    ('green_energy_co', 'Green Energy Collective', '12341234', 'info@greenenergy.org'),
    ('marketing_pros', 'Marketing Professionals Ltd', '12341234', 'admin@marketingpros.net');
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