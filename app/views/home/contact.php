<?php
/**
 * Contact page view
 */

// Define page title
$pageTitle = 'Contact Us';

// Start output buffering for the content
ob_start();
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Get in Touch</h3>
            </div>
            
            <div class="card-body">
                <form action="<?= $route('contact.submit') ?>" method="post">
                    <?= $csrf() ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= $e($old['name'] ?? '') ?>">
                        
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['name'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= $e($old['email'] ?? '') ?>">
                        
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['email'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" 
                                  id="message" name="message" rows="5"><?= $e($old['message'] ?? '') ?></textarea>
                        
                        <?php if (isset($errors['message'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['message'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
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