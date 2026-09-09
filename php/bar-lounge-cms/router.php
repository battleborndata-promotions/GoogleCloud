<?php

$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (!is_string($uriPath)) {
    http_response_code(400);
    exit;
}

// Reject encoded separators and null bytes.
if (preg_match('/%(?:2f|5c|00)/i', $uriPath)) {
    http_response_code(400);
    exit;
}

$path = rawurldecode($uriPath);

// Reject backslashes and null bytes.
if (str_contains($path, '\\') || str_contains($path, "\0")) {
    http_response_code(400);
    exit;
}

$root = realpath(__DIR__);

if ($root === false) {
    http_response_code(500);
    exit;
}

// Resolve the requested file before serving it.
$file = realpath($root . $path);

// Only allow files inside the application root.
if (
    $file !== false &&
    $file !== $root &&
    !str_starts_with($file, $root . DIRECTORY_SEPARATOR)
) {
    http_response_code(403);
    exit;
}

// Protect the admin URL space.
$isAdminPath =
    $path === '/admin' ||
    str_starts_with($path, '/admin/');

if ($isAdminPath) {

    require_once __DIR__ . '/includes/auth.php';

    $publicAdminRoutes = [
        '/admin/login.php',
        '/admin/logout.php'
    ];

    if (
        !in_array($path, $publicAdminRoutes, true) &&
        !isAdminLoggedIn()
    ) {
        header('Location: /admin/login.php');
        exit;
    }
}

// Serve existing files through PHP's normal handling.
if ($file !== false && is_file($file)) {
    return false;
}

// Preserve the homepage.
if ($path === '/') {
    require __DIR__ . '/index.php';
    return true;
}

http_response_code(404);
echo 'Page not found.';
return true;
