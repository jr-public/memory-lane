<?php
// teams.php - Included by main.php

// --- 1. Input Validation and Setup ---
if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    echo '<div class="error-banner">Invalid or missing Project ID (pid).</div>';
    return; // Stop processing if pid is invalid
}
$project_id = (int)$_GET['pid'];
$current_user_id = $user['id']; // $user is available from main.php
$error_message = isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error'])) : null; // Get error from main.php redirect

// --- 2. Fetch Data via API ---

// Fetch Project Details including assignments
$project_res = api_call("Task", "list", [
    "options" => [
		'filters' => [ 'id = ' . $project_id ],
		'perPage'   => 1,
		'page'      => 1,
        "with" => ["assignments"] // Ensure assignments are loaded
    ]
]);

if (!$project_res['success'] || empty($project_res['data'])) {
    echo '<div class="error-banner">Error fetching project details: ' . htmlspecialchars($project_res['message'] ?? 'Project not found or access denied.') . '</div>';
    return; // Stop if project data fails
}
$project_data = $project_res['data'][0];

// Fetch All Users for mapping IDs to names/details
$users_res = api_call("User", "list", [
    "options" => ["unique" => true] // Fetch as ID => User map
]);

if (!$users_res['success']) {
    // Handle gracefully - maybe show IDs or a generic error
    echo '<div class="error-banner">Warning: Could not fetch user list. Usernames may not display correctly.</div>';
    $all_users = []; // Use an empty array to avoid JS errors
} else {
    $all_users = $users_res['data'];
}

// --- 3. Prepare Data for JavaScript ---
// $current_url is available from main.php
?>

<!-- HTML Structure -->
<div class="team-container">
    <h1>Team for: <span id="project-title"><?= htmlspecialchars($project_data['title'] ?? 'Project') ?></span></h1>

    <!-- Error Message Display -->
    <div id="error-message-area" class="error-banner" style="<?= $error_message ? '' : 'display: none;' ?>">
        <?= $error_message ?>
    </div>

	
    <!-- Invite User Section -->
    <div class="team-section">
        <h2>Invite User</h2>
        <form id="invite-form" method="post" action="">
             <input type="hidden" name="entity_name" value="TaskAssignment">
             <!-- Using a distinct action name for clarity, even if main.php doesn't use it yet -->
             <input type="hidden" name="entity_action" value="create_assignment_invite">
             <input type="hidden" name="task_id" value=""> <!-- Project ID set by JS -->
             <input type="hidden" name="user_id" value=""> <!-- Current User ID set by JS -->

            <div class="invite-form-row">
                <input type="email" name="invite_email" required placeholder="Enter user's email to invite" class="invite-email-input">
                <button type="submit" class="btn-action btn-invite">Invite</button>
            </div>
             <p class="invite-note">Note: Invite functionality via email is not yet implemented. This form currently does nothing.</p>
        </form>
    </div>
	

    <!-- Separator -->
    <hr class="team-separator">

	
    <!-- Assigned Users List Section -->
    <div class="team-section">
        <h2>Current Team</h2>
        <div id="assigned-users-list" class="user-list">
            <!-- User items will be populated here by JS -->
        </div>
        <p id="empty-team-message" style="display: none; color: #888; margin-top: 1rem;">No users are currently assigned to this project.</p>

        <!-- Template for a single user item -->
        <template id="user-item-template">
            <div class="user-item">
                <div class="user-info">
                    <div class="user-avatar"></div>
                    <span class="user-name"></span>
                </div>
                <div class="user-actions">
                    <form class="remove-user-form" method="post" action="">
                        <input type="hidden" name="entity_name" value="TaskAssignment">
                        <input type="hidden" name="entity_action" value="delete">
                        <input type="hidden" name="id" value=""> <!-- Assignment ID set by JS -->
                        <button type="submit" class="btn-remove" title="Remove User">×</button>
                    </form>
                </div>
            </div>
        </template>
    </div>

</div>

<!-- CSS Styling -->
<style>
    .team-container {
        padding: 20px;
        max-width: 800px;
        margin: 20px auto;
        background-color: #2c2c2c;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .team-container h1 {
        border-bottom: 1px solid #444;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 1.8em;
        color: #eee;
    }

    .team-section {
        margin-bottom: 30px;
    }

    .team-section h2 {
        color: #bbb;
        margin-bottom: 15px;
        font-size: 1.3em;
    }

    .user-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .user-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #383838;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.2s ease;
    }

    .user-item:hover {
        background-color: #444;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: bold;
        flex-shrink: 0;
        background-color: #555; /* Default */
    }

    .user-name {
        font-weight: 500;
        color: #ddd;
    }

    .user-actions .btn-remove {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.2s ease;
        line-height: 1; /* Ensure 'x' is centered */
    }

    .user-actions .btn-remove:hover {
        background-color: #c0392b;
    }

    .team-separator {
        border: none;
        border-top: 1px solid #444;
        margin: 30px 0;
    }

    .invite-form-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .invite-email-input {
        flex-grow: 1;
        padding: 8px 12px;
        border: 1px solid #555;
        background-color: #222;
        color: #eee;
        border-radius: 4px;
    }

    .btn-invite {
        padding: 8px 15px;
    }

    .invite-note {
        font-size: 0.85em;
        color: #888;
        margin-top: 10px;
    }

    .error-banner {
        background-color: #e74c3c;
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

<!-- JavaScript Logic -->
<script>
    // --- Get Data from PHP ---
    const projectData = <?= json_encode($project_data) ?>;
    const allUsers = <?= json_encode($all_users) ?>;
    const currentUrl = <?= json_encode($current_url) ?>; // Provided by main.php
    const errorMessage = <?= json_encode($error_message) ?>;
    const projectId = <?= json_encode($project_id) ?>;
    const currentUserId = <?= json_encode($current_user_id) ?>;

    // --- Helper Functions ---
    function escapeHTML(str) {
        if (!str) return '';
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }

    // Basic avatar color function (can be replaced with a more sophisticated one if needed)
    function getAvatarColor(index) {
        const colors = ['#e74c3c', '#3498db', '#2ecc71', '#f1c40f', '#9b59b6', '#1abc9c', '#e67e22'];
        return colors[index % colors.length];
    }

    // --- Core Functions ---

    /**
     * Renders the list of assigned users.
     * @param {Array} assignments - Array of assignment objects from projectData.
     * @param {Object} usersMap - Map of user IDs to user objects.
     * @param {string} actionUrl - The URL for form submissions.
     */
    function renderAssignedUsers(assignments = [], usersMap = {}, actionUrl) {
        const listContainer = document.getElementById('assigned-users-list');
        const template = document.getElementById('user-item-template');
        const emptyMessage = document.getElementById('empty-team-message');

        listContainer.innerHTML = ''; // Clear previous items

        if (!assignments || assignments.length === 0) {
            emptyMessage.style.display = 'block';
            return;
        }

        emptyMessage.style.display = 'none';

        assignments.forEach((assignment, index) => {
            const user = usersMap[assignment.assigned_to];
            const username = user ? user.username : `User ID: ${assignment.assigned_to}`;
            const initial = username.charAt(0).toUpperCase();

            const clone = template.content.cloneNode(true);
            const item = clone.querySelector('.user-item');
            const avatar = clone.querySelector('.user-avatar');
            const nameSpan = clone.querySelector('.user-name');
            const removeForm = clone.querySelector('.remove-user-form');
            const assignmentIdInput = clone.querySelector('input[name="id"]');

            avatar.textContent = initial;
            avatar.style.backgroundColor = getAvatarColor(index); // Use index for color variation
            nameSpan.textContent = escapeHTML(username);

            removeForm.action = actionUrl;
            assignmentIdInput.value = assignment.id;

            listContainer.appendChild(clone);
        });
    }

    /**
     * Sets up the invite form with necessary hidden values and action URL.
     * @param {number} projId - The current project ID.
     * @param {number} currUserId - The ID of the logged-in user.
     * @param {string} actionUrl - The URL for form submission.
     */
    function setupInviteForm(projId, currUserId, actionUrl) {
        const form = document.getElementById('invite-form');
        const taskIdInput = form.querySelector('input[name="task_id"]');
        const userIdInput = form.querySelector('input[name="user_id"]');

        form.action = actionUrl;
        taskIdInput.value = projId;
        userIdInput.value = currUserId;

        // Optional: Add submit prevention if needed later for AJAX
        // form.addEventListener('submit', (event) => {
        //     // event.preventDefault(); // Uncomment for AJAX submission
        //     console.log('Invite form submitted (currently does nothing).');
        //     // Implement AJAX call here in the future
        // });
    }

    // --- Initialization ---
    document.addEventListener('DOMContentLoaded', () => {
        // Display error message if present
        const errorArea = document.getElementById('error-message-area');
        if (errorMessage) {
            errorArea.textContent = errorMessage;
            errorArea.style.display = 'block';
        } else {
            errorArea.style.display = 'none';
        }

        // Set project title (already done in PHP, but good practice if dynamic)
        // document.getElementById('project-title').textContent = escapeHTML(projectData.title);

        // Render the list of assigned users
        renderAssignedUsers(projectData.assignments, allUsers, currentUrl);

        // Setup the invite form
        setupInviteForm(projectId, currentUserId, currentUrl);
    });

</script>
