<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load(); // so it throws exception if .env file is missing
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER']);

// Start session (needed by Auth tests)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
