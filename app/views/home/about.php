<?php
/**
 * About page view
 */

// Define page title
$pageTitle = 'About Us';

// Start output buffering for the content
ob_start();
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">About Our Framework</h2>
        
        <p class="lead">
            This is a simple yet powerful PHP MVC framework designed with security as the top priority.
        </p>
        
        <h3>Key Features</h3>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h4>MVC Architecture</h4>
                <ul>
                    <li>Clean separation of concerns</li>
                    <li>Controllers handle user input</li>
                    <li>Models manage data and business logic</li>
                    <li>Views present data to users</li>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h4>Routing System</h4>
                <ul>
                    <li>Named routes for easier navigation</li>
                    <li>Support for GET, POST, PUT, DELETE methods</li>
                    <li>Route parameters for dynamic URLs</li>
                    <li>Route-specific middleware</li>
                </ul>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h4>Security Features</h4>
                <ul>
                    <li>PDO with prepared statements</li>
                    <li>XSS protection with automatic escaping</li>
                    <li>CSRF token generation and validation</li>
                    <li>Secure file upload handling</li>
                    <li>Password hashing with bcrypt</li>
                    <li>Session security enhancements</li>
                    <li>Content Security Policy (CSP)</li>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h4>Other Features</h4>
                <ul>
                    <li>Database abstraction layer</li>
                    <li>Middleware pipeline</li>
                    <li>Flash messages</li>
                    <li>Form validation</li>
                    <li>Error and exception handling</li>
                    <li>Customizable configuration</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content
$content = ob_get_clean();

// Include the layout
include BASE_PATH . '/app/views/layouts/main.php';
?>