<?php
// Get entity type and ID from URL parameters
$entity_type = isset($_GET['type']) ? $_GET['type'] : 'user';
$entity_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// If no ID is provided, redirect to the create entity page
if ($entity_id === 0) {
    header('Location: main.php?action=entity_create&type=' . $entity_type);
    exit;
}

$entity = api_call(ucfirst($entity_type), "get", ["id" => $entity_id]);
if (!$entity['success'] || empty($entity['data'])) {
    header('Location: main.php?action=entity_list&type=' . $entity_type);
    exit;
}
$entity = $entity['data'];

// Entity type specific configurations
$entity_configs = [
    'user' => [
        'title' => 'Edit User',
        'fields' => [
            'username' => ['label' => 'Username', 'type' => 'text', 'required' => true],
            // 'email' => ['label' => 'Email Address', 'type' => 'email', 'required' => true],
            // 'role_id' => ['label' => 'role_id', 'type' => 'hidden', 'required' => true],
        ]
    ],
    'client' => [
        'title' => 'Edit Client',
        'fields' => [
            'client_name' => ['label' => 'Client Name', 'type' => 'text', 'required' => true],
            // 'email' => ['label' => 'Email Address', 'type' => 'email', 'required' => true],
            // Password field is special - only update if provided
            // 'password' => ['label' => 'Password (leave empty to keep current)', 'type' => 'password', 'required' => false]
        ]
    ],
    'task' => [
        'title' => 'Edit Task',
        'fields' => [
            'title' => ['label' => 'Task Title', 'type' => 'text', 'required' => true],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'required' => false],
            // 'status_id' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'options' => [
            //     1 => 'Pending',
            //     2 => 'In Progress',
            //     3 => 'Completed',
            //     4 => 'On Hold'
            // ]],
            // 'user_id' => ['label' => 'Assigned To', 'type' => 'hidden', 'required' => true],
            // 'due_date' => ['label' => 'Due Date', 'type' => 'date', 'required' => false]
        ]
    ]
];

// Get the configuration for the current entity type
$current_config = isset($entity_configs[$entity_type]) ? $entity_configs[$entity_type] : $entity_configs['user'];

// Current URL for form action
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<style></style>

<div class="ee-container">
    <div class="ee-breadcrumb">
        <a href="main.php?action=dashboard">Dashboard</a> &gt; 
        <a href="main.php?action=<?php echo $entity_type; ?>s"><?php echo ucfirst($entity_type); ?>s</a> &gt; 
        Edit <?php echo ucfirst($entity_type); ?>
    </div>
    
    <div class="ee-entity-info">
        <div class="ee-entity-id">ID: <?php echo $entity_id; ?></div>
        <div class="ee-entity-name">
            <?php
            switch($entity_type) {
                case 'user':
                    echo htmlspecialchars($entity['username'] ?? 'Unknown User');
                    break;
                case 'client':
                    echo htmlspecialchars($entity['client_name'] ?? 'Unknown Client');
                    break;
                case 'task':
                    echo htmlspecialchars($entity['title'] ?? 'Unknown Task');
                    break;
                default:
                    echo 'Unknown Entity';
            }
            ?>
        </div>
    </div>
    
    <div class="ce-form-container">
        <div class="ce-form-header">
            <h2><?php echo $current_config['title']; ?></h2>
        </div>
        
        <form method="post" action="<?php echo $current_url; ?>" class="ce-entity-form">
            <input type="hidden" name="entity_name" value="<?php echo $entity_type; ?>">
            <input type="hidden" name="entity_action" value='update'>
            <input type="hidden" name="id" value="<?php echo $entity_id; ?>">
            
            <?php foreach ($current_config['fields'] as $field_name => $field_config): ?>
                <?php 
                // Skip password field if it's an update and password is empty
                if ($field_name === 'password' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                    $field_value = '';
                } else {
                    $field_value = $entity[$field_name] ?? '';
                }
                ?>
                
                <div class="ce-form-group">
                    <label for="<?php echo $field_name; ?>" class="ce-form-label">
                        <?php echo $field_config['label']; ?>
                        <?php if ($field_config['required']): ?>
                            <span class="ce-required-indicator">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field_config['type'] === 'textarea'): ?>
                        <textarea 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            class="ce-form-textarea"
                            <?php echo $field_config['required'] ? 'required' : ''; ?>
                        ><?php echo htmlspecialchars($field_value); ?></textarea>
                    <?php elseif ($field_config['type'] === 'select'): ?>
                        <select 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            class="ce-form-select"
                            <?php echo $field_config['required'] ? 'required' : ''; ?>
                        >
                            <?php foreach ($field_config['options'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo ($field_value == $value) ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($field_config['type'] === 'hidden'): ?>
                        <input 
                            type="hidden" 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            value="<?php echo htmlspecialchars($field_value); ?>"
                        >
                        <div class="ee-field-display">
                            <?php 
                            // Display a readable value for hidden fields (e.g., role name instead of ID)
                            if ($field_name === 'role_id') {
                                $roles = [1 => 'Administrator', 2 => 'Manager', 3 => 'Regular User', 4 => 'Guest'];
                                echo $roles[$field_value] ?? $field_value;
                            } else {
                                echo htmlspecialchars($field_value);
                            }
                            ?>
                        </div>
                    <?php else: ?>
                        <input 
                            type="<?php echo $field_config['type']; ?>" 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            value="<?php echo htmlspecialchars($field_value); ?>" 
                            class="ce-form-input"
                            <?php echo $field_config['required'] ? 'required' : ''; ?>
                        >
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="ce-form-buttons">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='main.php?action=<?php echo $entity_type; ?>s'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update <?php echo ucfirst($entity_type); ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchEntityType(type) {
        // Redirect to the entity list for the selected type
        window.location.href = 'main.php?action=' + type + 's';
    }
    
    // Add form validation if needed
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.ce-entity-form');
        
        form.addEventListener('submit', function(event) {
            // Add any client-side validation here if needed
        });
    });
</script>