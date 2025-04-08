/**
 * Popover Module - Creates contextual panels positioned relative to trigger elements
 * 
 * @author Memory Lane Admin Panel
 * @version 1.0.0
 */

/**
 * Create and show a popover next to a trigger element
 * 
 * @param {HTMLElement} triggerElement - The element that triggered the popover
 * @param {HTMLElement|String} content - The content to show in the popover (HTML Element or HTML string)
 * @param {Object} options - Configuration options
 * @param {String} options.position - Position of popover (top, right, bottom, left), defaults to 'bottom'
 * @param {Number} options.offset - Distance from trigger element in pixels, defaults to 5
 * @param {String} options.className - Additional class names for the popover
 * @param {Function} options.onOpen - Callback function when popover opens
 * @param {Function} options.onClose - Callback function when popover closes
 * @returns {Object} - Popover controller with close method
 */
function showPopover(triggerElement, content, options = {}) {
    // Default options
    const defaultOptions = {
        position: 'bottom',
        offset: 5,
        className: '',
        onOpen: null,
        onClose: null
    };
    
    // Merge default options with provided options
    const config = { ...defaultOptions, ...options };
    
    // Generate a unique ID for this popover
    const popoverId = 'popover-' + Math.random().toString(36).substr(2, 9);
    
    // Create popover element
    const popoverElement = document.createElement('div');
    popoverElement.className = `popover ${config.className}`;
    popoverElement.id = popoverId;
    
    // Add content to popover
    if (typeof content === 'string') {
        popoverElement.innerHTML = content;
    } else if (content instanceof HTMLElement) {
        popoverElement.appendChild(content);
    }
    
    // Add popover to DOM
    document.body.appendChild(popoverElement);
    
    // Position popover relative to trigger element
    positionPopover(popoverElement, triggerElement, config.position, config.offset);
    
    // Add active class for animation
    setTimeout(() => {
        popoverElement.classList.add('active');
    }, 10);
    
    // Handle click outside to close popover
    const handleClickOutside = (event) => {
        if (!popoverElement.contains(event.target) && 
            event.target !== triggerElement && 
            !triggerElement.contains(event.target)) {
            closePopover();
        }
    };
    
    // Handle escape key to close popover
    const handleEscKey = (event) => {
        if (event.key === 'Escape') {
            closePopover();
        }
    };
    
    // Attach event listeners
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscKey);
    
    // Call onOpen callback if provided
    if (typeof config.onOpen === 'function') {
        config.onOpen(popoverElement);
    }
    
    // Function to close the popover
    function closePopover() {
        // Remove the active class first (for animation)
        popoverElement.classList.remove('active');
        
        // Wait for animation to complete before removing from DOM
        setTimeout(() => {
            // Remove event listeners
            document.removeEventListener('click', handleClickOutside);
            document.removeEventListener('keydown', handleEscKey);
            
            // Remove the popover from DOM
            if (popoverElement.parentNode) {
                popoverElement.parentNode.removeChild(popoverElement);
            }
            
            // Call onClose callback if provided
            if (typeof config.onClose === 'function') {
                config.onClose();
            }
        }, 200); // Match this with CSS transition time
    }
    
    // Return controller with methods
    return {
        close: closePopover,
        element: popoverElement
    };
}

/**
 * Position the popover relative to the trigger element
 * 
 * @param {HTMLElement} popoverElement - The popover element
 * @param {HTMLElement} triggerElement - The element that triggered the popover
 * @param {String} position - Position of popover (top, right, bottom, left)
 * @param {Number} offset - Distance from trigger element in pixels
 */
function positionPopover(popoverElement, triggerElement, position, offset) {
    // Get the rectangle of the trigger element
    const triggerRect = triggerElement.getBoundingClientRect();
    
    // Get the dimensions of the popover
    const popoverWidth = popoverElement.offsetWidth;
    const popoverHeight = popoverElement.offsetHeight;
    
    // Calculate scroll position
    const scrollX = window.scrollX || window.pageXOffset;
    const scrollY = window.scrollY || window.pageYOffset;
    
    // Calculate position based on the specified direction
    let top, left;
    
    switch (position) {
        case 'top':
            top = triggerRect.top + scrollY - popoverHeight - offset;
            left = triggerRect.left + scrollX + (triggerRect.width / 2) - (popoverWidth / 2);
            popoverElement.classList.add('position-top');
            break;
        case 'right':
            top = triggerRect.top + scrollY + (triggerRect.height / 2) - (popoverHeight / 2);
            left = triggerRect.right + scrollX + offset;
            popoverElement.classList.add('position-right');
            break;
        case 'left':
            top = triggerRect.top + scrollY + (triggerRect.height / 2) - (popoverHeight / 2);
            left = triggerRect.left + scrollX - popoverWidth - offset;
            popoverElement.classList.add('position-left');
            break;
        case 'bottom':
        default:
            top = triggerRect.bottom + scrollY + offset;
            left = triggerRect.left + scrollX + (triggerRect.width / 2) - (popoverWidth / 2);
            popoverElement.classList.add('position-bottom');
            break;
    }
    
    // Set the position
    popoverElement.style.top = `${top}px`;
    popoverElement.style.left = `${left}px`;
    
    // Adjust position if popover is outside viewport
    adjustPositionForViewport(popoverElement);
}

/**
 * Adjust the position of the popover to ensure it stays within the viewport
 * 
 * @param {HTMLElement} popoverElement - The popover element
 */
function adjustPositionForViewport(popoverElement) {
    const rect = popoverElement.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    
    // Adjust horizontal position if needed
    if (rect.right > viewportWidth) {
        const overflowRight = rect.right - viewportWidth;
        popoverElement.style.left = `${parseInt(popoverElement.style.left) - overflowRight - 10}px`;
    }
    
    if (rect.left < 0) {
        popoverElement.style.left = '10px';
    }
    
    // Adjust vertical position if needed
    if (rect.bottom > viewportHeight) {
        const overflowBottom = rect.bottom - viewportHeight;
        popoverElement.style.top = `${parseInt(popoverElement.style.top) - overflowBottom - 10}px`;
    }
    
    if (rect.top < 0) {
        popoverElement.style.top = '10px';
    }
}

// Date popover helper for the task date element
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