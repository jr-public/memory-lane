<?php
// Get entity type from URL parameter, default to 'user'
$entity_type = isset($_GET['type']) ? $_GET['type'] : 'user';

// Validate entity type
$valid_types = ['user', 'client', 'task'];
if (!in_array($entity_type, $valid_types)) {
    $entity_type = 'user';
}

// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Search parameter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get entity data based on type
switch ($entity_type) {
    case 'user':
        $controller = 'User';
        $id_field = 'id';
        $name_field = 'username';
        $date_field = 'created_at';
        $status_field = 'active';
        $columns = ['ID', 'Username', 'Email', 'Client', 'Created', 'Status', 'Actions'];
        break;
    case 'client':
        $controller = 'Client';
        $id_field = 'id';
        $name_field = 'client_name';
        $date_field = 'created_at';
        $status_field = 'active';
        $columns = ['ID', 'Name', 'Email', 'Created', 'Status', 'Actions'];
        break;
    case 'task':
        $controller = 'Task';
        $id_field = 'id';
        $name_field = 'title';
        $date_field = 'created_at';
        $status_field = 'status_id';
        $columns = ['ID', 'Title', 'Assigned To', 'Due Date', 'Status', 'Actions'];
        break;
}

// Fetch data from API
$filter_params = [];
if (!empty($search)) {
    $filter_params[] = "$name_field LIKE '%$search%'";
}

$params = [
    "options" => [
        "limit" => $per_page,
        "offset" => $offset
    ]
];

if (!empty($filter_params)) {
    $params["options"]["filters"] = $filter_params;
}

// Additional options for tasks (e.g., include assignments)
if ($entity_type === 'task') {
    $params["options"]["with"] = ["assignments"];
}

$entities = api_call($controller, "list", $params);
if (!$entities['success']) {
    die("entity list -> entities lsit failed");
}
$entities = $entities['data'];

// Get total count for pagination
$total_count = count($entities);
$total_pages = ceil($total_count / $per_page);

// Function to format date
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

// Function to get status badge class
function getStatusBadgeClass($status, $entity_type) {
    if ($entity_type === 'task') {
        switch ($status) {
            case 1: return 'status-pending'; // Pending
            case 2: return 'status-in-progress'; // In Progress
            case 3: return 'status-active'; // Completed
            case 4: return 'status-inactive'; // On Hold
            default: return 'status-pending';
        }
    } else {
        return $status ? 'status-active' : 'status-inactive';
    }
}

// Function to get status text
function getStatusText($status, $entity_type) {
    if ($entity_type === 'task') {
        switch ($status) {
            case 1: return 'Pending';
            case 2: return 'In Progress';
            case 3: return 'Completed';
            case 4: return 'On Hold';
            default: return 'Unknown';
        }
    } else {
        return $status ? 'Active' : 'Inactive';
    }
}

// Handle delete action
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    api_call($controller, "delete", ["id" => $id_to_delete]);
    die("THERE IS NO DELETE ACTION");
    // Redirect to remove the delete parameter
    header('Location: main.php?action=entity_list&type=' . $entity_type);
    exit;
}
?>


<div class="el-container">
    <div class="el-header">
        <h1><?php echo ucfirst($entity_type); ?> Management</h1>
    </div>
    
    <div class="el-type-tabs">
        <a href="main.php?action=entity_list&type=user" class="el-type-tab <?php echo ($entity_type == 'user') ? 'active' : ''; ?>">
            <span class="el-type-tab-icon">👤</span> Users
        </a>
        <a href="main.php?action=entity_list&type=client" class="el-type-tab <?php echo ($entity_type == 'client') ? 'active' : ''; ?>">
            <span class="el-type-tab-icon">🏢</span> Clients
        </a>
        <a href="main.php?action=entity_list&type=task" class="el-type-tab <?php echo ($entity_type == 'task') ? 'active' : ''; ?>">
            <span class="el-type-tab-icon">📋</span> Tasks
        </a>
    </div>
    
    <div class="el-action-bar">
        <form class="el-search-box" method="get" action="main.php">
            <input type="hidden" name="action" value="entity_list">
            <input type="hidden" name="type" value="<?php echo $entity_type; ?>">
            <input 
                type="text" 
                name="search" 
                placeholder="Search <?php echo ucfirst($entity_type); ?>s..." 
                value="<?php echo htmlspecialchars($search); ?>"
                class="search-box input"
            >
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if (!empty($search)): ?>
                <a href="main.php?action=entity_list&type=<?php echo $entity_type; ?>" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
        
        <a href="main.php?action=entity_create&type=<?php echo $entity_type; ?>" class="btn btn-primary">
            Create <?php echo ucfirst($entity_type); ?>
        </a>
    </div>
    
    <div class="el-table-container">
        <?php if (empty($entities)): ?>
            <div class="el-empty-state">
                <div class="el-empty-icon">📭</div>
                <div class="el-empty-message">No <?php echo ucfirst($entity_type); ?>s found</div>
                <?php if (!empty($search)): ?>
                    <p>Try adjusting your search criteria</p>
                    <a href="main.php?action=entity_list&type=<?php echo $entity_type; ?>" class="btn btn-primary">Clear Search</a>
                <?php else: ?>
                    <a href="main.php?action=entity_create&type=<?php echo $entity_type; ?>" class="btn btn-primary">
                        Create your first <?php echo ucfirst($entity_type); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table class="el-table">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <th><?php echo $column; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entities as $entity): ?>
                        <tr>
                            <td><?php echo $entity[$id_field]; ?></td>
                            <td>
                                <span class="el-entity-name">
                                    <?php echo htmlspecialchars($entity[$name_field]); ?>
                                </span>
                            </td>
                            
                            <?php if ($entity_type == 'user'): ?>
                                <td><?php echo htmlspecialchars($entity['email'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($entity['client_name'] ?? 'N/A'); ?></td>
                                <td><?php echo isset($entity[$date_field]) ? formatDate($entity[$date_field]) : 'N/A'; ?></td>
                            <?php elseif ($entity_type == 'client'): ?>
                                <td><?php echo htmlspecialchars($entity['email'] ?? ''); ?></td>
                                <td><?php echo isset($entity[$date_field]) ? formatDate($entity[$date_field]) : 'N/A'; ?></td>
                            <?php elseif ($entity_type == 'task'): ?>
                                <td>
                                    <?php
                                    if (isset($entity['assignments']) && !empty($entity['assignments'])) {
                                        $assigned_users = array_column($entity['assignments'], 'username');
                                        echo htmlspecialchars(implode(', ', $assigned_users));
                                    } else {
                                        echo 'Unassigned';
                                    }
                                    ?>
                                </td>
                                <td><?php echo isset($entity['due_date']) ? formatDate($entity['due_date']) : 'No deadline'; ?></td>
                            <?php endif; ?>
                            
                            <td></td>
                            
                            <td>
                                <div class="el-actions">
                                    <a href="main.php?action=entity-view&type=<?php echo $entity_type; ?>&id=<?php echo $entity[$id_field]; ?>" class="el-action-btn view" title="View">
                                        👁️
                                    </a>
                                    <a href="main.php?action=entity_edit&type=<?php echo $entity_type; ?>&id=<?php echo $entity[$id_field]; ?>" class="el-action-btn edit" title="Edit">
                                        ✏️
                                    </a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $entity[$id_field]; ?>, '<?php echo addslashes($entity[$name_field]); ?>')" class="el-action-btn delete" title="Delete">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <?php if ($total_pages > 1): ?>
        <div class="el-pagination">
            <?php if ($page > 1): ?>
                <a href="main.php?action=entity_list&type=<?php echo $entity_type; ?>&page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="el-page-link">
                    ⏮️
                </a>
                <a href="main.php?action=entity_list&type=<?php echo $entity_type; ?>&page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="el-page-link">
                    ◀️
                </a>
            <?php endif; ?>
            
            <?php
            // Display a limited range of page numbers
            $range = 2;
            $start_page = max(1, $page - $range);
            $end_page = min($total_pages, $page + $range);
            
            // Always show first page
            if ($start_page > 1) {
                echo '<a href="main.php?action=entity_list&type=' . $entity_type . '&page=1' . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="el-page-link">1</a>';
                if ($start_page > 2) {
                    echo '<span class="el-page-link">...</span>';
                }
            }
            
            // Display page numbers within range
            for ($i = $start_page; $i <= $end_page; $i++) {
                $active_class = ($i == $page) ? 'active' : '';
                echo '<a href="main.php?action=entity_list&type=' . $entity_type . '&page=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="el-page-link ' . $active_class . '">' . $i . '</a>';
            }
            
            // Always show last page
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<span class="el-page-link">...</span>';
                }
                echo '<a href="main.php?action=entity_list&type=' . $entity_type . '&page=' . $total_pages . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="el-page-link">' . $total_pages . '</a>';
            }
            ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="main.php?action=entity_list&type=<?php echo $entity_type; ?>&page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="el-page-link">
                    ▶️
                </a>
                <a href="main.php?action=entity_list&type=<?php echo $entity_type; ?>&page=<?php echo $total_pages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="el-page-link">
                    ⏭️
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Add this hidden form to the bottom of your entity_list.php file -->
<form id="delete-form" method="post" action="<?= $current_url ?>" style="display: none;">
    <input type="hidden" name="entity_name" id="delete-entity-name" value="">
    <input type="hidden" name="entity_action" value="delete">
    <input type="hidden" name="id" id="delete-entity-id" value="">
</form>

<script>
    function confirmDelete(id, name) {
        if (confirm('Are you sure you want to delete ' + name + '?')) {
            // Set the form values
            document.getElementById('delete-entity-name').value = '<?php echo ucfirst($entity_type); ?>';
            document.getElementById('delete-entity-id').value = id;
            
            // Submit the form
            document.getElementById('delete-form').submit();
        }
    }
</script>