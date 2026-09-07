<?php

$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$instanceConnectionName = getenv('INSTANCE_CONNECTION_NAME');

$socket = '/cloudsql/' . $instanceConnectionName;

$dsn = "mysql:unix_socket={$socket};dbname={$dbName};charset=utf8mb4";

try {
    $pdo = new PDO(
        $dsn,
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());

    $pdo = null;
}
