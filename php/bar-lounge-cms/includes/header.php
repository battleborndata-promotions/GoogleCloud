<?php

require_once __DIR__ . '/../config/site.php';

$pageTitle = $pageTitle ?? 'Home';

$fullPageTitle =
    $pageTitle . ' | ' . $siteConfig['page_title_suffix'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?php echo htmlspecialchars($fullPageTitle); ?></title>

    <link rel="stylesheet" href="/../css/style.css">
</head>

<body>

<header class="site-header">

    <div class="header-container">

        <a class="site-logo" href="index.php">
            <?php echo htmlspecialchars($siteConfig['business_name']); ?>
        </a>

        <nav class="site-nav" aria-label="Main navigation">

            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <a href="about.php">About</a>
            <a href="connect.php">Connect</a>

        </nav>

    </div>

</header>
