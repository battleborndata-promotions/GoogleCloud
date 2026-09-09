<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function requireAdminLogin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function loginAdmin(): void
{
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
}

function logoutAdmin(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
