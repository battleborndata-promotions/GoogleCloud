<?php

$pageTitle = 'Home';

require_once __DIR__ . '/../config/site.php';
include __DIR__ . '/../includes/header.php';

?>

<main>

    <section class="hero">

        <div class="hero-content">

            <h1>
                <?php echo htmlspecialchars($siteConfig['hero_heading']); ?>
            </h1>

            <p>
                <?php echo htmlspecialchars($siteConfig['hero_text']); ?>
            </p>

            <div class="hero-actions">

                <a href="events.php" class="button">
                    View Events
                </a>

                <a href="connect.php" class="button button-secondary">
                    Connect With Us
                </a>

            </div>

        </div>

    </section>

</main>

<?php

include __DIR__ . '/../includes/footer.php';

?>
