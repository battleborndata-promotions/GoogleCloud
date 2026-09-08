<?php

require_once __DIR__ . '/../config/site.php';

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

        <form method="post">

            <div>
                <label for="business_name">
                    Business Name
                </label>

                <input
                    type="text"
                    id="business_name"
                    name="business_name"
                    value="<?php echo htmlspecialchars($siteConfig['business_name']); ?>"
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
                    value="<?php echo htmlspecialchars($siteConfig['hero_heading']); ?>"
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
                ><?php echo htmlspecialchars($siteConfig['hero_text']); ?></textarea>
            </div>

            <button type="submit">
                Save Settings
            </button>

        </form>

    </section>

</main>

</body>

</html>
