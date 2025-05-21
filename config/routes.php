<?php
/**
 * Route Definitions
 * 
 * Define all application routes here.
 */

use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;

// Apply global middleware
Router::useMiddleware(new CsrfMiddleware());

// Home routes
Router::get('/', 'HomeController@index', 'home');
Router::get('/about', 'HomeController@about', 'about');
Router::get('/contact', 'HomeController@contact', 'contact');
Router::post('/contact', 'HomeController@submitContact', 'contact.submit');

// User routes
Router::get('/register', 'UserController@registerForm', 'user.register');
Router::post('/register', 'UserController@register', 'user.register.submit');
Router::get('/login', 'UserController@loginForm', 'user.login');
Router::post('/login', 'UserController@login', 'user.login.submit');
Router::get('/logout', 'UserController@logout', 'user.logout');

// Dashboard routes - apply auth middleware
Router::get('/dashboard', 'DashboardController@index', 'dashboard', [new AuthMiddleware()]);
Router::get('/profile', 'DashboardController@profile', 'profile', [new AuthMiddleware()]);
Router::post('/profile/update', 'DashboardController@updateProfile', 'profile.update', [new AuthMiddleware()]);

// File upload example
Router::get('/upload', 'FileController@uploadForm', 'file.upload', [new AuthMiddleware()]);
Router::post('/upload', 'FileController@upload', 'file.upload.submit', [new AuthMiddleware()]);

// Default error routes
Router::get('/404', 'ErrorController@notFound', 'error.404');
Router::get('/403', 'ErrorController@forbidden', 'error.403');
Router::get('/500', 'ErrorController@serverError', 'error.500');