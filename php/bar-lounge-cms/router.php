<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === false) {
    http_response_code(400);
    exit;
}

$path = rawurldecode($path);

// Protect every request under /admin/.
if ($path === '/admin' || str_starts_with($path, '/admin/')) {

    require_once __DIR__ . '/includes/auth.php';

    // These routes must remain accessible without logging in.
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

// Preserve PHP's normal static-file and script handling.
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

// Preserve the existing homepage behavior.
if ($path === '/') {
    require __DIR__ . '/index.php';
    return true;
}

http_response_code(404);
echo 'Page not found.';
return true;
