<div class="priority-picker-header">
    <h3>Change Priority</h3>
</div>
<div class="priority-picker-body">
    <!-- Priority options will be populated via JavaScript -->
    <div class="priority-options-container">
    </div>
    
    <div class="priority-footer">
        <button class="priority-edit-btn">
            <span class="edit-icon">✎</span> Edit priorities
        </button>
    </div>
</div>


<style>
	/* Priority Popover Specific Styles */
	.priority-popover {
		width: 260px;
		background-color: #2d3748;
		border: 1px solid #4a5568;
	}

	.priority-picker-header {
		padding: 12px 15px;
		border-bottom: 1px solid #4a5568;
		background-color: #2d3748;
	}

	.priority-picker-header h3 {
		margin: 0;
		font-size: 14px;
		font-weight: 500;
		color: #e2e8f0;
	}

	.priority-picker-body {
		padding: 10px;
	}

	.priority-options-container {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.priority-option {
		padding: 12px;
		border-radius: 6px;
		text-align: center;
		color: white;
		font-weight: 500;
		cursor: pointer;
		transition: transform 0.1s ease, opacity 0.1s ease;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	}

	.priority-option:hover {
		transform: translateY(-1px);
		opacity: 0.95;
	}

	.priority-option:active {
		transform: translateY(1px);
	}

	.priority-footer {
		margin-top: 16px;
		padding-top: 12px;
		border-top: 1px solid #4a5568;
		display: flex;
		justify-content: center;
	}

	.priority-edit-btn {
		background: none;
		border: none;
		color: #a0aec0;
		font-size: 13px;
		display: flex;
		align-items: center;
		gap: 6px;
		cursor: pointer;
		padding: 6px 10px;
		border-radius: 4px;
		transition: background-color 0.2s ease;
	}

	.priority-edit-btn:hover {
		background-color: #3a4556;
		color: #e2e8f0;
	}

	.edit-icon {
		font-size: 14px;
	}
</style>