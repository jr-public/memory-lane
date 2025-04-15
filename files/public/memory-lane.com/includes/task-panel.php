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
                            <input type="hidden" name="user_id" value="1">
                            
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
	/**
 * Task Panel Module
 * 
 * Handles the display and interaction with the task details panel
 */

// TaskPanel class to manage the task details panel
class TaskPanel {
    constructor() {
        this.panel = document.getElementById('task-panel');
        this.title = document.querySelector('.task-panel-title');
        this.closeBtn = document.querySelector('.task-panel-close');
        this.overlay = document.querySelector('.task-panel-overlay');
        this.currentTaskId = null;
        
        this.init();
    }
    
    init() {
        if (!this.panel) {
            console.error('Task panel element not found');
            return;
        }
        
        // Set up event listeners
        this.closeBtn.addEventListener('click', () => this.close());
        this.overlay.addEventListener('click', () => this.close());
        
        // Close panel on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });
    }
    
    /**
     * Open the panel with task details
     * 
     * @param {Object} task - The task object containing details to display
     */
    open(task) {
        if (!task || !task.id) {
            console.error('Invalid task data provided to panel');
            return;
        }
        
        // Store the current task ID
        this.currentTaskId = task.id;
        
        // Update panel title with task title
        this.title.textContent = task.title || 'Task Details';
        
        // Update basic task info if available
        this.updateBasicInfo(task);
        
        // Update comments section
        this.updateComments(task);
        
        // Add the active class to show the panel
        this.panel.classList.add('active');
        
        // Set focus to the panel for accessibility
        this.closeBtn.focus();
        
        // Prevent body scrolling while panel is open
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Close the panel
     */
    close() {
        this.panel.classList.remove('active');
        this.currentTaskId = null;
        
        // Restore body scrolling
        document.body.style.overflow = '';
    }
    
    /**
     * Check if the panel is currently open
     * 
     * @returns {Boolean} True if the panel is open
     */
    isOpen() {
        return this.panel.classList.contains('active');
    }
    
    /**
     * Update the basic information section with task data
     * 
     * @param {Object} task - The task object containing details to display
     */
    updateBasicInfo(task) {
        // This method will be expanded later to display actual task info
        // For now, it's just a placeholder
        
        // Example of how we'll update status when implemented
        // const statusBadge = this.panel.querySelector('.status-badge');
        // if (statusBadge && task.status) {
        //     statusBadge.textContent = task.status;
        //     statusBadge.style.backgroundColor = getStatusColor(task.status);
        // }
    }
    updateComments(task) {
        const commentsContainer = this.panel.querySelector('#comments-container');
        const commentTaskIdInput = this.panel.querySelector('#comment-task-id');
        
        if (!commentsContainer || !commentTaskIdInput) {
            return;
        }
        
        // Set the task ID for the comment form
        commentTaskIdInput.value = task.id;
        
        // Clear existing comments
        commentsContainer.innerHTML = '';
        
        // Check if task has comments
        if (!task.comments || task.comments.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'comments-empty-state';
            emptyState.innerHTML = `
                <div class="comments-empty-icon">💬</div>
                <p>No comments yet</p>
            `;
            commentsContainer.appendChild(emptyState);
            return;
        }
        
        // Sort comments by date (newest first)
        const sortedComments = [...task.comments].sort((a, b) => {
            return new Date(b.created_at) - new Date(a.created_at);
        });
        
        // Render each comment
        sortedComments.forEach(comment => {
            const commentElement = this.createCommentElement(comment);
            commentsContainer.appendChild(commentElement);
        });
    }

    /**
     * Create a comment element
     * 
     * @param {Object} comment - The comment data
     * @returns {HTMLElement} - The comment element
     */
    createCommentElement(comment) {
        // Create the comment container
        const commentElement = document.createElement('div');
        commentElement.className = 'comment-item';
        commentElement.dataset.commentId = comment.id;
        
        // Get user data
        const users = (typeof tasked_users === 'undefined') ? {} : tasked_users;
        const user = users[comment.user_id] || { username: 'User' };
        const username = user.username || 'Unknown User';
        const initial = username.charAt(0).toUpperCase();
        
        // Format date
        const commentDate = new Date(comment.created_at);
        const dateOptions = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formattedDate = commentDate.toLocaleDateString('en-US', dateOptions);
        
        // Build comment HTML
        commentElement.innerHTML = `
            <div class="comment-header">
                <div class="comment-user">
                    <div class="comment-avatar" style="background-color: ${this.getAvatarColor(comment.user_id)}">
                        ${initial}
                    </div>
                    <div class="comment-author">${username}</div>
                </div>
                <div class="comment-date">${formattedDate}</div>
            </div>
            <div class="comment-content">${comment.text}</div>
        `;
        
        return commentElement;
    }

    /**
     * Get a color for user avatar based on user ID
     * 
     * @param {number} userId - The user ID
     * @returns {string} - CSS color value
     */
    getAvatarColor(userId) {
        const colors = [
            '#3498db', // Blue
            '#9b59b6', // Purple
            '#e74c3c', // Red
            '#2ecc71', // Green
            '#f39c12', // Orange
            '#1abc9c', // Teal
            '#d35400'  // Dark Orange
        ];
        return colors[userId % colors.length];
    }
}

// Initialize the task panel when the DOM is loaded
let taskPanel;
document.addEventListener('DOMContentLoaded', function() {
    taskPanel = new TaskPanel();
    
    // Expose the taskPanel to the global scope for use by other scripts
    window.taskPanel = taskPanel;
});
</script>
<style>
	/* Task Panel Styles */
.task-panel {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 100%;
    z-index: 1000;
    display: flex;
    visibility: hidden;
    pointer-events: none;
}

.task-panel.active {
    visibility: visible;
    pointer-events: auto;
}

/* Overlay background */
.task-panel-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.task-panel.active .task-panel-overlay {
    opacity: 1;
}

/* Panel container */
.task-panel-container {
    background-color: #2a2a2a;
    height: 100%;
    width: 100%;
    max-width: 500px;
    margin-left: auto;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.task-panel.active .task-panel-container {
    transform: translateX(0);
}

/* Panel header */
.task-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #444;
    background-color: #222;
}

.task-panel-title {
    margin: 0;
    font-size: 1.5rem;
    color: #e0e0e0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.task-panel-close {
    background: none;
    border: none;
    color: #e0e0e0;
    font-size: 1.5rem;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s ease;
}

.task-panel-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Panel content */
.task-panel-content {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

/* Panel sections */
.panel-section {
    background-color: #333;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.panel-section-header {
    padding: 1rem;
    border-bottom: 1px solid #444;
}

.panel-section-header h3 {
    margin: 0;
    color: #3498db;
    font-size: 1.1rem;
}

.panel-section-content {
    padding: 1rem;
}

/* Info items */
.info-item {
    display: flex;
    margin-bottom: 0.75rem;
}

.info-label {
    color: #909090;
    width: 100px;
    flex-shrink: 0;
}

.info-value {
    color: #e0e0e0;
    flex: 1;
}

/* Status badge */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    background-color: #3498db;
    color: #121212;
}

/* Coming soon placeholder */
.coming-soon {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
    color: #909090;
    text-align: center;
}

.coming-soon-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    opacity: 0.7;
}

.placeholder-text {
    color: #909090;
    font-style: italic;
}

/* Responsive styles */
@media (max-width: 768px) {
    .task-panel-container {
        max-width: 100%;
    }
}
/* Comments section styles */
.comments-container {
    margin-bottom: 1.5rem;
    max-height: 350px;
    overflow-y: auto;
}

/* Empty state for comments */
.comments-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 0;
    color: #909090;
    text-align: center;
}

.comments-empty-icon {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    opacity: 0.7;
}

/* Individual comment styles */
.comment-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #444;
}

.comment-item:last-child {
    border-bottom: none;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.comment-user {
    display: flex;
    align-items: center;
}

.comment-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background-color: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    margin-right: 0.5rem;
}

.comment-author {
    font-weight: 500;
    color: #e0e0e0;
}

.comment-date {
    font-size: 0.8rem;
    color: #909090;
}

.comment-content {
    color: #e0e0e0;
    line-height: 1.5;
    white-space: pre-wrap;
    word-break: break-word;
}

/* Comment form styles */
.comment-form-container {
    margin-top: 1rem;
    border-top: 1px solid #444;
    padding-top: 1rem;
}

.comment-input-container {
    margin-bottom: 0.75rem;
}

.comment-input {
    width: 100%;
    min-height: 80px;
    padding: 0.75rem;
    border-radius: 4px;
    border: 1px solid #444;
    background-color: #242424;
    color: #e0e0e0;
    resize: vertical;
    font-family: inherit;
    transition: border-color 0.2s ease;
}

.comment-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.comment-form-actions {
    display: flex;
    justify-content: flex-end;
}

.btn-comment-submit {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 0.6rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.btn-comment-submit:hover {
    background-color: #2980b9;
    transform: translateY(-1px);
}

.btn-comment-submit:active {
    transform: translateY(1px);
}
</style>