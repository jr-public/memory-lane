<?php
namespace MemoryLane;
class User extends AbstractEntity {
    protected static $table = 'users';
    public function authenticate(string $client_id, string $username, string $password, string $device ): array {
		try {
            $sql = "SELECT 
                        users.id,
                        users.username, 
                        clients.id AS client_id,
                        clients.client_name
                    FROM users
                    JOIN clients ON users.client_id = clients.id
                    WHERE users.username = :username AND users.password = :password AND users.client_id = :client_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':client_id'    => $client_id,
                ':username'     => $username,
                ':password'     => $password
            ]);
            $user = $stmt->fetch();
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Auth error: ' . $e->getMessage(), $e->getCode());
        }
        
        // Needs error code and or error explanation
        if ($user === false) return response(false, null, 'BAD_USER'); // 
        if ($user["client_id"] != $client_id) return response(false, null, "BAD_CLIENT"); // 
        
        return response( true, [
            "user" => $user,
            "jwt" => generate_token($user, $device)
        ]);
    }
}