<?php

require_once __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

$bucketName = getenv('GCS_BUCKET');
$objectName = $_GET['object'] ?? '';

if (
    !$bucketName ||
    $objectName === '' ||
    !str_starts_with($objectName, 'hero/')
) {
    http_response_code(404);
    exit;
}

try {

    $storage = new StorageClient();

    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($objectName);

    $info = $object->info();

    if (!isset($info['contentType'])) {
        http_response_code(404);
        exit;
    }

    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!in_array($info['contentType'], $allowedTypes, true)) {
        http_response_code(404);
        exit;
    }

    $contents = $object->downloadAsString();

    header('Content-Type: ' . $info['contentType']);
    header('Cache-Control: public, max-age=3600');

    echo $contents;

} catch (Throwable $e) {

    error_log(
        'Cloud Storage image read failed: ' .
        $e->getMessage()
    );

    http_response_code(404);
}
