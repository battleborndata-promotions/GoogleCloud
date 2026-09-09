<?php

require_once __DIR__ . '/../includes/auth.php';

if (isAdminLoggedIn()) {
    header('Location: /admin/site-settings.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedUsername = trim(
        $_POST['username'] ?? ''
    );

    $submittedPassword =
        $_POST['password'] ?? '';

    $adminUsername =
        getenv('ADMIN_USERNAME');

    $adminPasswordHash =
        getenv('ADMIN_PASSWORD_HASH');

    if (
        !$adminUsername ||
        !$adminPasswordHash
    ) {
        error_log(
            'Admin authentication configuration is incomplete.'
        );

        $error =
            'Login is temporarily unavailable.';
    } else {

        $usernameMatches = hash_equals(
            strtolower($adminUsername),
            strtolower($submittedUsername)
        );

        $passwordMatches = password_verify(
            $submittedPassword,
            $adminPasswordHash
        );

        if (
            $usernameMatches &&
            $passwordMatches
        ) {

            loginAdmin();

            header(
                'Location: /admin/site-settings.php'
            );

            exit;

        } else {

            $error =
                'Invalid username or password.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login</title>

    <link
        rel="stylesheet"
        href="/css/style.css"
    >

    <style>

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                linear-gradient(
                    180deg,
                    #171211 0%,
                    #211918 100%
                );
        }

        .login-card {
            width: min(100%, 420px);
            padding: 32px 26px;
            background:
                rgba(255, 255, 255, 0.04);
            border:
                1px solid rgba(255, 255, 255, 0.10);
            border-radius: 10px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.28);
        }

        .login-kicker {
            margin: 0 0 8px;
            color: #d9b39a;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .login-card h1 {
            margin: 0 0 10px;
            font-size: 2rem;
        }

        .login-subtitle {
            margin: 0 0 28px;
            color: #c9bbb2;
        }

        .login-error {
            margin-bottom: 20px;
            padding: 13px 14px;
            border-radius: 6px;
            background:
                rgba(184, 77, 75, 0.16);
            border:
                1px solid rgba(184, 77, 75, 0.50);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #f3eadf;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .form-group input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px 14px;
            border:
                1px solid rgba(255, 255, 255, 0.14);
            border-radius: 6px;
            background:
                rgba(0, 0, 0, 0.22);
            color: white;
            font: inherit;
            outline: none;
        }

        .form-group input:focus {
            border-color: #b8774b;
            box-shadow:
                0 0 0 3px
                rgba(184, 119, 75, 0.14);
        }

        .login-button {
            width: 100%;
            margin-top: 8px;
            border: 0;
            cursor: pointer;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #c9bbb2;
            font-size: 0.9rem;
        }

    </style>

</head>

<body>

<main class="login-page">

    <section class="login-card">

        <p class="login-kicker">
            Website CMS
        </p>

        <h1>
            Admin Login
        </h1>

        <p class="login-subtitle">
            Sign in to manage the website.
        </p>

        <?php if ($error !== ''): ?>

            <div class="login-error">
                <?php
                    echo htmlspecialchars($error);
                ?>
            </div>

        <?php endif; ?>

        <form method="post">

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >

            </div>

            <button
                type="submit"
                class="button login-button"
            >
                Sign In
            </button>

        </form>

        <a
            href="/"
            class="back-link"
        >
            Return to website
        </a>

    </section>

</main>

</body>

</html>
