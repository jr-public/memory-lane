

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
INSERT INTO users (client_id, username, email, password, role_id) VALUES 
	( 1, 'admin', 'admin@example.com', '1234', 1 ),
	( 1, 'Manager User', 'manager@example.com', '1234', 2 ),
	( 1, 'Regular User', 'user@example.com', '1234', 3 ),
	( 1, 'Jane Smith', 'jane.smith@example.com', '1234', 3 );
INSERT INTO tasks (user_id, title, description, status_id, due_date) VALUES 
    (1, 'Complete project proposal', 'Draft and submit the initial proposal for the Q2 marketing campaign', 1, '2025-04-15 17:00:00'),
    (1, 'Schedule team meeting', 'Set up weekly sync with development team to discuss progress', 1, '2025-03-31 10:00:00'),
    (1, 'Review client feedback', 'Go through client comments on the latest deliverable and prepare response', 1, '2025-04-02 12:00:00');

-- Insert 10 task assignments for user with ID 3 (Regular User)
-- Distributed across the 3 available tasks
INSERT INTO task_assignments (task_id, user_id, assigned_to) VALUES
    -- Assignments for Task 1: "Complete project proposal"
    (1, 1, 3),
    (1, 2, 3),
    (1, 4, 3),
    (1, 1, 3),
    -- Assignments for Task 2: "Schedule team meeting"
    (2, 1, 3),
    (2, 2, 3),
    (2, 4, 3),
    -- Assignments for Task 3: "Review client feedback"
    (3, 1, 3),
    (3, 2, 3),
    (3, 4, 3);
-- Insert 10 task comments distributed across the 3 available tasks
INSERT INTO task_comments (task_id, user_id, text) VALUES
    -- Comments for Task 1: "Complete project proposal"
    (1, 1, 'Initial draft looks good. Please add more details to the budget section.'),
    (1, 2, 'I''ve reviewed the proposal. We need to strengthen the ROI analysis.'),
    (1, 3, 'Working on implementing all the requested changes. Will submit by tomorrow.'),
    (1, 4, 'Added some market research data that might be helpful for the proposal.'),
    
    -- Comments for Task 2: "Schedule team meeting"
    (2, 1, 'Let''s try to schedule this for Monday morning if everyone is available.'),
    (2, 3, 'I have a conflict on Monday morning. Can we do Tuesday instead?'),
    (2, 2, 'Tuesday works for me. Shall we say 10 AM?'),
    
    -- Comments for Task 3: "Review client feedback"
    (3, 4, 'The client seems concerned about the timeline. We should address this first.'),
    (3, 2, 'I''ve compiled all feedback into a single document for easier review.'),
    (3, 3, 'Just had a call with the client to clarify some points. Will share notes shortly.');