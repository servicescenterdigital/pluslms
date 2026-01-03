<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error handling
if (config('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Load routes and dispatch
$router = require __DIR__ . '/../routes/web.php';
$router->dispatch();
