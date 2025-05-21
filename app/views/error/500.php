<?php
/**
 * 500 error page view
 */

// Define page title
$pageTitle = 'Server Error';

// Start output buffering for the content
ob_start();
?>

<div class="text-center">
    <h1 class="display-1">500</h1>
    <p class="lead">Oops! Something went wrong on our end.</p>
    <p>We're working to fix the issue. Please try again later.</p>
    <a href="<?= $route('home') ?>" class="btn btn-primary">Return to Homepage</a>
</div>

<?php
// Get the content
$content = ob_get_clean();

// Include the layout
include BASE_PATH . '/app/views/layouts/main.php';
?>