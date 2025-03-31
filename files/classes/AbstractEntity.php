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
            // Use the query builder with a filter for ID
            $options = [
                'filters' => ['id = :id'],
                'perPage' => 1,
                'page' => 1
            ];
            
            $query = $this->build_query($options);
            
            // Update the params to include the ID
            $query['params']['id'] = $id;
            
            // Execute the query
            $stmt = $this->db->prepare($query['sql']);
            $stmt->execute($query['params']);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Entity get error: ' . $e->getMessage());
        }
    }
    public function list(array $options = []) {
        try {
            $with = $options['with'] ?? [];
            
            // Build the query
            $query = $this->build_query($options);
            // echo $query['sql'];
            // die();
            // Execute the query
            $stmt = $this->db->prepare($query['sql']);
            $stmt->execute($query['params']);
            $list = $stmt->fetchAll(\PDO::FETCH_UNIQUE);
            if (empty($list)) return [];
            
            $list = $this->load_relations( $list, $with );
            return array_values($list);
        } catch (\PDOException $e) {
            die('Entity list error: ' . $e->getMessage());
        }
    }
    public function tree(array $options = []) {

        $rows = $this->list($options);
        
        // Return original rows if empty or parent_id doesn't exist in the first row
        if (empty($rows) || !isset($rows[0]['parent_id'])) {
            return $rows;
        }
        
        $result = [];
        $itemMap = [];
        
        // First pass: map all items
        foreach ($rows as $row) {
            $item = $row;
            $item['children'] = [];
            $itemMap[$item['id']] = $item;
        }
        
        // Second pass: build the hierarchy
        foreach ($itemMap as $id => $item) {
            // Check if this item has a parent and if the parent exists in our dataset
            if (!empty($item['parent_id']) && isset($itemMap[$item['parent_id']])) {
                // Add to parent's children
                $itemMap[$item['parent_id']]['children'][] = &$itemMap[$id];
            } else {
                // If parent doesn't exist in our dataset, treat as a root node
                $result[] = &$itemMap[$id];
            }
        }
        
        return $result;
    }

    
    private function get_relations( $with = [] ) {
        if (empty($with)) 
            $relations = [];
        elseif ( $with == '*' ) 
            $relations = static::$related_tables;
        else {
            $relations = array_filter(
                static::$related_tables, 
                function($key) use ($with) {
                    return in_array($key, $with);
                }, 
                ARRAY_FILTER_USE_KEY
            );
        }
        return $relations;
    }    
    private function load_relations( $list = [], $with = [] ) {
        $related_tables = $this->get_relations($with);
        if (empty($related_tables)) return $list;
        foreach ($related_tables as $name => $rel_config) {
            $result = api_call(
                $rel_config['controller'],
                'list',
                [
                    'options' => [
                        'filters' => [$rel_config['relation_field'] . ' IN (' . implode(',', array_column($list, 'id')) . ')'],
                        'order'   => [$rel_config['relation_field'] . ' ASC']
                    ]
                ]
            );
            $rel_field = $rel_config['relation_field'];
            // Attach related entities to their parent entities
            foreach ($result as $row) {
                $parent_entity = &$list[$row[$rel_field]];
                if (!isset($parent_entity[$name])) 
                    $parent_entity[$name] = [];
                $parent_entity[$name][] = $row;
            }
        }
        
        return $list;
    }
    protected function build_query(array $options = []) {

        // ESTO NECESITA UN TRY CATCH Y POR LO TANTO UNA RESPUESTA MAS COMPLEJA

        $perPage    = $options['perPage'] ?? 10;
        $page       = $options['page'] ?? 1;
        $filters    = $options['filters'] ?? [];
        $order      = $options['order'] ?? [];
        
        $offset = ($page - 1) * $perPage;
        
        $select = 'SELECT id, *';
        $from = ' FROM ' . static::$table;
        $joins = '';
        $where = (!empty($filters)) ? ' WHERE ' . implode(' AND ', $filters) : '';
        $groupBy = '';
        $orderBy = ' ORDER BY ' . (!empty($order) ? implode(', ', $order) : 'id DESC');
        $limit = ' LIMIT :limit OFFSET :offset';
        
        $params = [
            'limit' => $perPage,
            'offset' => $offset
        ];
        
        // Assemble the complete query
        $sql = $select . $from . $joins . $where . $groupBy . $orderBy . $limit;
        
        return [
            'sql' => $sql,
            'params' => $params
        ];
    }
}
