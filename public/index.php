<?php
/**
 * Front Controller - Entry point for all requests
 * 
 * This file initializes the application and handles the request lifecycle.
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration and autoloader
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/app/core/Autoloader.php';

use App\Core\App;
use App\Core\Router;
use App\Core\Security;
use App\Core\Session;

// Initialize secure session
Session::start();

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', DEBUG_MODE ? 1 : 0);
set_error_handler('App\Core\ErrorHandler::handleError');
set_exception_handler('App\Core\ErrorHandler::handleException');

// Load routes
require_once BASE_PATH . '/config/routes.php';

// Initialize security
Security::init();

// Start the application
$app = new App();
$app->run();