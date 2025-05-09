<?php
// Get entity type from URL parameter, default to 'user'
$entity_type = isset($_GET['type']) ? $_GET['type'] : 'user';

$clients = api_call("Client","list");
if ( !$clients['success'] ) {
    die('entity create -> client list failed');
}
$clients = array_combine(array_column($clients['data'], 'id'), array_column($clients['data'], 'client_name'));
// Entity type specific configurations
$entity_configs = [
    'user' => [
        'title' => 'Create New User',
        'fields' => [
            'username' => ['label' => 'Username', 'type' => 'text', 'required' => true, 'value' => 'username'],
            'email' => ['label' => 'Email Address', 'type' => 'email', 'required' => true, 'value' => 'email@email.com'],
            'password' => ['label' => 'Password', 'type' => 'password', 'required' => true, 'value' => '1234'],
            // 'confirm_password' => ['label' => 'Confirm Password', 'type' => 'password', 'required' => true, 'value' => '1234'],
            'client_id' => ['label' => 'Client', 'type' => 'select', 'required' => true, 'options' => $clients],
            'role_id' => ['label' => 'role_id', 'type' => 'hidden', 'required' => true, 'value' => '1'],
            // 'role_id' => ['label' => 'Role', 'type' => 'select', 'required' => true, 'options' => [
            //     1 => 'Administrator',
            //     2 => 'Manager',
            //     3 => 'Regular User',
            //     4 => 'Guest'
            // ]]
        ]
    ],
    'client' => [
        'title' => 'Create New Client',
        'fields' => [
            'client_name' => ['label' => 'Client Name', 'type' => 'text', 'required' => true, 'value' => 'test'],
            'email' => ['label' => 'Email Address', 'type' => 'email', 'required' => true, 'value' => 'email@email.com'],
            'password' => ['label' => 'Password', 'type' => 'password', 'required' => true, 'value' => '1234'],
        ]
    ],
    'task' => [
        'title' => 'Create New Task',
        'fields' => [
            'title' => ['label' => 'Task Title', 'type' => 'text', 'required' => true, 'value' => 'task title'],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'required' => false],
            'status_id' => ['label' => 'Status', 'type' => 'hidden', 'required' => true, 'value' => 1],
            'user_id' => ['label' => 'user id', 'type' => 'hidden', 'required' => true, 'value' => $user['id']],
            'due_date' => ['label' => 'Due Date', 'type' => 'date', 'required' => false, 'value' => '2030-05-05']
        ]
    ]
];

// Get the configuration for the current entity type
$current_config = isset($entity_configs[$entity_type]) ? $entity_configs[$entity_type] : $entity_configs['user'];
?>

<style></style>

<div class="ce-container">
    <div class="entity-header">
        <h1>Entity Management</h1>
        
        <div class="entity-selector">
            <label for="entity-type">Select Entity Type:</label>
            <select id="entity-type" onchange="window.location.href='main.php?action=entity_create&type='+this.value">
                <option value="user" <?php echo ($entity_type == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="client" <?php echo ($entity_type == 'client') ? 'selected' : ''; ?>>Client</option>
                <option value="task" <?php echo ($entity_type == 'task') ? 'selected' : ''; ?>>Task</option>
            </select>
        </div>
    </div>
    
    <div class="ce-type-selector">
        <a href="main.php?action=entity_create&type=user" class="ce-type-option <?php echo ($entity_type == 'user') ? 'active' : ''; ?>">User</a>
        <a href="main.php?action=entity_create&type=client" class="ce-type-option <?php echo ($entity_type == 'client') ? 'active' : ''; ?>">Client</a>
        <a href="main.php?action=entity_create&type=task" class="ce-type-option <?php echo ($entity_type == 'task') ? 'active' : ''; ?>">Task</a>
    </div>
    
    <div class="ce-form-container">
        <div class="ce-form-header">
            <h2><?php echo $current_config['title']; ?></h2>
        </div>
        
        <form method="post" action="<?= $current_url ?>" class="ce-entity-form">
            <input type='hidden' name='entity_name' value='<?= ucfirst($entity_type) ?>'>
            <input type='hidden' name='entity_action' value='create'>
            <?php foreach ($current_config['fields'] as $field_name => $field_config): ?>
                <div class="ce-form-group">
                    <label for="<?php echo $field_name; ?>" class="ce-form-label">
                        <?php echo $field_config['label']; ?>
                        <?php if ($field_config['required']): ?>
                            <span class="ce-required-indicator">*</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($field_config['type'] == 'textarea'): ?>
                        <textarea 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            class="ce-form-textarea"
                            <?php echo $field_config['required'] ? 'required' : ''; ?>
                        ></textarea>
                    <?php elseif ($field_config['type'] == 'select'): ?>
                        <select 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            class="ce-form-select"
                            <?php echo $field_config['required'] ? 'required' : ''; ?>
                        >
                            <?php foreach ($field_config['options'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input 
                            value="<?php echo $field_config['value'] ?? '' ?>"
                            type="<?php echo $field_config['type']; ?>" 
                            name="<?php echo $field_name; ?>" 
                            id="<?php echo $field_name; ?>" 
                            class="ce-form-input"
                            <?php echo $field_config['required'] ? 'required' : ''; ?>
                        >
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="ce-form-buttons">
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
                <button type="submit" class="btn btn-primary">Create <?php echo ucfirst($entity_type); ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     // This is just a skeleton form, no actual functionality
    //     const form = document.querySelector('.ce-entity-form');
        
    //     form.addEventListener('submit', function(event) {
    //         // Prevent actual form submission
    //         event.preventDefault();
            
    //         // Show success message
    //         alert('This is just a demo form. No actual data will be saved.');
            
    //         // Optionally, you could redirect back to the entity list
    //         // window.location.href = 'main.php?action=entity&type=<?php echo $entity_type; ?>';
    //     });
    // });
</script>