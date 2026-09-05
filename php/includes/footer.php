<footer class="site-footer">

    <div class="footer-container">

        <div class="footer-business">

            <h2>
                <?php echo htmlspecialchars($siteConfig['business_name']); ?>
            </h2>

            <p>
                <?php echo htmlspecialchars($siteConfig['tagline']); ?>
            </p>

        </div>

        <div class="footer-contact">

            <h3>Connect With Us</h3>

            <?php if (!empty($siteConfig['address'])): ?>
                <p>
                    <?php echo htmlspecialchars($siteConfig['address']); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($siteConfig['phone'])): ?>
                <p>
                    <?php echo htmlspecialchars($siteConfig['phone']); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($siteConfig['email'])): ?>
                <p>
                    <?php echo htmlspecialchars($siteConfig['email']); ?>
                </p>
            <?php endif; ?>

        </div>

        <div class="footer-social">

            <?php if (!empty($siteConfig['instagram_url'])): ?>
                <a
                    href="<?php echo htmlspecialchars($siteConfig['instagram_url']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Instagram
                </a>
            <?php endif; ?>

            <?php if (!empty($siteConfig['facebook_url'])): ?>
                <a
                    href="<?php echo htmlspecialchars($siteConfig['facebook_url']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Facebook
                </a>
            <?php endif; ?>

        </div>

    </div>

    <div class="footer-bottom">

        <p>
            &copy;
            <?php echo date('Y'); ?>
            <?php echo htmlspecialchars($siteConfig['business_name']); ?>.
            All rights reserved.
        </p>

    </div>

</footer>

<script src="js/script.js"></script>

</body>
</html>
