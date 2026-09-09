<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/site.php';
require_once __DIR__ . '/../config/database.php';

use Google\Cloud\Storage\StorageClient;

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $businessName = trim($_POST['business_name'] ?? '');
    $heroHeading = trim($_POST['hero_heading'] ?? '');
    $heroText = trim($_POST['hero_text'] ?? '');

    if (
        $businessName === '' ||
        $heroHeading === '' ||
        $heroText === ''
    ) {
        $error = 'All text fields are required.';
    } else {

        try {

            $pdo = getDatabaseConnection();

            $settingsToUpdate = [
                'business_name' => $businessName,
                'hero_heading' => $heroHeading,
                'hero_text' => $heroText
            ];

            /*
             * Handle optional hero image upload.
             */
            if (
                isset($_FILES['hero_image']) &&
                $_FILES['hero_image']['error'] !== UPLOAD_ERR_NO_FILE
            ) {

                $file = $_FILES['hero_image'];

                if ($file['error'] !== UPLOAD_ERR_OK) {
                    throw new RuntimeException(
                        'The image upload did not complete successfully.'
                    );
                }

                $maxFileSize = 5 * 1024 * 1024;

                if ($file['size'] > $maxFileSize) {
                    throw new RuntimeException(
                        'Hero image must be 5 MB or smaller.'
                    );
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);

                $mimeType = $finfo->file(
                    $file['tmp_name']
                );

                $allowedTypes = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($allowedTypes[$mimeType])) {
                    throw new RuntimeException(
                        'Hero image must be a JPG, PNG, or WebP file.'
                    );
                }

                $imageInfo = getimagesize(
                    $file['tmp_name']
                );

                if ($imageInfo === false) {
                    throw new RuntimeException(
                        'The uploaded file is not a valid image.'
                    );
                }

                $bucketName = getenv('GCS_BUCKET');

                if (!$bucketName) {
                    throw new RuntimeException(
                        'Cloud Storage bucket is not configured.'
                    );
                }

                $extension = $allowedTypes[$mimeType];

                $objectName =
                    'hero/' .
                    bin2hex(random_bytes(16)) .
                    '.' .
                    $extension;

                $storage = new StorageClient();

                $bucket = $storage->bucket(
                    $bucketName
                );

                $stream = fopen(
                    $file['tmp_name'],
                    'r'
                );

                if ($stream === false) {
                    throw new RuntimeException(
                        'Unable to read uploaded image.'
                    );
                }

                $bucket->upload(
                    $stream,
                    [
                        'name' => $objectName,
                        'metadata' => [
                            'contentType' => $mimeType
                        ]
                    ]
                );

            

                $heroImageUrl =
                    '/media.php?object=' .
                    rawurlencode($objectName);

                $settingsToUpdate['hero_image'] =
                    $heroImageUrl;
            }

            $sql = '
                UPDATE site_settings
                SET setting_value = :value
                WHERE setting_key = :key
            ';

            $stmt = $pdo->prepare($sql);

            foreach ($settingsToUpdate as $key => $value) {

                $stmt->execute([
                    ':value' => $value,
                    ':key' => $key
                ]);

                if ($stmt->rowCount() === 0) {

                    $insert = $pdo->prepare(
                        '
                        INSERT INTO site_settings
                            (setting_key, setting_value)
                        VALUES
                            (:key, :value)
                        ON DUPLICATE KEY UPDATE
                            setting_value = :update_value
                        '
                    );

                    $insert->execute([
                        ':key' => $key,
                        ':value' => $value,
                        ':update_value' => $value
                    ]);
                }
            }

            $siteConfig['business_name'] =
                $businessName;

            $siteConfig['hero_heading'] =
                $heroHeading;

            $siteConfig['hero_text'] =
                $heroText;

            if (isset($settingsToUpdate['hero_image'])) {
                $siteConfig['hero_image'] =
                    $settingsToUpdate['hero_image'];
            }

            $message =
                'Site settings saved successfully.';

        } catch (Throwable $e) {

            error_log(
                'Site settings update failed: ' .
                $e->getMessage()
            );

            $error = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Unable to save site settings.';
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

    <title>Site Settings</title>

    <link
        rel="stylesheet"
        href="/css/style.css"
    >

    <style>

        .admin-page {
            min-height: 100vh;
            background:
                linear-gradient(
                    180deg,
                    #171211 0%,
                    #211918 100%
                );
            padding: 48px 20px;
        }

        .admin-shell {
            width: min(100%, 760px);
            margin: 0 auto;
        }

        .admin-header {
            margin-bottom: 28px;
        }

        .admin-kicker {
            margin: 0 0 8px;
            color: #d9b39a;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .admin-header h1 {
            margin: 0 0 8px;
            font-size: clamp(2rem, 6vw, 3rem);
            line-height: 1.05;
        }

        .admin-subtitle {
            margin: 0;
            color: #c9bbb2;
            font-size: 1rem;
        }

        .admin-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 10px;
            padding: 28px;
            box-shadow:
                0 18px 50px rgba(0,0,0,0.22);
        }

        .admin-message {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .admin-message-success {
            background: rgba(53,110,109,0.18);
            border: 1px solid rgba(53,110,109,0.55);
        }

        .admin-message-error {
            background: rgba(184,77,75,0.16);
            border: 1px solid rgba(184,77,75,0.50);
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.86rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #f3eadf;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            border:
                1px solid rgba(255,255,255,0.14);
            border-radius: 6px;
            background: rgba(0,0,0,0.22);
            color: white;
            font: inherit;
            padding: 13px 14px;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 130px;
            line-height: 1.6;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #b8774b;
            box-shadow:
                0 0 0 3px rgba(184,119,75,0.14);
        }

        .form-help {
            margin: 7px 0 0;
            color: #9f9189;
            font-size: 0.82rem;
        }

        .current-image {
            margin-top: 14px;
        }

        .current-image img {
            display: block;
            width: 100%;
            max-width: 420px;
            height: auto;
            border-radius: 8px;
            border:
                1px solid rgba(255,255,255,0.12);
        }

        .admin-actions {
            margin-top: 28px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .admin-save {
            border: 0;
            cursor: pointer;
        }

        .admin-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 12px 22px;
            border:
                1px solid rgba(255,255,255,0.45);
            border-radius: 4px;
            color: white;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        @media (max-width: 600px) {

            .admin-page {
                padding: 32px 14px;
            }

            .admin-card {
                padding: 22px 18px;
            }

            .admin-actions {
                flex-direction: column;
            }

            .admin-save,
            .admin-link {
                width: 100%;
            }
        }

    </style>

</head>

<body>

<main class="admin-page">

<div class="admin-shell">

<header class="admin-header">

    <p class="admin-kicker">
        Website CMS
    </p>

    <h1>
        Site Settings
    </h1>

    <p class="admin-subtitle">
        Update the main homepage content for your website.
    </p>

</header>

<section class="admin-card">

<?php if ($message !== ''): ?>

    <div class="admin-message admin-message-success">
        <?php echo htmlspecialchars($message); ?>
    </div>

<?php endif; ?>

<?php if ($error !== ''): ?>

    <div class="admin-message admin-message-error">
        <?php echo htmlspecialchars($error); ?>
    </div>

<?php endif; ?>

<form
    method="post"
    enctype="multipart/form-data"
>

<div class="form-group">

    <label for="business_name">
        Business Name
    </label>

    <input
        type="text"
        id="business_name"
        name="business_name"
        value="<?php
            echo htmlspecialchars(
                $siteConfig['business_name']
            );
        ?>"
        required
    >

</div>

<div class="form-group">

    <label for="hero_heading">
        Hero Heading
    </label>

    <input
        type="text"
        id="hero_heading"
        name="hero_heading"
        value="<?php
            echo htmlspecialchars(
                $siteConfig['hero_heading']
            );
        ?>"
        required
    >

</div>

<div class="form-group">

    <label for="hero_text">
        Hero Description
    </label>

    <textarea
        id="hero_text"
        name="hero_text"
        rows="5"
        required
    ><?php
        echo htmlspecialchars(
            $siteConfig['hero_text']
        );
    ?></textarea>

</div>

<div class="form-group">

    <label for="hero_image">
        Hero Image
    </label>

    <input
        type="file"
        id="hero_image"
        name="hero_image"
        accept="image/jpeg,image/png,image/webp"
    >

    <p class="form-help">
        JPG, PNG, or WebP. Maximum file size: 5 MB.
        Leave this empty to keep the current image.
    </p>

    <?php if (!empty($siteConfig['hero_image'])): ?>

        <div class="current-image">

            <p class="form-help">
                Current hero image:
            </p>

            <img
                src="<?php
                    echo htmlspecialchars(
                        $siteConfig['hero_image']
                    );
                ?>"
                alt="Current hero image"
            >

        </div>

    <?php endif; ?>

</div>

<div class="admin-actions">

    <button
        type="submit"
        class="button admin-save"
    >
        Save Settings
    </button>

    <a
        href="/"
        class="admin-link"
    >
        View Website
    </a>

</div>

</form>

</section>

</div>

</main>

</body>

</html>
