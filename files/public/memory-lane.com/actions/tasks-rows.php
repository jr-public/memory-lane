<?php

$tasks = [
    [
        'id' => 1,
        'title' => 'Website Redesign',
        'description' => 'Complete redesign of the corporate website with new branding',
        'status' => 'in_progress',
        'due_date' => '2025-04-30',
        'priority' => 'high',
        'assigned_to' => 'Jane Smith',
        'parent_id' => null,
        'depth' => 0,
        'children' => [
            [
                'id' => 101,
                'title' => 'Design mockups approval',
                'description' => 'Get approval for the new design mockups',
                'status' => 'completed',
                'due_date' => '2025-02-01',
                'priority' => 'medium',
                'assigned_to' => 'Design Team',
                'parent_id' => 1,
                'depth' => 1,
                'children' => []
            ],
            [
                'id' => 102,
                'title' => 'Frontend implementation',
                'description' => 'Implement the frontend based on approved designs',
                'status' => 'in_progress',
                'due_date' => '2025-03-15',
                'priority' => 'high',
                'assigned_to' => 'Development Team',
                'parent_id' => 1,
                'depth' => 1,
                'children' => [
                    [
                        'id' => 1021,
                        'title' => 'Homepage development',
                        'description' => 'Develop the new homepage',
                        'status' => 'in_progress',
                        'due_date' => '2025-02-28',
                        'priority' => 'high',
                        'assigned_to' => 'John Doe',
                        'parent_id' => 102,
                        'depth' => 2,
                        'children' => []
                    ],
                    [
                        'id' => 1022,
                        'title' => 'About page development',
                        'description' => 'Develop the about page',
                        'status' => 'pending',
                        'due_date' => '2025-03-05',
                        'priority' => 'medium',
                        'assigned_to' => 'Sarah Johnson',
                        'parent_id' => 102,
                        'depth' => 2,
                        'children' => []
                    ]
                ]
            ],
            [
                'id' => 103,
                'title' => 'Content migration',
                'description' => 'Migrate content from old site to new site',
                'status' => 'pending',
                'due_date' => '2025-04-15',
                'priority' => 'medium',
                'assigned_to' => 'Content Team',
                'parent_id' => 1,
                'depth' => 1,
                'children' => []
            ]
        ]
    ],
    [
        'id' => 2,
        'title' => 'Mobile App Development',
        'description' => 'Development of a cross-platform mobile application',
        'status' => 'in_progress',
        'due_date' => '2025-06-30',
        'priority' => 'high',
        'assigned_to' => 'John Doe',
        'parent_id' => null,
        'depth' => 0,
        'children' => [
            [
                'id' => 201,
                'title' => 'Requirements gathering',
                'description' => 'Gather and document all requirements',
                'status' => 'completed',
                'due_date' => '2025-02-15',
                'priority' => 'high',
                'assigned_to' => 'Project Manager',
                'parent_id' => 2,
                'depth' => 1,
                'children' => []
            ],
            [
                'id' => 202,
                'title' => 'UI/UX design',
                'description' => 'Design user interface and experience',
                'status' => 'in_progress',
                'due_date' => '2025-03-30',
                'priority' => 'high',
                'assigned_to' => 'Design Team',
                'parent_id' => 2,
                'depth' => 1,
                'children' => []
            ]
        ]
    ],
    [
        'id' => 3,
        'title' => 'Marketing Campaign',
        'description' => 'Plan and execute Q2 marketing campaign',
        'status' => 'pending',
        'due_date' => '2025-05-15',
        'priority' => 'medium',
        'assigned_to' => 'Marketing Team',
        'parent_id' => null,
        'depth' => 0,
        'children' => []
    ]
];