<?php

$entity_types = [
    'User' => [
        'display_name' => 'Users',
        'add_button_text' => 'Add New User'
    ],
    'Client' => [
        'display_name' => 'Clients',
        'add_button_text' => 'Add New Client'
    ]
];


$entity_type = isset($_GET['type']) ? $_GET['type'] : 'User';
if (!array_key_exists($entity_type, $entity_types)) {
    $entity_type = 'User';
}

$current_entity = $entity_types[$entity_type];

if ( !isset(${$entity_type}) ) {
    die("BAD TYPE");
}
try {
    $Instance = ${$entity_type};
	$list = $Instance->list();
} catch (\Throwable $th) {
	die("BAD TYPE2");
}

?>
<div class="entity-container">
    <div class="entity-header">
        <h1><?= $entity_type ?> Management</h1>
        
        <div class="entity-selector">
            <label for="entity-type">Select Entity Type:</label>
            <select id="entity-type" onchange="window.location.href='index.php?action=entity&type='+this.value">
                <?php foreach ($entity_types as $type => $props): ?>
                <option value="<?php echo $type; ?>" <?php echo ($type == $entity_type) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($props['display_name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="action-bar">
        <button class="btn btn-primary"><?php echo htmlspecialchars($current_entity['add_button_text']); ?></button>
        <div class="search-box">
            <input type="text" placeholder="Search <?php echo strtolower($current_entity['display_name']); ?>...">
            <button class="btn btn-secondary">Search</button>
        </div>
    </div>
    
    <?php if (!empty($list)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <?php foreach (array_keys($list[0]) AS $header): ?>
                <th><?= htmlspecialchars($header); ?></th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list AS $entity): ?>
            <tr>
                <?php foreach (array_values($entity) AS $val): ?>
                    <td><?= $val ?></td>
                <?php endforeach; ?>
                <td class="actions">
                    <a href="#" class="btn-icon edit" title="Edit">✏️</a>
                    <a href="#" class="btn-icon view" title="View">👁️</a>
                    <a href="#" class="btn-icon delete" title="Delete">❌</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    
    <div class="pagination">
        <a href="#" class="page-link">Previous</a>
        <a href="#" class="page-link active">1</a>
        <a href="#" class="page-link">2</a>
        <a href="#" class="page-link">3</a>
        <a href="#" class="page-link">Next</a>
    </div>
</div>