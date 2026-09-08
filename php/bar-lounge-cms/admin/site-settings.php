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
</head>

<body>

<main>

    <section>

        <h1>Site Settings</h1>

        <?php if ($message !== ''): ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>

        <?php if ($error !== ''): ?>

            <p>
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="post">

            <div>

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

            <div>

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

            <div>

                <label for="hero_text">
                    Hero Text
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

            <button type="submit">
                Save Settings
            </button>

        </form>

    </section>

</main>

</body>

</html>
