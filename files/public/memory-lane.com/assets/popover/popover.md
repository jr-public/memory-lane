# Popover System Integration Guide

This guide explains how to integrate the new popover system into your Memory Lane Admin Panel.

## 1. File Setup

Place the new files in your project structure:

- Copy `popover.js` to your `assets` directory
- Copy `popover.css` to your `assets` directory or add its contents to your existing `style.css`
- Modify your existing `tasks.js` file with the new `createDueDateElement` function from `tasks-integration.js`

## 2. Include the Files

Add these lines to your `main.php` or relevant pages:

```php
<!-- Before your existing scripts -->
<link rel="stylesheet" href="assets/popover.css">
<script src="assets/popover.js" defer></script>
```

## 3. Test the Integration

After integration, clicking on a task date element should open a date selection popover positioned relative to the clicked element.

## 4. Using Popovers in Other Parts of Your Application

The popover system is designed to be reusable. Here's how to use it for other elements:

### Basic Usage

```javascript
// Create some content for the popover
const content = document.createElement('div');
content.innerHTML = 'This is a popover';

// Get the element that should trigger the popover
const triggerElement = document.getElementById('some-element');

// Show the popover when the element is clicked
triggerElement.addEventListener('click', function() {
    showPopover(this, content, {
        position: 'bottom', // 'top', 'right', 'bottom', 'left'
        offset: 5,
        className: 'my-custom-popover',
        onOpen: (popoverEl) => {
            // Do something when popover opens
        },
        onClose: () => {
            // Do something when popover closes
        }
    });
});
```

### Create Custom Popover Helper Functions

You can create helper functions for specific popover types, similar to `showDatePopover`:

```javascript
function showUserPopover(element, userId) {
    // Create content for user details
    const content = document.createElement('div');
    content.className = 'user-popover-content';
    
    // Fetch user details or use cached data
    const user = getUserDetails(userId);
    
    // Populate content
    content.innerHTML = `
        <div class="user-info">
            <div class="user-avatar">${user.name.charAt(0)}</div>
            <div class="user-name">${user.name}</div>
            <div class="user-role">${user.role}</div>
        </div>
        <div class="user-actions">
            <button class="btn-assign">Assign Task</button>
            <button class="btn-profile">View Profile</button>
        </div>
    `;
    
    // Show the popover
    return showPopover(element, content, {
        position: 'right',
        className: 'user-popover'
    });
}
```

## 5. Styling Custom Popovers

You can add custom styles for different popover types by adding CSS classes:

```css
/* Example of custom popover styles */
.user-popover {
    width: 250px;
}

.user-popover-content {
    padding: 15px;
}

.user-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}
```

## 6. Notes on Performance

- Popovers are created and destroyed as needed, not kept in DOM
- Event handlers are properly cleaned up when popovers close
- Positioning is adjusted to keep popovers within viewport