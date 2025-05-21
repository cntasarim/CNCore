<?php
/**
 * Dashboard page view
 */

// Define page title
$pageTitle = 'Dashboard';

// Start output buffering for the content
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3 class="mb-0">Welcome, <?= $e($userName) ?></h3>
    </div>
    
    <div class="card-body">
        <p class="lead">This is your dashboard where you can manage your account.</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Your Profile</h4>
                        <p class="card-text">View and update your personal information.</p>
                        <a href="<?= $route('profile') ?>" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">File Management</h4>
                        <p class="card-text">Upload and manage your files securely.</p>
                        <a href="<?= $route('file.upload') ?>" class="btn btn-primary">Upload Files</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Security Recommendations</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Use a strong password</strong>
                        <p class="mb-0">Ensure your password is at least 12 characters and includes letters, numbers, and special characters.</p>
                    </li>
                    <li class="list-group-item">
                        <strong>Enable two-factor authentication</strong>
                        <p class="mb-0">Add an extra layer of security to your account.</p>
                    </li>
                    <li class="list-group-item">
                        <strong>Regular security audits</strong>
                        <p class="mb-0">Check your account activity regularly for any suspicious activity.</p>
                    </li>
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