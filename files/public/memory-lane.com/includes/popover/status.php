
<!-- <div class="status-picker-header">
    <h3>Change Status</h3>
</div> -->
<div class="status-picker-body">
    <!-- Status options -->
    <div class="status-options-container"></div>
    
    <!-- <div class="status-footer">
        <button class="status-edit-btn">
            <span class="edit-icon">✎</span> Editar etiquetas
        </button>
    </div> -->
</div>


<style>
	/* Status Popover Specific Styles */
	.status-popover {
		width: 260px;
		background-color: #2d3748;
		border: 1px solid #4a5568;
	}

	.status-picker-header {
		padding: 12px 15px;
		border-bottom: 1px solid #4a5568;
		background-color: #2d3748;
	}

	.status-picker-header h3 {
		margin: 0;
		font-size: 14px;
		font-weight: 500;
		color: #e2e8f0;
	}

	.status-picker-body {
		padding: 10px;
	}

	.status-options-container {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.status-option {
		padding: 12px;
		border-radius: 6px;
		text-align: center;
		color: white;
		font-weight: 500;
		cursor: pointer;
		transition: transform 0.1s ease, opacity 0.1s ease;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	}

	.status-option:hover {
		transform: translateY(-1px);
		opacity: 0.95;
	}

	.status-option:active {
		transform: translateY(1px);
	}

	.status-footer {
		margin-top: 16px;
		padding-top: 12px;
		border-top: 1px solid #4a5568;
		display: flex;
		justify-content: center;
	}

	.status-edit-btn {
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

	.status-edit-btn:hover {
		background-color: #3a4556;
		color: #e2e8f0;
	}

	.edit-icon {
		font-size: 14px;
	}
</style>