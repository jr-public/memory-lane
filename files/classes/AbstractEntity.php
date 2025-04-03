<?php
namespace MemoryLane;

abstract class AbstractEntity {
    protected $db;
    protected static $table;
    protected static $related_tables;
    protected static $editable_columns;

    public function __construct(\PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    public function create(array $data): array {
        try {
            $fields = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $query = 'INSERT INTO ' . static::$table . ' (' . $fields . ') VALUES (' . $placeholders . ')';
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
            
            $id = $this->db->lastInsertId();
            return response(true, $id);
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Entity creation error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    public function get(string $id): array {
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
            $result = $stmt->fetch();
            
            if (!$result) return response(true, []);
            
            return response(true, $result);
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Entity get error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public function list(array $options = []): array {
        $with = $options['with'] ?? [];
        
        try {
            // Build the query
            $query = $this->build_query($options);
    
            // Execute the query
            $stmt = $this->db->prepare($query['sql']);
            $stmt->execute($query['params']);
            $list = $stmt->fetchAll(\PDO::FETCH_UNIQUE);
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Entity list error: ' . $e->getMessage(), $e->getCode());
        }
        
        if (empty($list)) return response(true, []);
        
        $res = $this->load_relations($list, $with);
        if (!$res['success']) return $res;

        return response(true, array_values($res['data']));
    }

    public function tree(array $options = []): array {
        $res = $this->list($options);
        if (!$res['success']) return $res;
        $rows = $res['data'];

        // Return original rows if empty or parent_id doesn't exist in the first row
        if (empty($rows) || !array_key_exists('parent_id', $rows[0])) {
            return response(true, $rows);
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
        
        return response(true, $result);
    }

    public function update(string $id, array $data): array {
        if (empty(static::$editable_columns)) {
            return response(false, null, 'No editable columns defined', 400);
        }
        
        $invalidColumns = array_diff(array_keys($data), static::$editable_columns);
        if (!empty($invalidColumns)) {
            return response(false, $invalidColumns, 'Invalid columns for update', 400);
        }
        
        // Build SET clause with placeholders
        $setClause = [];
        foreach (array_keys($data) as $field) {
            $setClause[] = "$field = :$field";
        }
        
        // Combine SET clauses
        $setClauseStr = implode(', ', $setClause);
        
        // Prepare the UPDATE query
        $query = 'UPDATE ' . static::$table . ' SET ' . $setClauseStr . ' WHERE id = :id';
        
        // Add ID to the data array for the WHERE clause
        $data['id'] = $id;
            
        try {
            // Prepare and execute the statement
            $stmt = $this->db->prepare($query);
            $stmt->execute($data);
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Entity update error: ' . $e->getMessage(), $e->getCode());
        }
            
        // Return success based on affected rows
        if ($stmt->rowCount() > 0) {
            return response(true, true, 'Entity updated successfully');
        } else {
            return response(false, false, 'Entity not found or no changes made', 404);
        }
    }

    public function delete(string $id): array {
        try {
            $query = 'DELETE FROM ' . static::$table . ' WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            if ($stmt->rowCount() > 0) {
                return response(true, true, 'Entity deleted successfully');
            } else {
                return response(false, false, 'Entity not found', 404);
            }
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Entity delete error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    private function get_relations($with = []): array {
        if (empty($with)) {
            $relations = [];
        } elseif ($with == '*') {
            $relations = static::$related_tables;
        } else {
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
    
    private function load_relations($list = [], $with = []): array {
        if (empty($list)) return response(true, []);
        
        $related_tables = $this->get_relations($with);
        if (empty($related_tables)) return response(true, $list);
        
        foreach ($related_tables as $name => $rel_config) {
            $res = api_call(
                $rel_config['controller'],
                'list',
                [
                    'options' => [
                        'filters' => [$rel_config['relation_field'] . ' IN (' . implode(',', array_column($list, 'id')) . ')'],
                        'order'   => [$rel_config['relation_field'] . ' ASC']
                    ]
                ]
            );
            
            if (!$res['success']) return $res;
            
            $result = $res['data'];
            $rel_field = $rel_config['relation_field'];
            
            // Attach related entities to their parent entities
            foreach ($result as $row) {
                $parent_entity = &$list[$row[$rel_field]];
                if (!isset($parent_entity[$name]))
                    $parent_entity[$name] = [];
                $parent_entity[$name][] = $row;
            }
        }
        
        return response(true, $list);
    }

    protected function build_query(array $options = []): array {
        try {
            $perPage = $options['perPage'] ?? 10;
            $page = $options['page'] ?? 1;
            $filters = $options['filters'] ?? [];
            $order = $options['order'] ?? [];
            
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
        } catch (\Throwable $e) {
            // For query building issues, we need to throw rather than return
            // since the caller expects an array structure, not a response
            throw new \RuntimeException('Error building query: ' . $e->getMessage(), 0, $e);
            die();
        }
    }
}