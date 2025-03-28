<?php
namespace MemoryLane;
abstract class AbstractEntity	 {
    protected $db;
    protected static $table;
    protected static $related_tables;
    
    public function __construct(\PDO $dbConnection) {
        $this->db = $dbConnection;
    }
    
    public function create(array $data) {
        try {
            $fields = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $query = 'INSERT INTO ' . static::$table . ' (' . $fields . ') VALUES (' . $placeholders . ')';
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
            
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            die('Entity creation error: ' . $e->getMessage());
        }
    }
    
    public function get(string $id) {
        try {
            $sql = 'SELECT * FROM ' . static::$table . ' WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Entity get error: ' . $e->getMessage());
        }
    }
    
    public function _list(int $page = 1, int $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = 'SELECT * FROM ' . static::$table . '
                    ORDER BY id DESC
                    LIMIT :limit
                    OFFSET :offset';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'limit' => $perPage,
                'offset' => $offset
            ]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            die('Entity list error: ' . $e->getMessage());
        }
    }
    public function list(array $config = []) {
        try {
            $page       = $config['page'] ?? 1;
            $perPage    = $config['perPage'] ?? 10;
            $with       = $config['with'] ?? [];
            $filters    = $config['filters'] ?? [];
            $order      = $config['order'] ?? [];
            
            
            // Calculate offset for pagination
            $offset = ($page - 1) * $perPage;
            
            // Start building the base query
            $select = 'SELECT id, ' . static::$table . '.*';
            $from = ' FROM ' . static::$table;
            $joins = '';
            $where = (!empty($filters)) ? ' WHERE ' . implode(' AND ', $config['filters']) : '';
            $groupBy = '';
            $orderBy = ' ORDER BY ' . (!empty($order) ? implode(', ', $config['order']) : static::$table . '.id DESC');
            $limit = ' LIMIT :limit OFFSET :offset';
            
            // Params for prepared statement
            $params = [
                'limit' => $perPage,
                'offset' => $offset
            ];
            
            // Assemble the complete query
            $sql = $select . $from . $joins . $where . $groupBy . $orderBy . $limit;
            // echo $sql . "<br /><br />";

            // Prepare and execute
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $list = $stmt->fetchAll(\PDO::FETCH_UNIQUE);
            // Process relationships if any are requested
            if (!empty($list) && !empty($with) && !empty(static::$related_tables)) {
                $relation_ids = array_column($list, 'id');
                foreach (static::$related_tables as $name => $rel_config) {
                    $api_call_config = [
                        'config' => [
                            'filters'   => [ $rel_config['relation_field'] . ' IN (' . implode(',',$relation_ids) . ')' ],
                            'order'     => [ $rel_config['relation_field'] . ' ASC' ]
                        ]
                    ];
                    $relation_result = api_call($rel_config['controller'],'list',$api_call_config) ?? [];
                    foreach ($relation_result AS $row) {
                        $pointer = &$list[$row[$rel_config['relation_field']]];
                        if ( !isset($pointer[$name]) ) $pointer[$name] = [];
                        $pointer[$name][] = $row;
                    }
                }
            }
            return array_values($list);
        } catch (\PDOException $e) {
            die('Entity list error: ' . $e->getMessage());
        }
    }
}
