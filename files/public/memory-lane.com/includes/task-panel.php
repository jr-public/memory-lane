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
 * Task Panel Enhancements
 * 
 * This script adds the following features to the task panel:
 * 1. Editable description with save button
 * 2. Edit button for existing descriptions
 * 3. Edit and delete buttons for comments
 */

// Enhanced TaskPanel class to handle description editing
class TaskPanel {
    constructor() {
        this.panel = document.getElementById('task-panel');
        this.title = document.querySelector('.task-panel-title');
        this.closeBtn = document.querySelector('.task-panel-close');
        this.overlay = document.querySelector('.task-panel-overlay');
        this.descriptionContainer = this.panel.querySelector('.panel-section:nth-child(1) .panel-section-content');
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
    // Add to the TaskPanel class

    // Modify the open method to make the title editable
    open(task) {
        if (!task || !task.id) {
            console.error('Invalid task data provided to panel');
            return;
        }
        
        // Store the current task ID
        this.currentTaskId = task.id;
        
        // Update panel title with task title and add edit button
        this.updateTitleDisplay(task);
        
        // Update basic task info
        this.updateBasicInfo(task);
        
        // Update description section
        this.updateDescription(task);
        
        // Update comments section
        this.updateComments(task);
        
        // Add the active class to show the panel
        this.panel.classList.add('active');
        
        // Prevent body scrolling while panel is open
        document.body.style.overflow = 'hidden';
    }

    // Create a new method to handle updating the title display
    updateTitleDisplay(task) {
        // Clear previous title content
        this.title.innerHTML = '';
        
        // Create title text element
        const titleText = document.createElement('span');
        titleText.className = 'task-title-text';
        titleText.textContent = task.title || 'Untitled Task';
        
        // Create edit button
        const editButton = document.createElement('button');
        editButton.className = 'btn-edit-title';
        editButton.innerHTML = '<span class="edit-icon">✎</span>';
        editButton.title = 'Edit title';
        editButton.addEventListener('click', () => this.showTitleEditor(task));
        
        // Add elements to title container
        this.title.appendChild(titleText);
        this.title.appendChild(editButton);
    }

    // Create a new method to show the title editor
    showTitleEditor(task) {
        // Clear the title container
        this.title.innerHTML = '';
        
        // Create input field
        const titleInput = document.createElement('input');
        titleInput.type = 'text';
        titleInput.className = 'title-edit-input';
        titleInput.value = task.title || '';
        titleInput.placeholder = 'Enter task title...';
        
        // Create save button
        const saveButton = document.createElement('button');
        saveButton.className = 'btn-save-title';
        saveButton.innerHTML = '✓';
        saveButton.title = 'Save title';
        saveButton.addEventListener('click', () => this.saveTitle(task.id, titleInput.value));
        
        // Create cancel button
        const cancelButton = document.createElement('button');
        cancelButton.className = 'btn-cancel-title';
        cancelButton.innerHTML = '✕';
        cancelButton.title = 'Cancel';
        cancelButton.addEventListener('click', () => this.updateTitleDisplay(task_list[task.id]));
        
        // Add elements to title container
        this.title.appendChild(titleInput);
        this.title.appendChild(saveButton);
        this.title.appendChild(cancelButton);
        
        // Focus the input field
        titleInput.focus();
        
        // Add key event listeners
        titleInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.saveTitle(task.id, titleInput.value);
            } else if (e.key === 'Escape') {
                this.updateTitleDisplay(task_list[task.id]);
            }
        });
    }

    // Create a new method to save the title
    saveTitle(taskId, title) {
        if (!title.trim()) {
            alert('Task title cannot be empty');
            return;
        }
        
        // Update local data first
        if (task_list[taskId]) {
            task_list[taskId].title = title;
        }
        
        // Make API request to update the title
        apiProxyRequest(
            {
                controller: 'Task',
                action: 'update',
                params: {
                    id: taskId,
                    data: {
                        title: title
                    }
                }
            },
            (result) => {
                if (result.success) {
                    // Update the title display
                    this.updateTitleDisplay(task_list[taskId]);
                    
                    // Update the task in the list display
                    const taskNameElement = document.querySelector(`.task-item[data-task-id="${taskId}"] .task-name`);
                    if (taskNameElement) {
                        taskNameElement.textContent = title;
                    }
                } else {
                    alert('Failed to save title: ' + (result.message || 'Unknown error'));
                }
            },
            (error) => {
                console.error('Error saving title:', error);
                alert('Failed to save title. Please try again.');
            }
        );
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
        // Placeholder for basic info updates
    }

    /**
     * Update the description section with task data
     * 
     * @param {Object} task - The task object containing details to display
     */
    updateDescription(task) {
        if (!this.descriptionContainer) {
            console.error('Description container not found');
            return;
        }
        
        this.descriptionContainer.innerHTML = '';
        
        if (task.description) {
            // Create description display with edit button
            const descriptionDisplay = document.createElement('div');
            descriptionDisplay.className = 'task-description-display';
            
            const descriptionText = document.createElement('p');
            descriptionText.className = 'task-description-text';
            descriptionText.textContent = task.description;
            
            const editButton = document.createElement('button');
            editButton.className = 'btn-edit-description';
            editButton.innerHTML = '<span class="edit-icon">✎</span> Edit';
            editButton.addEventListener('click', () => this.showDescriptionEditor(task));
            
            descriptionDisplay.appendChild(descriptionText);
            descriptionDisplay.appendChild(editButton);
            this.descriptionContainer.appendChild(descriptionDisplay);
        } else {
            // Create description editor for empty description
            this.showDescriptionEditor(task);
        }
    }
    
    /**
     * Show the description editor
     * 
     * @param {Object} task - The task object
     */
    showDescriptionEditor(task) {
        this.descriptionContainer.innerHTML = '';
        
        const editorContainer = document.createElement('div');
        editorContainer.className = 'task-description-editor';
        
        const textarea = document.createElement('textarea');
        textarea.className = 'description-textarea';
        textarea.value = task.description || '';
        textarea.placeholder = 'Add a description for this task...';
        
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'description-buttons';
        
        const saveButton = document.createElement('button');
        saveButton.className = 'btn-save-description';
        saveButton.textContent = 'Save';
        saveButton.addEventListener('click', () => this.saveDescription(task.id, textarea.value));
        
        const cancelButton = document.createElement('button');
        cancelButton.className = 'btn-cancel-description';
        cancelButton.textContent = 'Cancel';
        cancelButton.addEventListener('click', () => this.updateDescription(task));
        
        buttonContainer.appendChild(saveButton);
        buttonContainer.appendChild(cancelButton);
        
        editorContainer.appendChild(textarea);
        editorContainer.appendChild(buttonContainer);
        
        this.descriptionContainer.appendChild(editorContainer);
        
        // Focus the textarea
        textarea.focus();
    }
    
    /**
     * Save the task description
     * 
     * @param {number} taskId - The task ID
     * @param {string} description - The new description
     */
    saveDescription(taskId, description) {
        // Update the task data in our local store first
        if (task_list[taskId]) {
            task_list[taskId].description = description;
        }
        
        // Make API request to update the description
        apiProxyRequest(
            {
                controller: 'Task',
                action: 'update',
                params: {
                    id: taskId,
                    data: {
                        description: description
                    }
                }
            },
            (result) => {
                if (result.success) {
                    // Update the description display
                    this.updateDescription(task_list[taskId]);
                } else {
                    alert('Failed to save description: ' + (result.message || 'Unknown error'));
                }
            },
            (error) => {
                console.error('Error saving description:', error);
                alert('Failed to save description. Please try again.');
            }
        );
    }
    
    /**
     * Update the comments section with task data
     * 
     * @param {Object} task - The task object containing details to display
     */
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
     * Create a comment element with edit and delete buttons
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
        
        // Build comment HTML structure
        const headerDiv = document.createElement('div');
        headerDiv.className = 'comment-header';
        
        const userDiv = document.createElement('div');
        userDiv.className = 'comment-user';
        
        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'comment-avatar';
        avatarDiv.style.backgroundColor = getAvatarColor(comment.user_id);
        avatarDiv.textContent = initial;
        
        const authorDiv = document.createElement('div');
        authorDiv.className = 'comment-author';
        authorDiv.textContent = username;
        
        userDiv.appendChild(avatarDiv);
        userDiv.appendChild(authorDiv);
        
        const dateDiv = document.createElement('div');
        dateDiv.className = 'comment-date';
        dateDiv.textContent = formattedDate;
        
        headerDiv.appendChild(userDiv);
        headerDiv.appendChild(dateDiv);
        
        // Create content area (will be replaced by editor when editing)
        const contentDiv = document.createElement('div');
        contentDiv.className = 'comment-content';
        contentDiv.textContent = comment.text;
        
        // Create comment actions
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'comment-actions';
        
        // Edit button
        const editButton = document.createElement('button');
        editButton.className = 'comment-btn comment-edit-btn';
        editButton.innerHTML = '<span class="edit-icon">✎</span> Edit';
        editButton.addEventListener('click', () => this.showCommentEditor(commentElement, comment));
        
        // Delete button (as a form for non-JS fallback)
        const deleteForm = document.createElement('form');
        deleteForm.action = currentUrl;
        deleteForm.method = 'post';
        deleteForm.className = 'comment-delete-form';
        deleteForm.innerHTML = `
            <input type="hidden" name="entity_name" value="TaskComment">
            <input type="hidden" name="entity_action" value="delete">
            <input type="hidden" name="id" value="${comment.id}">
            <button type="submit" class="comment-btn comment-delete-btn" 
                    onclick="return confirm('Are you sure you want to delete this comment?')">
                <span class="delete-icon">🗑️</span> Delete
            </button>
        `;
        
        actionsDiv.appendChild(editButton);
        actionsDiv.appendChild(deleteForm);
        
        // Assemble the comment element
        commentElement.appendChild(headerDiv);
        commentElement.appendChild(contentDiv);
        commentElement.appendChild(actionsDiv);
        
        return commentElement;
    }
    
    /**
     * Show the comment editor for editing an existing comment
     * 
     * @param {HTMLElement} commentElement - The comment element
     * @param {Object} comment - The comment data
     */
    showCommentEditor(commentElement, comment) {
        // Create editor elements
        const editorContainer = document.createElement('div');
        editorContainer.className = 'comment-editor';
        
        const textarea = document.createElement('textarea');
        textarea.className = 'comment-edit-textarea';
        textarea.value = comment.text;
        
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'comment-edit-buttons';
        
        const saveButton = document.createElement('button');
        saveButton.className = 'comment-btn comment-save-btn';
        saveButton.textContent = 'Save';
        saveButton.addEventListener('click', () => this.saveComment(comment.id, textarea.value, commentElement));
        
        const cancelButton = document.createElement('button');
        cancelButton.className = 'comment-btn comment-cancel-btn';
        cancelButton.textContent = 'Cancel';
        cancelButton.addEventListener('click', () => {
            // Remove editor and restore content
            const contentDiv = commentElement.querySelector('.comment-content');
            contentDiv.style.display = 'block';
            editorContainer.remove();
        });
        
        buttonContainer.appendChild(saveButton);
        buttonContainer.appendChild(cancelButton);
        
        editorContainer.appendChild(textarea);
        editorContainer.appendChild(buttonContainer);
        
        // Hide the content div
        const contentDiv = commentElement.querySelector('.comment-content');
        contentDiv.style.display = 'none';
        
        // Insert the editor after the content div
        contentDiv.parentNode.insertBefore(editorContainer, contentDiv.nextSibling);
        
        // Focus the textarea
        textarea.focus();
    }
    
    /**
     * Save an edited comment
     * 
     * @param {number} commentId - The comment ID
     * @param {string} text - The new comment text
     * @param {HTMLElement} commentElement - The comment element
     */
    saveComment(commentId, text, commentElement) {
        if (!text.trim()) {
            alert('Comment cannot be empty');
            return;
        }
        
        // Make API request to update the comment
        apiProxyRequest(
            {
                controller: 'TaskComment',
                action: 'update',
                params: {
                    id: commentId,
                    data: {
                        text: text
                    }
                }
            },
            (result) => {
                if (result.success) {
                    // Update the comment in the UI
                    const contentDiv = commentElement.querySelector('.comment-content');
                    contentDiv.textContent = text;
                    contentDiv.style.display = 'block';
                    
                    // Remove the editor
                    const editorContainer = commentElement.querySelector('.comment-editor');
                    if (editorContainer) {
                        editorContainer.remove();
                    }
                    
                    // Update the comment in our local store
                    if (this.currentTaskId && task_list[this.currentTaskId]) {
                        const task = task_list[this.currentTaskId];
                        if (task.comments) {
                            const commentIndex = task.comments.findIndex(c => c.id === commentId);
                            if (commentIndex !== -1) {
                                task.comments[commentIndex].text = text;
                            }
                        }
                    }
                } else {
                    alert('Failed to save comment: ' + (result.message || 'Unknown error'));
                }
            },
            (error) => {
                console.error('Error saving comment:', error);
                alert('Failed to save comment. Please try again.');
            }
        );
    }
}

// Initialize when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the new TaskPanel
    taskPanel = new TaskPanel();
    
    // Expose the taskPanel to the global scope
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
/* Task Panel Enhancement Styles */

/* Description display and editing */
.task-description-text {
    margin-bottom: 15px;
    line-height: 1.5;
    white-space: pre-wrap;
}

.task-description-display {
    position: relative;
    padding-right: 40px; /* Make room for edit button */
}

.btn-edit-description {
    position: absolute;
    top: 0;
    right: 0;
    background: none;
    border: none;
    color: #909090;
    font-size: 0.9rem;
    cursor: pointer;
    padding: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
    opacity: 0.7;
    transition: opacity 0.2s ease;
    border-radius: 4px;
}

.btn-edit-description:hover {
    opacity: 1;
    background-color: rgba(255, 255, 255, 0.1);
}

.edit-icon {
    font-size: 14px;
}

.delete-icon {
    font-size: 14px;
}

.task-description-editor {
    width: 100%;
}

.description-textarea {
    width: 100%;
    min-height: 120px;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #444;
    background-color: #242424;
    color: #e0e0e0;
    resize: vertical;
    margin-bottom: 10px;
    font-family: inherit;
    line-height: 1.5;
}

.description-textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.description-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 10px;
}

.btn-save-description,
.btn-cancel-description {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-save-description {
    background-color: #3498db;
    color: white;
}

.btn-save-description:hover {
    background-color: #2980b9;
    transform: translateY(-1px);
}

.btn-cancel-description {
    background-color: #555;
    color: white;
}

.btn-cancel-description:hover {
    background-color: #666;
    transform: translateY(-1px);
}

/* Comment actions and editing */
.comment-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
    gap: 10px;
    opacity: 0.5;
    transition: opacity 0.2s ease;
}

.comment-item:hover .comment-actions {
    opacity: 1;
}

.comment-btn {
    background: none;
    border: none;
    font-size: 0.8rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    color: #909090;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.comment-btn:hover {
    background-color: #333;
    color: #e0e0e0;
}

.comment-delete-btn:hover {
    color: #e74c3c;
}

.comment-edit-btn:hover {
    color: #3498db;
}

/* Comment editor */
.comment-editor {
    margin-top: 10px;
    margin-bottom: 10px;
}

.comment-edit-textarea {
    width: 100%;
    min-height: 80px;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #444;
    background-color: #242424;
    color: #e0e0e0;
    resize: vertical;
    margin-bottom: 10px;
    font-family: inherit;
    line-height: 1.5;
}

.comment-edit-textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.comment-edit-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.comment-save-btn {
    background-color: #3498db;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
}

.comment-save-btn:hover {
    background-color: #2980b9;
    transform: translateY(-1px);
}

.comment-cancel-btn {
    background-color: #555;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
}

.comment-cancel-btn:hover {
    background-color: #666;
    transform: translateY(-1px);
}

/* Add placeholder styles */
.placeholder-text {
    color: #909090;
    font-style: italic;
}

/* Empty state enhancements */
.comments-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
    color: #909090;
    text-align: center;
}

.comments-empty-icon {
    font-size: 2rem;
    margin-bottom: 10px;
    opacity: 0.7;
}

/* Title editing styles */
.task-panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.task-title-text {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-edit-title {
    background: none;
    border: none;
    color: #909090;
    font-size: 0.9rem;
    cursor: pointer;
    padding: 5px;
    display: flex;
    align-items: center;
    opacity: 0.7;
    transition: opacity 0.2s ease;
    border-radius: 4px;
}

.btn-edit-title:hover {
    opacity: 1;
    background-color: rgba(255, 255, 255, 0.1);
}

.title-edit-input {
    flex: 1;
    background-color: #242424;
    border: 1px solid #444;
    border-radius: 4px;
    color: #e0e0e0;
    padding: 5px 10px;
    font-size: 1.2rem;
    font-weight: 500;
}

.title-edit-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.btn-save-title,
.btn-cancel-title {
    background: none;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-save-title {
    color: #2ecc71;
}

.btn-save-title:hover {
    background-color: rgba(46, 204, 113, 0.2);
}

.btn-cancel-title {
    color: #e74c3c;
}

.btn-cancel-title:hover {
    background-color: rgba(231, 76, 60, 0.2);
}
</style>