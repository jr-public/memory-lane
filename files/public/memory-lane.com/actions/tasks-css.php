<style>
/* Task Management Specific Styles */
.task-container {
    padding: 20px;
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.task-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.task-card {
    background-color: #2a2a2a;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.stat-title {
    color: #909090;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: bold;
    color: #e0e0e0;
    margin-bottom: 0.5rem;
}

.task-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.btn-action {
    background-color: #3498db;
    color: #121212;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.9rem;
}

.task-filters {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    background-color: #333;
    border: none;
    color: #e0e0e0;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
}

.filter-btn.active {
    background-color: #3498db;
    color: #121212;
}

.task-list-container {
    background-color: #2a2a2a;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.task-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #444;
}

.task-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #444;
    transition: all 0.2s ease;
}

.task-item:hover {
    background-color: #333;
}

.task-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 2;
}

.task-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #909090;
    font-size: 0.85rem;
    flex: 1;
    justify-content: flex-end;
}

.task-status-icon {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-completed-icon {
    background-color: #2ecc71;
}

.status-in-progress-icon {
    background-color: #3498db;
}

.status-pending-icon {
    background-color: #f39c12;
}

.status-on-hold-icon {
    background-color: #7f8c8d;
}

.task-name {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}

.task-assignee, .task-due-date {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.task-status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    color: #121212;
}

.priority-indicator {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
}

.priority-high {
    background-color: #e74c3c;
    color: white;
}

.priority-medium {
    background-color: #f39c12;
    color: #121212;
}

.priority-low {
    background-color: #3498db;
    color: #121212;
}

.toggle-icon, .toggle-icon-placeholder {
    cursor: pointer;
    width: 16px;
    display: inline-block;
    text-align: center;
    font-size: 0.8rem;
}

.toggle-icon-placeholder {
    visibility: hidden;
}

.task-children {
    padding-left: 0.5rem;
}

/* Avatar/Assignment Styles */
.task-assignments {
    margin-right: 10px;
}

.task-avatars-container {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.task-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    margin-right: -8px;
    border: 2px solid #2a2a2a;
}

.task-avatar-more {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #7f8c8d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
    border: 2px solid #2a2a2a;
}

.task-avatars-empty {
    color: #909090;
    font-size: 0.8rem;
    font-style: italic;
}

/* Plus icon for adding assignments */
.task-avatar-add {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: bold;
    border: 2px solid #2a2a2a;
    cursor: pointer;
    transition: all 0.2s ease;
}

.task-avatar-add:hover {
    background-color: #2980b9;
    transform: scale(1.1);
}

/* Modal Styles */
#assignment-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    overflow: auto;
}

.assignment-modal-content {
    background-color: #2a2a2a;
    margin: 10% auto;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: modalFadeIn 0.3s;
}

@keyframes modalFadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}

.assignment-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #444;
}

.assignment-modal-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #e0e0e0;
}

.assignment-modal-close {
    color: #909090;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.assignment-modal-close:hover {
    color: #e0e0e0;
}

.assignment-list {
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
}

.assignment-content {
    margin-bottom: 20px;
}

.assignment-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #444;
    transition: all 0.2s ease;
}

.assignment-item:last-child {
    border-bottom: none;
}

.assignment-item:hover {
    background-color: #333;
}
.assignment-main-content {
    display: flex;
    align-items: center;
    flex: 1;
}

/* Delete button styles */
.assignment-delete-form {
    margin-left: 10px;
}

.assignment-delete-btn {
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
    transition: all 0.2s ease;
}

.assignment-delete-btn:hover {
    background-color: #c0392b;
    transform: scale(1.1);
}
.assignment-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
    margin-right: 15px;
}

.assignment-details {
    flex: 1;
}

.assignment-name {
    font-weight: bold;
    color: #e0e0e0;
    margin-bottom: 3px;
}

.assignment-role {
    color: #909090;
    font-size: 0.85rem;
}

/* Assignment form styles */
.assignment-form {
    width: 100%;
    margin-top: 15px;
}

.assignment-separator {
    height: 1px;
    background-color: #444;
    margin: 10px 0;
}

.form-row {
    display: flex;
    gap: 10px;
    width: 100%;
}

.user-select {
    flex: 1;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #444;
    background-color: #333;
    color: #e0e0e0;
    font-size: 0.9rem;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23e0e0e0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    padding-right: 30px;
}

.user-select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.user-select option {
    background-color: #333;
    color: #e0e0e0;
}

.btn-add-assignment {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-add-assignment:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
}

.btn-add-more-assignment {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-add-more-assignment:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
}

/* Empty state in the assignment modal */
.assignment-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px 0;
    text-align: center;
}

.empty-icon {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #909090;
}

.empty-text {
    color: #909090;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .task-overview {
        grid-template-columns: 1fr;
    }
    
    .task-meta {
        flex-direction: column;
        align-items: flex-end;
        gap: 0.25rem;
    }
    
    .task-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .task-info {
        margin-bottom: 0.5rem;
    }
    
    .assignment-modal-content {
        width: 95%;
        margin: 5% auto;
    }
}

/* Add these styles to your existing CSS */
.task-new-form {
    display: flex;
    align-items: center;
    width: 100%;
}

.task-new-form .task-info {
    display: flex;
    align-items: center;
    width: 100%;
}

.new-task-form {
    transition: all 0.2s ease;
    border-bottom: 1px dashed #444;
}

.new-task-form:hover {
    background-color: #333;
}

.new-task-form .ce-form-input {
    background-color: #333;
    border: 1px solid #444;
    color: #e0e0e0;
    border-radius: 4px;
}

.new-task-form .ce-form-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
}


.task-new-form {
    display: flex;
    align-items: center;
    width: 100%;
}

.task-new-form .task-info {
    display: flex;
    align-items: center;
    width: 100%;
}

.new-task-form {
    transition: all 0.2s ease;
    border-bottom: 1px dashed #444;
}

.new-task-form:hover {
    background-color: #333;
}

.new-task-form .ce-form-input {
    background-color: #333;
    border: 1px solid #444;
    color: #e0e0e0;
    border-radius: 4px;
}

.new-task-form .ce-form-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.25);
}

/* Improved styling for new task input */
.new-task-form {
    margin: 8px 0;
    padding: 8px 0;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.new-task-form:hover {
    background-color: rgba(52, 152, 219, 0.05);
}

.new-task-form .task-info {
    align-items: center;
}

.new-task-form .ce-form-input {
    background-color: transparent;
    border: 1px dashed #444;
    border-radius: 4px;
    color: #909090;
    height: 36px !important;
    padding: 0 12px !important;
    transition: all 0.2s ease;
    font-size: 0.95rem;
}

.new-task-form .ce-form-input:focus {
    border: 1px solid #3498db;
    color: #e0e0e0;
    background-color: rgba(52, 152, 219, 0.1);
    outline: none;
}

.new-task-form .ce-form-input::placeholder {
    color: #666;
    font-style: italic;
}

.new-task-form .toggle-icon-placeholder {
    width: 16px;
    display: inline-block;
    margin-right: 4px;
}

.new-task-form .task-status-icon {
    opacity: 0.5;
}

/* Add subtle animation on hover */
.new-task-form:hover .task-status-icon {
    opacity: 1;
}

/* Better styling for the task input parent divider */
.task-children {
    position: relative;
    padding-left: 10px;
}

.task-children:before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #444;
    opacity: 0.5;
}

/* Make the new task form stand out a bit */
.new-task-form .task-new-form {
    display: flex;
    align-items: center;
}

/* Add a subtle button-like effect for the add task input field */
.new-task-form:hover .ce-form-input {
    border-color: #666;
}

/* Better spacing for input field */
.new-task-form .task-status-icon {
    margin-right: 6px;
}

/* Add a subtle plus icon before the input on hover */
.new-task-form:hover .ce-form-input::placeholder {
    color: #909090;
}
</style>