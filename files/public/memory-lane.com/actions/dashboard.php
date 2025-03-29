<?php
// Dashboard content file
?>
<div class="dashboard-container">
    <h1>Dashboard</h1>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-title">API Requests</div>
            <div class="stat-value">12,845</div>
            <div class="stat-change positive">+8.5% from last week</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-title">Active Users</div>
            <div class="stat-value">342</div>
            <div class="stat-change positive">+12% from last week</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-title">Error Rate</div>
            <div class="stat-value">0.8%</div>
            <div class="stat-change negative">+0.2% from last week</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-title">Avg. Response Time</div>
            <div class="stat-value">245ms</div>
            <div class="stat-change positive">-15ms from last week</div>
        </div>
    </div>
    
    <div class="dashboard-section">
        <h2>Recent Activity</h2>
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Event</th>
                    <th>User</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Today, 14:32</td>
                    <td>API Config Changed</td>
                    <td>admin@example.com</td>
                    <td><span class="status-success">Success</span></td>
                </tr>
                <tr>
                    <td>Today, 12:17</td>
                    <td>User Added</td>
                    <td>admin@example.com</td>
                    <td><span class="status-success">Success</span></td>
                </tr>
                <tr>
                    <td>Today, 10:04</td>
                    <td>Database Backup</td>
                    <td>system</td>
                    <td><span class="status-success">Success</span></td>
                </tr>
                <tr>
                    <td>Yesterday, 18:22</td>
                    <td>API Key Generated</td>
                    <td>john.doe@example.com</td>
                    <td><span class="status-success">Success</span></td>
                </tr>
                <tr>
                    <td>Yesterday, 15:40</td>
                    <td>Login Failed</td>
                    <td>unknown</td>
                    <td><span class="status-error">Failed</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>