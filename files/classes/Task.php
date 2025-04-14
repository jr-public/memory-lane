<?php
namespace MemoryLane;
class Task extends AbstractEntity {
    protected static $table = 'tasks';
    protected static $related_tables = [
        'assignments' => [
            'controller' => 'TaskAssignment',
            'table' => 'task_assignments',
            'relation_field' => 'task_id',
            'select' => '*'
        ],
        'comments' => [
            'controller' => 'TaskComment',
            'table' => 'task_comments',
            'relation_field' => 'task_id',
            'select' => '*'
        ]
    ];
    protected static $editable_columns = ["title","description","due_date","status_id","priority_id","difficulty_id"];
}