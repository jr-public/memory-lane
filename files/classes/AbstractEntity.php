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
            return response(false, null, 'Entity creation error: ' . $e->getMessage());
        }
    }
    
    public function get(string $id, array $options = []): array {
        $options = array_merge($options, [
            'filters' => ['id = :id'],
            'params' => ['id' => $id],
            'perPage' => 1,
            'page' => 1
        ]);
        $res = $this->list($options);
        if (!$res['success']) return $res;
        return response(true, $res['data'][0] ?? []);
    }


    public function list(array $options = []): array {
        $with = $options['with'] ?? [];
        $unique = $options['unique'] ?? false;

        try {
            // Build and execute the query
            if ( isset($options['sql']) ) {
                $query = $options['sql'];
                $stmt = $this->db->prepare($query);
                $stmt->execute($options['params'] ?? []);
            }
            else {                
                $query = $this->build_query($options);
                $stmt = $this->db->prepare($query['sql']);
                $params = $query['params'];
                if (isset($options['params']) && is_array($options['params'])) {
                    $params = array_merge($params, $options['params']);
                }
                $stmt->execute($params);
            }
            $list = $stmt->fetchAll(\PDO::FETCH_UNIQUE);
        } catch (\PDOException $e) {
            // LOG ERROR
            return response(false, null, 'Entity list error: ' . $e->getMessage());
        }
        
        if (empty($list)) return response(true, []);
        
        $res = $this->load_relations($list, $with);
        if (!$res['success']) return $res;

        if (!$unique) return response(true, array_values($res['data']));
        else return response(true, $res['data']);
    }
    public function root( $id, $options = [] ) {
        
        $sql = 'WITH RECURSIVE TaskHierarchy AS (
                SELECT id, parent_id
                FROM ' . static::$table . '
                WHERE id = :id
                UNION ALL
                SELECT t.id, t.parent_id
                FROM tasks t
                INNER JOIN TaskHierarchy th ON t.id = th.parent_id
            )
            SELECT id FROM TaskHierarchy WHERE parent_id IS NULL';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            if ( empty($result) ) {
                return response(true, null);
            }
        } catch (\Throwable $th) {
            return response(false, $th, $th->getMessage());
        }

        $root = $this->get($result['id'], $options);
        if ( !$root['success'] ) {
            return $root;
        }
        return response(true, $root['data']);
    }
    public function tree(array $options = []): array {

        $with = $options['with'] ?? [];

        $sql = "WITH RECURSIVE task_tree AS (
            -- Base case: Select the root task
            SELECT *, 0 AS level
            FROM tasks
            WHERE id = :id
            
            UNION ALL
            
            -- Recursive case: Select all children of tasks already in the CTE
            SELECT t.*, tt.level + 1 AS level
            FROM tasks t
            JOIN task_tree tt ON t.parent_id = tt.id
        )
        -- Select all tasks except the root task itself
        SELECT id, * FROM task_tree
        WHERE level > 0
        ORDER BY level, id;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $options['root_node']
        ]);
        $list = $stmt->fetchAll(\PDO::FETCH_UNIQUE);

        
        $res = $this->load_relations($list, $with);
        if (!$res['success']) return $res;
        $list = $res['data'];
        
        $rows = array_values($list);
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
        $response = [
            "tree" => $result,
            "list" => $list
        ];
        return response(true, $response);
    }
    /**
     * Searches entities based on a query string in 'email' and 'username' columns.
     * Allows additional filtering via options.
     * Uses ILIKE for case-insensitive partial matching (PostgreSQL specific).
     *
     * @param string $query The search term.
     * @param array $options {
     *     Optional. An array of query options.
     *
     *     @type int    $perPage Number of items per page. Default 1000.
     *     @type int    $page    Current page number. Default 1.
     *     @type array  $order   Array of order clauses (e.g., ['column ASC', 'other DESC']). Default ['id DESC'].
     *     @type array  $filters Array of additional WHERE clause conditions (strings, e.g., ['status_id = :status']).
     *     @type array  $params  Key-value pairs for placeholders used in $filters.
     *     @type array  $with    Array of relation names to load.
     *     @type bool   $unique  Whether to return results indexed by ID (true) or as a plain array (false). Default false.
     * }
     * @return array A response array containing 'success', 'data', 'message', 'code'.
     */
    public function search(string $query, array $options = []): array {
        // --- Input Validation ---
        if (empty(trim($query))) {
            return response(false, null, 'Search query cannot be empty.', 400);
        }
        // Validate filters if provided
        if (isset($options['filters']) && !is_array($options['filters'])) {
             return response(false, null, "'filters' option must be an array.", 400);
        }
        if (isset($options['filters'])) {
            foreach ($options['filters'] as $filter) {
                if (!is_string($filter)) {
                    return response(false, null, "All elements in 'filters' option must be strings.", 400);
                }
            }
        }
        // Validate params if provided
        if (isset($options['params']) && !is_array($options['params'])) {
             return response(false, null, "'params' option must be an array.", 400);
        }

        try {
            // --- Extract Options ---
            $perPage       = $options['perPage'] ?? 1000;
            $page          = $options['page'] ?? 1;
            $order         = $options['order'] ?? [];
            $filters       = $options['filters'] ?? [];
            $filter_params = $options['params'] ?? []; // Use 'params' key as requested
            $with          = $options['with'] ?? [];
            $unique        = $options['unique'] ?? false; // Default to false for search results
            $offset        = ($page - 1) * $perPage;

            // --- Construct SQL Query ---
            $select = 'SELECT id, *';
            $from   = ' FROM ' . static::$table;

            // Build WHERE clause
            // Fixed search condition for email and username
            $searchCondition = '(email ILIKE :search_query OR username ILIKE :search_query)';
            // For MySQL/other DBs use: "(LOWER(email) LIKE LOWER(:search_query) OR LOWER(username) LIKE LOWER(:search_query))"

            // Build additional filter conditions
            $filterCondition = '';
            if (!empty($filters)) {
                $filterCondition = '(' . implode(' AND ', $filters) . ')';
            }

            // Combine search and filter conditions
            if (!empty($filterCondition)) {
                $where = ' WHERE ' . $searchCondition . ' AND ' . $filterCondition;
            } else {
                $where = ' WHERE ' . $searchCondition;
            }

            // Build ORDER BY clause
            // Ensure order clauses are strings
            $valid_orders = !empty($order) && is_array($order) ? array_filter($order, 'is_string') : [];
            if (!empty($order) && count($valid_orders) !== count($order)) {
                 // Log warning or handle error if non-strings are found
                 error_log("Warning: Non-string elements found in 'order' option for search.");
                 $valid_orders = array_filter($valid_orders); // Keep only valid strings
            }
            $orderBy = ' ORDER BY ' . (!empty($valid_orders) ? implode(', ', $valid_orders) : 'id DESC');


            // Build LIMIT clause
            $limit = ' LIMIT :limit OFFSET :offset';

            // Assemble the complete query
            $sql = $select . $from . $where . $orderBy . $limit;

            // --- Prepare Parameters ---
            // Start with base parameters for search and pagination
            $params = [
                'search_query' => '%' . $query . '%', // Add wildcards for partial matching
                'limit'        => $perPage,
                'offset'       => $offset
            ];
            // Merge filter parameters (ensure filter_params is an array)
            if (!is_array($filter_params)) {
                 // This validation is already done above, but double-check doesn't hurt
                 throw new \InvalidArgumentException("'params' option must be an array.");
            }
            // Check for placeholder collisions before merging
            $base_param_keys = array_keys($params);
            $filter_param_keys = array_keys($filter_params);
            $collisions = array_intersect($base_param_keys, $filter_param_keys);
            if (!empty($collisions)) {
                throw new \InvalidArgumentException("Placeholder collision detected in 'params': " . implode(', ', $collisions));
            }
            $params = array_merge($params, $filter_params);


            // --- Execute Query ---
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $list = $stmt->fetchAll(\PDO::FETCH_UNIQUE); // Fetch unique for relation loading

        } catch (\PDOException $e) {
            // Log error if necessary
            error_log("Search query failed: " . $e->getMessage() . " SQL: " . $sql);
            return response(false, null, 'Search execution error: ' . $e->getMessage(), $e->getCode());
        } catch (\InvalidArgumentException $e) {
             // Catch specific validation/setup errors
             error_log("Error during search setup: " . $e->getMessage());
             return response(false, null, 'Search setup error: ' . $e->getMessage(), 400);
        } catch (\Throwable $e) {
             // Catch other potential errors during setup
             error_log("Unexpected error during search setup: " . $e->getMessage());
             return response(false, null, 'Search setup error: ' . $e->getMessage(), 500);
        }

        // --- Handle Empty Results ---
        if (empty($list)) {
            return response(true, []); // Successful query, but no results found
        }

        // --- Load Relations ---
        $res = $this->load_relations($list, $with);
        if (!$res['success']) {
            return $res; // Propagate error from relation loading
        }
        $list = $res['data']; // Use the list potentially modified by load_relations

        // --- Format Output ---
        if (!$unique) {
            return response(true, array_values($list)); // Return as a simple array
        } else {
            return response(true, $list); // Return indexed by ID
        }
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
            $perPage    = $options['perPage'] ?? 1000;
            $page       = $options['page'] ?? 1;
            $order      = $options['order'] ?? [];
            $offset     = ($page - 1) * $perPage;
            $filters    = $options['filters'] ?? [];

            $select     = 'SELECT id, *';
            $from       = ' FROM ' . static::$table;
            $joins      = '';
            $where      = (!empty($filters)) ? ' WHERE ' . implode(' AND ', $filters) : '';
            $groupBy    = '';
            $orderBy    = ' ORDER BY ' . (!empty($order) ? implode(', ', $order) : 'id DESC');
            $limit      = ' LIMIT :limit OFFSET :offset';
            
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