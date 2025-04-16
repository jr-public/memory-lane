
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