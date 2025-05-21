<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $e($title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom styles -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            margin-top: 2rem;
            padding: 1.5rem 0;
            background-color: #f8f9fa;
            color: #6c757d;
        }
        .alert-flash {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?= $route('home') ?>"><?= $e(APP_NAME) ?></a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $route('home') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $route('about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $route('contact') ?>">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (\App\Core\Session::has('user_id')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $route('dashboard') ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $route('profile') ?>">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $route('user.logout') ?>">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $route('user.login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $route('user.register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash messages -->
    <div class="container alert-flash">
        <?php if ($hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $e($flash('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $e($flash('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($hasFlash('info')): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= $e($flash('info')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Main content -->
    <main class="container py-4">
        <?php if (isset($pageTitle) && !empty($pageTitle)): ?>
            <h1 class="mb-4"><?= $e($pageTitle) ?></h1>
        <?php endif; ?>
        
        <?php if (isset($errors) && !empty($errors) && isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors['general'] as $error): ?>
                    <p class="mb-0"><?= $e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= $e(APP_NAME) ?>. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>