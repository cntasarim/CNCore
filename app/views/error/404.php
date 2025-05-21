<?php
/**
 * 404 error page view
 */

// Define page title
$pageTitle = 'Page Not Found';

// Start output buffering for the content
ob_start();
?>

<div class="text-center">
    <h1 class="display-1">404</h1>
    <p class="lead">Oops! The page you're looking for doesn't exist.</p>
    <p>The page might have been moved, deleted, or never existed.</p>
    <a href="<?= $route('home') ?>" class="btn btn-primary">Return to Homepage</a>
</div>

<?php
// Get the content
$content = ob_get_clean();

// Include the layout
include BASE_PATH . '/app/views/layouts/main.php';
?>