<?php

function getDatabaseConnection(): PDO
{
    $dbName = getenv('DB_NAME');
    $dbUser = getenv('DB_USER');
    $dbPass = getenv('DB_PASS');
    $instanceConnectionName = getenv('INSTANCE_CONNECTION_NAME');

    if (
        !$dbName ||
        !$dbUser ||
        $dbPass === false ||
        !$instanceConnectionName
    ) {
        throw new RuntimeException(
            'Database configuration is incomplete.'
        );
    }

    $socket = '/cloudsql/' . $instanceConnectionName;

    $dsn = sprintf(
        'mysql:unix_socket=%s;dbname=%s;charset=utf8mb4',
        $socket,
        $dbName
    );

    return new PDO(
        $dsn,
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
}
