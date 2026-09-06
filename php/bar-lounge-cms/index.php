<?php

$pageTitle = 'Home';

require_once __DIR__ . '/config/site.php';
include __DIR__ . '/includes/header.php';

?>

<main>

    <section
        class="hero"
        style="
            background-image:
                linear-gradient(
                    90deg,
                    rgba(20, 15, 14, 0.88) 0%,
                    rgba(20, 15, 14, 0.66) 42%,
                    rgba(20, 15, 14, 0.24) 72%,
                    rgba(20, 15, 14, 0.10) 100%
                ),
                url('<?php echo htmlspecialchars($siteConfig['hero_image']); ?>');
        "
    >

        <div class="hero-inner">

            <div class="hero-content">

                <p class="hero-kicker">
                    Reno Neighborhood Lounge
                </p>

                <h1>
                    <?php echo htmlspecialchars($siteConfig['business_name']); ?>
                </h1>

                <h2>
                    <?php echo htmlspecialchars($siteConfig['hero_heading']); ?>
                </h2>

                <p class="hero-description">
                    <?php echo htmlspecialchars($siteConfig['hero_text']); ?>
                </p>

                <div class="hero-actions">

                    <a href="/events.php" class="button">
                        View Events
                    </a>

                    <a href="/connect.php" class="button button-secondary">
                        Connect With Us
                    </a>

                </div>

            </div>

        </div>

    </section>

</main>

<?php

include __DIR__ . '/includes/footer.php';

?>
