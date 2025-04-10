/**
 * Send an asynchronous request to the API proxy and pass the result to a callback
 * 
 * @param {Object} data - The data to send to the API
 * @param {Function} callback - Function to handle the response
 * @param {Function} errorCallback - Function to handle errors (optional)
 * @returns {Promise} - The fetch promise for additional handling if needed
 */
function apiProxyRequest(data, callback, errorCallback = null) {
    
    // Return the promise but don't await it - let it resolve in the background
    const fetchPromise = fetch('api_proxy.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    }).then(response => {
            // Check if the response is OK (status 200-299)
            if (!response.ok) {
                throw new Error(`API Proxy Error: ${response.status} ${response.statusText}`);
            }
            return response.json();
	}).then(result => {
		// Pass the result to the callback
		if (typeof callback === 'function') {
			callback(result);
		}
		return result; // Return for promise chaining if needed
	}).catch(error => {
		console.error('API Proxy Request failed:', error);
		
		// Call the error callback if provided
		if (typeof errorCallback === 'function') {
			errorCallback(error);
		}
	});
    
    return fetchPromise; // Return the promise for optional additional handling
}