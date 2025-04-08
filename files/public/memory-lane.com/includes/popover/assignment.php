<?php
/**
 * Assignment Popover Template
 * 
 * This file creates the HTML structure for the assignment popover
 * that appears when clicking on task assignments.
 */
?>

<div id="assignment_popover">
    <div class="assignment-popover-content">
        <!-- Popover Header -->
        <div class="assignment-popover-header">
            <h3>Assignments for: Task Title</h3>
        </div>
        
        <!-- Assignment List Container -->
        <div class="assignment-popover-list">
            <!-- Empty State Message (when no assignments) -->
            <div class="assignment-empty">
                <div class="empty-icon">👥</div>
                <div class="empty-text">No users assigned to this task</div>
            </div>
            
            <!-- Note: In the actual implementation, assigned users would be listed here 
                 when they exist, but are not shown for the empty state -->
        </div>
        
        <!-- Separator -->
        <div class="assignment-separator"></div>
        
        <!-- Assignment Form -->
        <form id="assignment-form" class="assignment-form" action="" method="post">
            <input type="hidden" name="entity_name" value="TaskAssignment">
            <input type="hidden" name="entity_action" value="create">
            <input type="hidden" name="task_id" value="1"> <!-- Hardcoded task ID for example -->
            <input type="hidden" name="user_id" value="1">
            <div class="form-row">
                <select id="user-select" name="assigned_to" class="user-select" required>
                    <option value="">Select a user</option>
                    <option value="1">Admin User</option>
                    <option value="2">John Doe</option>
                    <option value="3">Jane Smith</option>
                    <option value="4">Alex Johnson</option>
                    <option value="5">Taylor Brown</option>
                </select>
                <button type="submit" class="btn-add-assignment">Add</button>
            </div>
        </form>
    </div>
</div>