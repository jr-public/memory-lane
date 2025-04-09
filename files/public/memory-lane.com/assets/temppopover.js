function showDatePopover(dateElement, currentDate) {
    // Create the date selection content
    const dateContent = document.createElement('div');
    dateContent.className = 'date-popover-content';
    
    // Parse the current date or use today
    let selectedDate;
    try {
        selectedDate = currentDate ? new Date(currentDate) : new Date();
    } catch (e) {
        selectedDate = new Date();
    }
    
    // Format date for input value
    const formattedDate = selectedDate.toISOString().split('T')[0];
    
    // Create a simple date picker
    dateContent.innerHTML = `
        <div class="date-picker-header">
            <h3>Select Due Date</h3>
        </div>
        <div class="date-picker-body">
            <input type="date" class="date-picker-input" value="${formattedDate}">
        </div>
        <div class="date-picker-footer">
            <button class="btn-apply-date">Apply</button>
            <button class="btn-clear-date">Clear</button>
            <button class="btn-cancel-date">Cancel</button>
        </div>
    `;
    
    // Show the popover
    const popover = showPopover(dateElement, dateContent, {
        position: 'bottom',
        className: 'date-popover',
        onOpen: (popoverEl) => {
            // Setup event listeners for date picker actions
            const applyBtn = popoverEl.querySelector('.btn-apply-date');
            const clearBtn = popoverEl.querySelector('.btn-clear-date');
            const cancelBtn = popoverEl.querySelector('.btn-cancel-date');
            const dateInput = popoverEl.querySelector('.date-picker-input');
            
            // Apply button handler
            applyBtn.addEventListener('click', () => {
                const newDate = new Date(dateInput.value);
                updateTaskDate(dateElement, newDate);
                popover.close();
            });
            
            // Clear button handler
            clearBtn.addEventListener('click', () => {
                updateTaskDate(dateElement, null);
                popover.close();
            });
            
            // Cancel button handler
            cancelBtn.addEventListener('click', () => {
                popover.close();
            });
        }
    });
    
    return popover;
}

// Helper function to update task date display
function updateTaskDate(dateElement, newDate) {
    // Find the text span inside the date element
    const dateText = dateElement.querySelector('span:last-child');
    
    if (!dateText) return;
    
    if (newDate) {
        // Format the date for display
        const formattedDate = `${newDate.toLocaleString('default', { month: 'short' })} ${newDate.getDate()}`;
        dateText.textContent = formattedDate;
        
        // Here you would also update the task in your backend
        // This is a placeholder for the integration with your task system
        console.log('Date updated to:', newDate.toISOString());
    } else {
        // Clear the date
        dateText.textContent = 'No date';
        console.log('Date cleared');
    }
}