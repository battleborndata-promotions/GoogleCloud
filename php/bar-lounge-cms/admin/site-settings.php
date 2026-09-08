
<?php

require_once __DIR__ . '/../config/site.php';
require_once __DIR__ . '/../config/database.php';

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
        $error = 'All fields are required.';
    } else {

        try {

            $pdo = getDatabaseConnection();

            $sql = '
                UPDATE site_settings
                SET setting_value = :value
                WHERE setting_key = :key
            ';

            $stmt = $pdo->prepare($sql);

            $settingsToUpdate = [
                'business_name' => $businessName,
                'hero_heading' => $heroHeading,
                'hero_text' => $heroText
            ];

            foreach ($settingsToUpdate as $key => $value) {
                $stmt->execute([
                    ':value' => $value,
                    ':key' => $key
                ]);
            }

            $siteConfig['business_name'] = $businessName;
            $siteConfig['hero_heading'] = $heroHeading;
            $siteConfig['hero_text'] = $heroText;

            $message = 'Site settings saved successfully.';

        } catch (Throwable $e) {

            error_log(
                'Site settings update failed: ' .
                $e->getMessage()
            );

            $error = 'Unable to save site settings.';
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

    <link rel="stylesheet" href="/css/style.css">

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
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.22);
        }

        .admin-message {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .admin-message-success {
            background: rgba(53, 110, 109, 0.18);
            border: 1px solid rgba(53, 110, 109, 0.55);
        }

        .admin-message-error {
            background: rgba(184, 77, 75, 0.16);
            border: 1px solid rgba(184, 77, 75, 0.50);
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
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.22);
            color: white;
            font: inherit;
            padding: 13px 14px;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 130px;
            line-height: 1.6;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #b8774b;
            box-shadow: 0 0 0 3px rgba(184, 119, 75, 0.14);
            background: rgba(0, 0, 0, 0.30);
        }

        .form-help {
            margin: 7px 0 0;
            color: #9f9189;
            font-size: 0.82rem;
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
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 4px;
            color: white;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .admin-link:hover {
            background: rgba(255, 255, 255, 0.08);
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

            <form method="post">

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

                    <p class="form-help">
                        This appears in the header and homepage.
                    </p>

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

                    <p class="form-help">
                        The main message displayed over the hero image.
                    </p>

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

                    <p class="form-help">
                        Keep this short and easy to read on mobile.
                    </p>

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
