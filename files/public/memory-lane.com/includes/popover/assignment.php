<?php
/**
 * Assignment Popover Template
 * 
 * This file creates the HTML structure for the assignment popover
 * that appears when clicking on task assignments.
 * It includes data attributes for dynamic content insertion.
 */
?>
<style>
    /* Additional CSS styles to fix assignment item layout */
    .assignment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        border-bottom: 1px solid #444;
        transition: all 0.2s ease;
    }

    .assignment-main-content {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .assignment-actions {
        display: flex;
        align-items: center;
    }

    /* Fix for delete button */
    .assignment-delete-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-left: 10px;
    }

    /* Make sure the avatar is displayed correctly */
    .assignment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: bold;
        margin-right: 12px;
        flex-shrink: 0;
    }
</style>
<?php
/**
 * Assignment Popover Template
 * 
 * This file creates the HTML structure for the assignment popover
 * that appears when clicking on task assignments.
 * It includes data attributes for dynamic content insertion.
 */
?>

<div id="assignment_popover" style="display:none;">
    <div class="assignment-popover-content">
        <!-- Popover Header -->
        <div class="assignment-popover-header">
            <h3 class="task-title-placeholder">Assignments for: Task Title</h3>
        </div>
        
        <!-- Assignment List Container -->
        <div class="assignment-popover-list">
            <!-- Empty State Message (shown when no assignments) -->
            <div class="assignment-empty">
                <div class="empty-icon">👥</div>
                <div class="empty-text">No users assigned to this task</div>
            </div>
            
            <!-- Assignment List (populated by JavaScript) -->
            <div class="assignment-items-container" style="display:none;">
                <!-- Template for a single assignment item -->
                <div class="assignment-item-template" style="display:none;">
                    <div class="assignment-main-content">
                        <div class="assignment-avatar"></div>
                        <div class="assignment-details">
                            <div class="assignment-name"></div>
                            <div class="assignment-role"></div>
                        </div>
                    </div>
                    <div class="assignment-actions">
                        <form class="assignment-delete-form" action="" method="post">
                            <input type="hidden" name="entity_name" value="TaskAssignment">
                            <input type="hidden" name="entity_action" value="delete">
                            <input type="hidden" name="id" value="" class="assignment-id-input">
                            <button type="submit" class="assignment-delete-btn" title="Remove Assignment">×</button>
                        </form>
                    </div>
                </div>
                <!-- Actual assignment items will be cloned from the template and inserted here -->
            </div>
        </div>
        
        <!-- Separator -->
        <div class="assignment-separator"></div>
        
        <!-- Assignment Form -->
        <form id="assignment-form" class="assignment-form" action="" method="post">
            <input type="hidden" name="entity_name" value="TaskAssignment">
            <input type="hidden" name="entity_action" value="create">
            <input type="hidden" name="task_id" value="" class="task-id-input"> <!-- Will be set by JS -->
            <input type="hidden" name="user_id" value="1">
            <div class="form-row">
                <select id="user-select" name="assigned_to" class="user-select" required>
                    <option value="">Select a user</option>
                    <!-- User options will be populated by JavaScript -->
                </select>
                <button type="submit" class="btn-add-assignment">Add</button>
            </div>
        </form>
    </div>
</div>