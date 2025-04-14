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
            <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Basic Information</h3>
                </div>
                <div class="panel-section-content">
                    <!-- Placeholder content -->
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
            </div>
            
            <!-- Task Description Section -->
            <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Description</h3>
                </div>
                <div class="panel-section-content">
                    <p class="placeholder-text">Task description will appear here.</p>
                </div>
            </div>
            
            <!-- Comments Section Placeholder -->
            <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Comments</h3>
                </div>
                <div class="panel-section-content">
                    <div class="coming-soon">
                        <div class="coming-soon-icon">💬</div>
                        <p>Comments section coming soon</p>
                    </div>
                </div>
            </div>
            
            <!-- Attachments Section Placeholder -->
            <div class="panel-section">
                <div class="panel-section-header">
                    <h3>Attachments</h3>
                </div>
                <div class="panel-section-content">
                    <div class="coming-soon">
                        <div class="coming-soon-icon">📎</div>
                        <p>Attachments section coming soon</p>
                    </div>
                </div>
            </div>
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
</style>