<?php

require_once __DIR__ . '/database.php';

$siteConfig = [
    'business_name' => 'Vassar Lounge',
    'tagline' => 'Your Neighborhood Hangout',
    'address' => '123 Main St, Reno, NV 89502',
    'phone' => '',
    'email' => '',
    'instagram_url' => '',
    'facebook_url' => '',
    'page_title_suffix' => 'Vassar Lounge',
    'hero_heading' => 'Your Neighborhood Hangout',
    'hero_text' => 'Drinks, events, games, and good company in Reno.',
    'hero_image' => '/images/IMG_1362.png'
];

try {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->query(
        'SELECT setting_key, setting_value
         FROM site_settings'
    );

    $settings = $stmt->fetchAll();

    foreach ($settings as $setting) {
        $key = $setting['setting_key'];
        $value = $setting['setting_value'];

        if (array_key_exists($key, $siteConfig)) {
            $siteConfig[$key] = $value;
        }
    }
} catch (Throwable $e) {
    error_log(
        'Could not load site settings: ' . $e->getMessage()
    );
}
