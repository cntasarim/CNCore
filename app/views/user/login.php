<?php
/**
 * Login page view
 */

// Define page title
$pageTitle = 'Login';

// Start output buffering for the content
ob_start();
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Login</h3>
            </div>
            
            <div class="card-body">
                <form action="<?= $route('user.login.submit') ?>" method="post">
                    <?= $csrf() ?>
                    
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors['general'] as $error): ?>
                                <p class="mb-0"><?= $e($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
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
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                               id="password" name="password">
                        
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback">
                                <?php foreach ($errors['password'] as $error): ?>
                                    <div><?= $e($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" 
                               <?= isset($old['remember']) && $old['remember'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="<?= $route('user.register') ?>">Register</a></p>
                </div>
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