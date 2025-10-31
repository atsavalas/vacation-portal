<?php

use App\Helpers\View;

/**
 * ------------------------------------------------------------
 * Global Helper Functions
 * ------------------------------------------------------------
 * These helpers provide simple, framework-like functionality
 * such as view rendering, input sanitization, flash messages,
 * debugging, and environment access.
 * ------------------------------------------------------------
 */

/* ============================================================
 |  Environment & Routing
 * ============================================================ */

/**
 * Build a controller method string (for router compatibility).
 */
function route(string $class, string $method): string
{
    return implode('@', [$class, $method]);
}

/**
 * Retrieve environment variable with optional default value.
 */
function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

/* ============================================================
 |  Views & Redirects
 * ============================================================ */

/**
 * Render a Twig view without requiring the .twig extension.
 *
 * Example: view('auth/login', ['error' => 'Invalid credentials']);
 */
function view(string $template, array $data = []): void
{
    // Automatically append ".twig" if missing
    if (!str_ends_with($template, '.twig')) {
        $template .= '.twig';
    }

    View::render($template, $data);
}

function redirect($path): void
{
    header("Location: $path");
    exit();
}

/* ============================================================
 |  Input & Flash Messages
 * ============================================================ */

/**
 * Sanitize user input to prevent XSS.
 */
function clean($data): string
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}


/**
 * Set a flash message in session.
 */
function setFlash($key, $message): void
{
    $_SESSION['flash'][$key] = $message;
}

/**
 * Retrieve and clear a flash message.
 */
function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

/* ============================================================
 |  Debug Helpers
 * ============================================================ */

/**
 * Dump the given variables and end execution.
 */
function dd(...$vars): void
{
    echo '<pre style="background:#111;color:#0f0;padding:1rem;border-radius:6px;">';
    foreach ($vars as $v) {
        var_dump($v);
        echo "\n";
    }
    echo '</pre>';
    die();
}

/**
 * Dump the given variables without dying.
 */
function dump(...$vars): void
{
    echo '<pre style="background:#222;color:#0f0;padding:1rem;border-radius:6px;">';
    foreach ($vars as $v) {
        var_dump($v);
        echo "\n";
    }
    echo '</pre>';
}