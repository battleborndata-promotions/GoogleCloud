<?php

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = getDatabaseConnection();

    echo 'Database connection successful.';
} catch (Throwable $e) {
    error_log($e->getMessage());

    http_response_code(500);
    echo 'Database connection failed.';
}
