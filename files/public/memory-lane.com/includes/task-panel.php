<!-- Task Details Panel -->
<div id="task-panel" class="task-panel">
    <div class="task-panel-overlay"></div>
    <div class="task-panel-container">
        <!-- Panel Header -->
        <div class="task-panel-header">
            <h2 class="task-panel-title">Task Details</h2>
            <button class="task-panel-close" aria-label="Close panel">&times;</button>
        </div>
        
        <!-- Panel Content -->
        <div class="task-panel-content">
            <!-- Task Basic Info Section -->
            <!-- <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Basic Information</h3>
                </div>
                <div class="panel-section-content">
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value status-badge">Not implemented yet</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Priority:</span>
                        <span class="info-value">Not implemented yet</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">Not implemented yet</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Assigned To:</span>
                        <span class="info-value">Not implemented yet</span>
                    </div>
                </div>
            </div> -->
            
            <!-- Task Description Section -->
            <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Description</h3>
                </div>
                <div class="panel-section-content">
                    <p class="placeholder-text">Task description will appear here.</p>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Comments</h3>
                </div>
                <div class="panel-section-content">
                    <!-- Comment List -->
                    <div class="comments-container" id="comments-container">
                        <!-- Comments will be inserted here via JavaScript -->
                    </div>
                    
                    <!-- Add Comment Form -->
                    <div class="comment-form-container">
                        <form id="comment-form" method="post" action="<?php echo $current_url; ?>">
                            <input type="hidden" name="entity_name" value="TaskComment">
                            <input type="hidden" name="entity_action" value="create">
                            <input type="hidden" name="task_id" id="comment-task-id" value="">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            
                            <div class="comment-input-container">
                                <textarea name="text" class="comment-input" placeholder="Add a comment..." required></textarea>
                            </div>
                            
                            <div class="comment-form-actions">
                                <button type="submit" class="btn-comment-submit">Add Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Attachments Section Placeholder -->
            <!-- <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Attachments</h3>
                </div>
                <div class="panel-section-content">
                    <div class="coming-soon">
                        <div class="coming-soon-icon">📎</div>
                        <p>Attachments section coming soon</p>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<script>

// Initialize when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the new TaskPanel
    taskPanel = new TaskPanel();
    
    // Expose the taskPanel to the global scope
    window.taskPanel = taskPanel;
});
</script>