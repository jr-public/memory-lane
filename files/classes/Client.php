<?php
namespace MemoryLane;
class Client {
    private $db;
    private static $table = 'clients';
    
    public function __construct(\PDO $dbConnection) {
        $this->db = $dbConnection;
    }
	
    public function create( array $data ) {
        try {
            $fields = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $query = 'INSERT INTO ' . self::$table . ' (' . $fields . ') VALUES (' . $placeholders . ')';
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
            
			return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            die('Entity creation error: ' . $e->getMessage());
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
            die('Entity get error: ' . $e->getMessage());
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
            die('Entity list error: ' . $e->getMessage());
        }
    }
}