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


    public function get_root( $task_id ) {
        $sql = "WITH RECURSIVE TaskHierarchy AS (
            SELECT id, parent_id
            FROM tasks
            WHERE id = :task_id
            UNION ALL
            SELECT t.id, t.parent_id
            FROM tasks t
            INNER JOIN TaskHierarchy th ON t.id = th.parent_id
        )
        SELECT id FROM TaskHierarchy WHERE parent_id IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':task_id' => $task_id]);
        $result = $stmt->fetch();
        return $result['id'] ?? null;
    }
    public function get_project_asignees( $task_id ) {
        $root_task_id = $this->get_root( $task_id );
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