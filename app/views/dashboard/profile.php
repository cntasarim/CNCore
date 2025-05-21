<?php
/**
 * Profile page view
 */

// Define page title
$pageTitle = 'Your Profile';

// Start output buffering for the content
ob_start();
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="mb-0">Edit Profile</h3>
            </div>
            
            <div class="card-body">
                <form action="<?= $route('profile.update') ?>" method="post">
                    <?= $csrf() ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= $e($old['name'] ?? $user['name']) ?>">
                        
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
                               id="email" name="email" value="<?= $e($old['email'] ?? $user['email']) ?>">
                        
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['email'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <hr>
                    <h5>Change Password</h5>
                    <p class="text-muted small">Leave these fields blank if you don't want to change your password.</p>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" 
                               id="current_password" name="current_password">
                        
                        <?php if (isset($errors['current_password'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['current_password'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" 
                               id="new_password" name="new_password">
                        
                        <?php if (isset($errors['new_password'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['new_password'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                               id="confirm_password" name="confirm_password">
                        
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['confirm_password'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center">
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