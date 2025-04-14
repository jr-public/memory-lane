-- Insert top-level tasks
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (1, 1, 'Website Redesign Project', 'Complete overhaul of company website with new branding', NULL, 0, 2, 1, 5, '2025-05-15'),
  (2, 2, 'Mobile App Development', 'Create a cross-platform mobile application', NULL, 0, 1, 2, 4, '2025-06-30'),
  (3, 3, 'Marketing Campaign', 'Q2 digital marketing campaign', NULL, 0, 3, 1, 3, '2025-04-10'),
  (4, 4, 'Content Creation', 'Create blog posts and social media content', NULL, 0, 4, 3, 2, '2025-03-25');

-- Insert second-level tasks for Website Redesign (parent_id = 1)
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (5, 1, 'Design mockups', 'Create design mockups for all pages', 1, 1, 4, 2, 3, '2025-03-20'),
  (6, 2, 'Frontend development', 'Implement responsive frontend', 1, 1, 2, 1, 4, '2025-04-15'),
  (7, 3, 'Backend integration', 'Connect frontend to API endpoints', 1, 1, 1, 2, 5, '2025-05-01');

-- Insert third-level tasks for Frontend development (parent_id = 6)
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (8, 2, 'Header and navigation', 'Implement main navigation', 6, 2, 4, 3, 2, '2025-03-25'),
  (9, 1, 'Contact form', 'Create and validate contact form', 6, 2, 2, 4, 1, '2025-04-05'),
  (10, 4, 'Footer components', 'Implement footer with links', 6, 2, 1, 5, 1, '2025-04-10');

-- Insert second-level tasks for Mobile App Development (parent_id = 2)
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (11, 2, 'UI/UX design', 'Create app interface designs', 2, 1, 3, 2, 3, '2025-04-15'),
  (12, 3, 'Core functionality', 'Implement core app features', 2, 1, 1, 1, 5, '2025-05-30'),
  (13, 1, 'Testing and QA', 'Quality assurance and bug fixing', 2, 1, 1, 3, 3, '2025-06-15');

-- Insert third-level tasks for Core functionality (parent_id = 12)
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (14, 3, 'Authentication system', 'Implement user login/signup', 12, 2, 2, 2, 4, '2025-05-01'),
  (15, 4, 'Database integration', 'Connect to backend services', 12, 2, 1, 2, 4, '2025-05-15');

-- Insert fourth-level tasks for Authentication system (parent_id = 14)
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (16, 3, 'Social login integration', 'Add Google and Facebook login', 14, 3, 1, 3, 3, '2025-04-25'),
  (17, 2, 'Password recovery', 'Implement password reset flow', 14, 3, 1, 4, 2, '2025-04-28');

-- Insert second-level tasks for Marketing Campaign (parent_id = 3)
INSERT INTO tasks (id, user_id, title, description, parent_id, depth, status_id, priority_id, difficulty_id, due_date) VALUES
  (18, 3, 'Social media schedule', 'Plan and schedule social posts', 3, 1, 4, 2, 2, '2025-03-20'),
  (19, 4, 'Email newsletter', 'Design and send monthly newsletters', 3, 1, 2, 3, 3, '2025-04-05');

-- Insert task assignments (not all tasks need assignments)
INSERT INTO task_assignments (task_id, user_id, assigned_to, created_at) VALUES
  -- Website Redesign assignments
  (1, 1, 2, '2025-02-15 10:30:00'),  -- Admin assigned to Manager
  (5, 1, 3, '2025-02-16 09:15:00'),  -- Design mockups assigned to Regular User
  (6, 2, 4, '2025-02-16 11:20:00'),  -- Frontend development assigned to Jane
  (9, 1, 3, '2025-03-01 14:45:00'),  -- Contact form assigned to Regular User
  
  -- Mobile App Development assignments
  (2, 2, 1, '2025-03-01 10:00:00'),  -- Mobile App assigned to Admin
  (11, 2, 4, '2025-03-02 15:30:00'), -- UI/UX design assigned to Jane
  (12, 3, 2, '2025-03-05 11:00:00'), -- Core functionality assigned to Manager
  (14, 3, 1, '2025-03-10 09:45:00'), -- Authentication system assigned to Admin
  (16, 3, 4, '2025-03-12 16:20:00'), -- Social login integration assigned to Jane
  
  -- Marketing Campaign assignments
  (3, 3, 2, '2025-02-20 13:15:00'),  -- Marketing Campaign assigned to Manager
  (18, 3, 4, '2025-02-22 10:30:00'); -- Social media schedule assigned to Jane

-- Insert task comments
INSERT INTO task_comments (task_id, user_id, text, created_at) VALUES
  -- Comments on Website Redesign
  (1, 1, 'This is our highest priority project for Q2.', '2025-02-15 10:35:00'),
  (1, 2, 'I''ll start assembling the team today.', '2025-02-15 11:20:00'),
  
  (5, 3, 'First draft of mockups completed. Need feedback.', '2025-03-01 15:45:00'),
  (5, 1, 'Great work! Let''s review them in tomorrow''s meeting.', '2025-03-01 16:30:00'),
  
  (6, 4, 'Starting with the homepage implementation.', '2025-03-05 09:10:00'),
  (6, 2, 'Make sure it''s fully responsive.', '2025-03-05 10:25:00'),
  (6, 4, 'Responsive design implemented for all screen sizes.', '2025-03-15 14:20:00'),
  
  -- Comments on Mobile App Development
  (2, 2, 'We need to prioritize user experience for this app.', '2025-03-01 10:15:00'),
  (2, 1, 'Agreed. Let''s schedule a brainstorming session.', '2025-03-01 11:30:00'),
  
  (11, 4, 'Initial wireframes ready for review.', '2025-03-10 13:40:00'),
  (11, 2, 'These look good. Please proceed with high-fidelity designs.', '2025-03-10 15:55:00'),
  
  (14, 1, 'We should use JWT for authentication.', '2025-03-12 11:25:00'),
  (14, 3, 'Good idea. I''ll implement it that way.', '2025-03-12 13:10:00'),
  
  -- Comments on Marketing Campaign
  (3, 3, 'We need to align this with the product launch.', '2025-02-21 09:30:00'),
  (3, 2, 'Let''s focus on engagement metrics for this campaign.', '2025-02-21 10:45:00'),
  
  (18, 4, 'Social media calendar draft completed.', '2025-03-01 11:20:00'),
  (18, 3, 'Looks good! Make sure we have enough video content.', '2025-03-01 14:15:00'),
  (18, 4, 'Added more video posts as requested.', '2025-03-03 10:30:00');

-- Set the sequence values to continue after our inserted IDs
SELECT setval('tasks_id_seq', 20);
SELECT setval('task_assignments_id_seq', 12);
SELECT setval('task_comments_id_seq', 20);