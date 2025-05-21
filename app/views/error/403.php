<?php
/**
 * 403 error page view
 */

// Define page title
$pageTitle = 'Access Forbidden';

// Start output buffering for the content
ob_start();
?>

<div class="text-center">
    <h1 class="display-1">403</h1>
    <p class="lead">Oops! You don't have permission to access this page.</p>
    <p>This might be due to:</p>
    <ul class="list-unstyled">
        <li>An invalid or expired CSRF token</li>
        <li>Insufficient privileges</li>
        <li>Restricted area</li>
    </ul>
    <a href="<?= $route('home') ?>" class="btn btn-primary">Return to Homepage</a>
</div>

<?php
// Get the content
$content = ob_get_clean();

// Include the layout
include BASE_PATH . '/app/views/layouts/main.php';
?>