<?php

namespace MemoryLane;

/**
 * User class for handling user-related operations in the API
 */
class User {
    private $db;
    private static $table = 'users';
    
    public function __construct(\PDO $dbConnection) {
        $this->db = $dbConnection;
    }
	
    public function create( array $userData ) {
        try {
            // Prepare query
            $fields = implode(', ', array_keys($userData));
            $placeholders = ':' . implode(', :', array_keys($userData));
            
            $query = 'INSERT INTO ' . self::$table . ' (' . $fields . ') VALUES (' . $placeholders . ')';
            $stmt = $this->db->prepare($query);
            $stmt->execute($userData);
            
			return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            die('User creation error: ' . $e->getMessage());
        }
    }
    public function get( string $id ) {
        try {
            $sql = 'SELECT * FROM ' . self::$table . ' WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([ 'id' => $id ]);
            $list = $stmt->fetch();
			return $list;
        } catch (\PDOException $e) {
            die('User get error: ' . $e->getMessage());
        }
    }
    public function list(int $page = 1, int $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = 'SELECT * FROM ' . self::$table . ' 
					ORDER BY id DESC 
					LIMIT :limit 
					OFFSET :offset';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
				'limit' => $perPage,
				'offset' => $offset
			]);
            $list = $stmt->fetchAll();
			return $list;
        } catch (\PDOException $e) {
            die('User list error: ' . $e->getMessage());
        }
    }
    public function authenticate(string $client_id, string $username, string $password): array {
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
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        
        // Needs error code and or error explanation
        if ($user === false) return response(false); // 
        if ($user["client_id"] != $client_id) return response(false, $user, "BAD_CLIENT"); // 
        
        return response( true, [
            "user" => $user,
            "jwt" => generate_token($user)
        ]);
    }
}