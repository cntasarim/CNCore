<?php
/**
 * File upload view
 */

// Define page title
$pageTitle = 'Upload File';

// Start output buffering for the content
ob_start();
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Upload File</h3>
            </div>
            
            <div class="card-body">
                <form action="<?= $route('file.upload.submit') ?>" method="post" enctype="multipart/form-data">
                    <?= $csrf() ?>
                    
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="file" name="file">
                        <div class="form-text">
                            <p>Maximum file size: <?= number_format($maxSize / (1024 * 1024), 2) ?> MB</p>
                            <p>Allowed file types: <?= implode(', ', $allowedExtensions) ?></p>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Secure File Upload Guidelines</h4>
            </div>
            <div class="card-body">
                <ul>
                    <li>Only upload files that you trust.</li>
                    <li>The system checks file types for security, but always be cautious.</li>
                    <li>Uploaded files are stored securely and can only be accessed by you.</li>
                    <li>Never upload files containing sensitive personal information unless encrypted.</li>
                </ul>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= $route('dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

<?php
// Get the content
$content = ob_get_clean();

// Include the layout
include BASE_PATH . '/app/views/layouts/main.php';
?>