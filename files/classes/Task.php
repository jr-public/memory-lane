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



    
    public function get_project_assignees( $task_id ) {
        $root_res = $this->root( $task_id );
        if ( !$root_res['success'] || empty($root_res['data'])) {
            return $root_res;
        }

        $root_task_id = $root_res['data']['id'];

        $res = api_call('Task', 'get', [
            'id' => $root_task_id,
            'options' => [
                'with' => ['assignments']
            ]
        ]);
        if ( !$res ) {
            return $res;
        }
        $assignments = $res['data']['assignments'];
        $ids = array_column($assignments,'assigned_to');
        $users_res = api_call('User', 'list', [
            'options' => [
                'filters' => ['id IN (' . implode(',', $ids) . ')'],
                'unique' => true
            ]
        ]);
        if ( !$users_res ) {
            return $users_res;
        }
        return response(true, $users_res['data']);
    }
}