<div class="difficulty-picker-header">
    <h3>Change Difficulty</h3>
</div>
<div class="difficulty-picker-body">
    <!-- Difficulty options will be populated via JavaScript -->
    <div class="difficulty-options-container">
    </div>
    
    <div class="difficulty-footer">
        <button class="difficulty-edit-btn">
            <span class="edit-icon">✎</span> Edit difficulties
        </button>
    </div>
</div>


<style>
	/* Difficulty Popover Specific Styles */
	.difficulty-popover {
		width: 260px;
		background-color: #2d3748;
		border: 1px solid #4a5568;
	}

	.difficulty-picker-header {
		padding: 12px 15px;
		border-bottom: 1px solid #4a5568;
		background-color: #2d3748;
	}

	.difficulty-picker-header h3 {
		margin: 0;
		font-size: 14px;
		font-weight: 500;
		color: #e2e8f0;
	}

	.difficulty-picker-body {
		padding: 10px;
	}

	.difficulty-options-container {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.difficulty-option {
		padding: 12px;
		border-radius: 6px;
		text-align: center;
		color: white;
		font-weight: 500;
		cursor: pointer;
		transition: transform 0.1s ease, opacity 0.1s ease;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	}

	.difficulty-option:hover {
		transform: translateY(-1px);
		opacity: 0.95;
	}

	.difficulty-option:active {
		transform: translateY(1px);
	}

	.difficulty-footer {
		margin-top: 16px;
		padding-top: 12px;
		border-top: 1px solid #4a5568;
		display: flex;
		justify-content: center;
	}

	.difficulty-edit-btn {
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

	.difficulty-edit-btn:hover {
		background-color: #3a4556;
		color: #e2e8f0;
	}
	.difficulty-indicator {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    color: #121212;
}
	.edit-icon {
		font-size: 14px;
	}
</style>