<?php
namespace MemoryLane;
class TaskAssignment extends AbstractEntity {
    protected static $table = 'task_assignments';

    public function invite( $user_id, $task_id, $email ): array {
        $user_res = api_call('User', 'list', [
            'options' => [
                'filters' => ['email = :email'],
                'params' => [
                    'email' => $email
                ]
            ]
        ]);
        if (!$user_res['success']) {
            return $user_res;
        }
        $user = $user_res['data'][0] ?? [];

        if ( empty($user) ) {
            // SEND EMAIL
            // ...
            // PROFIT
            return response(false,[],"NO USER WITH THAT EMAIL");
        }
        else {
            // ADD ASSIGNMENT
            $res = $this->create([
                'task_id'       => intval($task_id),
                'assigned_to'   => intval($user['id']),
                'user_id'       => intval($user_id)
            ]);
            if (!$res['success']) return $res;
            $user['assignment_id'] = $res['data'];
        }
        return response(true,$user);
    }
}