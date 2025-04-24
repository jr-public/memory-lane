<?php
// teams.php - Included by main.php

// --- 1. Input Validation and Setup ---
if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    echo '<div class="error-banner">Invalid or missing Project ID (pid).</div>';
    return; // Stop processing if pid is invalid
}
$project_id = (int)$_GET['pid'];
$current_user_id = $user['id']; // $user is available from main.php
$current_client_id = 1; // <<< ADD THIS LINE
$error_message = isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error'])) : null; // Get error from main.php redirect

// --- 2. Fetch Data via API ---

// Fetch Project Details including assignments
$project_res = api_call("Task", "root", [
    'id' => $project_id,
    "options" => [
        "with"      => ["assignments"]
    ]
]);

if (!$project_res['success'] || empty($project_res['data'])) {
    echo '<div class="error-banner">Error fetching project details: ' . htmlspecialchars($project_res['message'] ?? 'Project not found or access denied.') . '</div>';
    return; // Stop if project data fails
}
$project_data = $project_res['data'];

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
        <h2>Assign User</h2> <!-- Changed header -->
        <form id="invite-form" method="post" action="">
             <input type="hidden" name="entity_name" value="TaskAssignment">
             <input type="hidden" name="entity_action" value="invite">
             <input type="hidden" name="task_id" value="<?= $project_id ?>;">
             <input type="hidden" name="creator_id" value="<?= get_auth_user('id') ?>;">
            <div class="invite-form-row">
                <input type="email" name="email" placeholder="Search or enter email address" class="invite-email-input" autocomplete="off">
                <button type="submit" class="btn-action btn-invite" id="assign-user-button">Assign</button>
            </div>            
            <!-- Container for search results -->
            <div id="user-search-results" class="user-search-results"></div>
             <!-- Removed the "not implemented" note -->
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
    /* Existing styles... */
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
        position: relative; /* Needed for absolute positioning of results */
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
        position: relative; /* Context for results */
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
    .btn-invite:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .error-banner {
        background-color: #e74c3c;
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Styles for Search Results */
    .user-search-results {
        position: absolute;
        background-color: #3a3a3a;
        border: 1px solid #555;
        border-top: none;
        border-radius: 0 0 4px 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: calc(100% - 100px); /* Adjust width based on button size */
        left: 0;
        top: 100%; /* Position below the input row */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        display: none; /* Hidden by default */
    }

    .user-search-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #4a4a4a;
        color: #ccc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .user-search-item:last-child {
        border-bottom: none;
    }

    .user-search-item:hover {
        background-color: #4a4a4a;
        color: #fff;
    }
    .user-search-item .email {
        font-size: 0.85em;
        color: #999;
    }
    .user-search-item.loading,
    .user-search-item.no-results {
        padding: 10px;
        text-align: center;
        color: #888;
        cursor: default;
    }
     .user-search-item.no-results:hover {
         background-color: transparent;
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
    const currentClientId = <?= json_encode($current_client_id) ?>; // <<< ADDED

    // --- Debounce Timer ---
    let searchDebounceTimer;

    // --- Helper Functions ---
    function escapeHTML(str) {
        if (!str) return '';
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }

    function getAvatarColor(index) {
        const colors = ['#e74c3c', '#3498db', '#2ecc71', '#f1c40f', '#9b59b6', '#1abc9c', '#e67e22'];
        return colors[index % colors.length];
    }

    // Debounce function
    function debounce(func, delay) {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(func, delay);
    }

    // --- Core Functions ---

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
            // Ensure user exists and has a username, otherwise provide a fallback
            const username = user?.username ? user.username : `User ID: ${assignment.assigned_to}`;
            const initial = username.charAt(0).toUpperCase();

            const clone = template.content.cloneNode(true);
            const item = clone.querySelector('.user-item');
            const avatar = clone.querySelector('.user-avatar');
            const nameSpan = clone.querySelector('.user-name');
            const removeForm = clone.querySelector('.remove-user-form');
            const assignmentIdInput = clone.querySelector('input[name="id"]');

            avatar.textContent = initial;
            avatar.style.backgroundColor = getAvatarColor(index);
            nameSpan.textContent = escapeHTML(username);

            removeForm.action = actionUrl;
            assignmentIdInput.value = assignment.id;

            listContainer.appendChild(clone);
        });
    }

    /**
     * Sets up the invite form, including the search input listener.
     */
    function setupInviteForm(projId, currUserId, actionUrl) {
        const form = document.getElementById('invite-form');
        const searchInput = form.querySelector('.invite-email-input');
        const resultsContainer = document.getElementById('user-search-results');
        const assignButton = document.getElementById('assign-user-button');

        form.action = actionUrl;
        // taskIdInput.value = projId;

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.trim();

            if (searchTerm.length < 2) {
                resultsContainer.innerHTML = '';
                resultsContainer.style.display = 'none';
                return;
            }

            debounce(() => performUserSearch(searchTerm), 300); // Debounce API call
        });

        // Hide results when clicking outside
        document.addEventListener('click', (event) => {
            if (!form.contains(event.target)) {
                resultsContainer.style.display = 'none';
            }
        });

        // Prevent form submission if no user is selected
        form.addEventListener('submit', (event) => {
            if (!selectedUserIdInput.value) {
                event.preventDefault();
                alert('Please select a user from the search results before assigning.');
            }
        });
    }

    /**
     * Performs the user search via API proxy.
     * @param {string} searchTerm - The query string.
     */
    function performUserSearch(searchTerm) {
        const resultsContainer = document.getElementById('user-search-results');
        resultsContainer.innerHTML = '<div class="user-search-item loading">Searching...</div>';
        resultsContainer.style.display = 'block';

        const requestData = {
            controller: "User",
            action: "search",
            params: {
                query: searchTerm,
                options: {
                    filters: ["client_id = :client_id"],
                    params: { "client_id": currentClientId },
                    perPage: 10,
                    page: 1,
                    order: ["username ASC"],
                    unique: false // Get array for easier iteration
                }
            }
        };

        apiProxyRequest(requestData, handleSearchResults, handleSearchError);
    }

    /**
     * Handles the successful response from the user search API call.
     * @param {object} result - The API response object.
     */
    function handleSearchResults(result) {
        const resultsContainer = document.getElementById('user-search-results');
        resultsContainer.innerHTML = ''; // Clear loading/previous results

        if (!result.success) {
            resultsContainer.innerHTML = `<div class="user-search-item no-results">Error: ${escapeHTML(result.message || 'Unknown error')}</div>`;
            resultsContainer.style.display = 'block';
            return;
        }

        const users = result.data; // Should be an array from unique: false

        if (!users || users.length === 0) {
            resultsContainer.innerHTML = '<div class="user-search-item no-results">No users found</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        users.forEach(user => {
            const item = document.createElement('div');
            item.className = 'user-search-item';
            item.dataset.userId = user.id;
            item.dataset.username = user.username;
            item.innerHTML = `
                <span>${escapeHTML(user.username)}</span>
                <span class="email">${escapeHTML(user.email)}</span>
            `;
            item.addEventListener('click', () => selectUser(user));
            resultsContainer.appendChild(item);
        });
        resultsContainer.style.display = 'block';
    }

    /**
     * Handles errors during the user search API call.
     * @param {Error} error - The error object.
     */
    function handleSearchError(error) {
        const resultsContainer = document.getElementById('user-search-results');
        resultsContainer.innerHTML = `<div class="user-search-item no-results">Search failed: ${escapeHTML(error.message)}</div>`;
        resultsContainer.style.display = 'block';
        console.error("Search API Error:", error);
    }

    /**
     * Handles the selection of a user from the search results.
     * @param {object} user - The selected user object.
     */
    function selectUser(user) {
        const searchInput = document.querySelector('.invite-email-input');
        const resultsContainer = document.getElementById('user-search-results');

        searchInput.value = user.email; // Update input field to show selection
        resultsContainer.innerHTML = ''; // Clear results
        resultsContainer.style.display = 'none'; // Hide results
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

        // Render the list of assigned users
        renderAssignedUsers(projectData.assignments, allUsers, currentUrl);

        // Setup the invite/assign form
        setupInviteForm(projectId, currentUserId, currentUrl);
    });

</script>
