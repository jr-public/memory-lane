<?php
namespace MemoryLane;
class TaskAssignment extends AbstractEntity {
    protected static $table = 'task_assignments';

    public function invite( $creator_id, $task_id, $email ): array {
        $creator_res = api_call('User', 'list', [
            'options' => [
                'filters' => ['id = :id'],
                'params' => [
                    'id' => intval($creator_id)
                ]
            ]
        ]);
        if (!$creator_res['success'] ){
            return $creator_res;
        }

        $creator = $creator_res['data'][0];

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
            // ADD TEMPORARY USER TO DB
            // ADD ASSIGNMENT AS IF THEY WERE ALREADY IN
            // ...
            // PROFIT
            $username = explode('@', $email)[0];
            $user = [
                'username'      => $username,
                'email'         => $email,
                'password'      => '1234',
                'client_id'     => intval($creator['client_id']),
                'role_id'       => 1
            ];
            $user_res = api_call('User', 'create', [
                'data' => $user
            ]);
            if (!$user_res['success']) {
                return $user_res;
            }
            $user['id'] = $user_res['data'];
        }
        
        // ADD ASSIGNMENT
        $res = $this->create([
            'task_id'       => intval($task_id),
            'assigned_to'   => intval($user['id']),
            'user_id'       => intval($creator_id)
        ]);
        if (!$res['success']) return $res;
        $user['assignment_id'] = $res['data'];

        return response(true,$user);
    }
}