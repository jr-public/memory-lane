<?php
namespace MemoryLane;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    private static $secret_key = '123456';
	private $db;
	public function __construct() {
		$this->db = DB;
	}

	public function authenticate(string $client_id, string $password): ?string {
		try {
			$query = "SELECT * FROM clients 
					  WHERE client_id = :client_id AND 
					  password_hash = :password_hash";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':client_id' => $client_id,
                ':password_hash' => $password
            ]);
            $client = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        
        if ($client === false) return null; // Authentication failed

        return $this->generateToken($client);
    }

	private function generateToken(array $client): string {
        $issued_at = time();
        $expiration = $issued_at + 3600; // Token valid for 1 hour

        $payload = [
            'iat' => $issued_at,           // Issued at
            'exp' => $expiration,          // Expiration time
            'sub' => $client['client_id'], // Subject (client ID)
            'client' => [
                'id' => $client['client_id'],
                'name' => $client['client_name'],
                'permissions' => $client['permissions'] ?? []
            ]
        ];

        return JWT::encode($payload, self::$secret_key, 'HS256');
    }

    /**
     * Verify JWT token
     * 
     * @param string $token JWT token to verify
     * @return array|false Decoded token payload or false if invalid
     */
    public function verifyToken(string $token): ?array {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            // Log token verification error
            error_log('Token verification failed: ' . $e->getMessage());
            return null;
        }
    }
}