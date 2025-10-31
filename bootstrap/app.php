<?php

/**
 * ------------------------------------------------------------------
 * Vacation Portal - Front Controller
 * ------------------------------------------------------------------
 * This file bootstraps the application, loads environment variables,
 * registers routes, and starts the router.
 *
 * It acts as the single entry point for all HTTP requests.
 */

use Bramus\Router\Router;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load(); // so it throws exception if .env file is missing
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER']);

// Config debug mode
if ($_ENV['APP_DEBUG'] ?? false) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

// Load global helper functions
require_once __DIR__ . '/helpers.php';

// Initialize Router
$router = new Router();

// Define routes
require __DIR__ . '/../app/routes.php';

return $router;
