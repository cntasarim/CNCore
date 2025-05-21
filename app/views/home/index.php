<?php
/**
 * Home page view
 */

// Define page title
$pageTitle = 'Welcome to Secure MVC Framework';

// Start output buffering for the content
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Secure PHP MVC Framework</h2>
                <p class="card-text">
                    This is a simple yet powerful PHP MVC framework designed with security in mind.
                    It provides the core functionality needed to build secure web applications.
                </p>
                <hr>
                <h3>Security Features</h3>
                <ul>
                    <li>Protection against SQL Injection</li>
                    <li>Cross-Site Scripting (XSS) prevention</li>
                    <li>Cross-Site Request Forgery (CSRF) protection</li>
                    <li>Secure file upload handling</li>
                    <li>Session security</li>
                    <li>Password hashing</li>
                    <li>Content Security Policy</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Getting Started</h5>
            </div>
            <div class="card-body">
                <p>
                    To get started with this framework, check out these pages:
                </p>
                <ul>
                    <li><a href="<?= $route('about') ?>">About</a> - Learn more about the framework</li>
                    <li><a href="<?= $route('contact') ?>">Contact</a> - Get in touch</li>
                    <?php if (!\App\Core\Session::has('user_id')): ?>
                        <li><a href="<?= $route('user.register') ?>">Register</a> - Create an account</li>
                        <li><a href="<?= $route('user.login') ?>">Login</a> - Access your dashboard</li>
                    <?php else: ?>
                        <li><a href="<?= $route('dashboard') ?>">Dashboard</a> - Access your dashboard</li>
                    <?php endif; ?>
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