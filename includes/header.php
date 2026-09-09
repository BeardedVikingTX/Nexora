<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/header.php
 * Purpose: Global application bootstrap + secure document head
 * ------------------------------------------------------------
 */

declare(strict_types=1);


/*
|--------------------------------------------------------------------------
| Application Bootstrap
|--------------------------------------------------------------------------
*/

if (!defined('NEXORA_BOOTSTRAPPED')) {
    define('NEXORA_BOOTSTRAPPED', true);
}

require_once __DIR__ . '/cookies.php';
require_once __DIR__ . '/database.php';


/*
|--------------------------------------------------------------------------
| Application Timezone
|--------------------------------------------------------------------------
*/

$config = nexora_db_config();

$timezone = (string) (
    $config['app']['timezone'] ?? 'America/Chicago'
);

if (@date_default_timezone_set($timezone) === false) {
    date_default_timezone_set('UTC');
}


/*
|--------------------------------------------------------------------------
| Page Metadata Defaults
|--------------------------------------------------------------------------
*/

$pageTitle = $pageTitle ?? 'Nexora';

$pageDescription = $pageDescription
    ?? 'Nexora — The Next Generation of Social Connection.';

$pageKeywords = $pageKeywords
    ?? 'Nexora, social network, social media, privacy, security, community';

$pageRobots = $pageRobots
    ?? 'index, follow';


/*
|--------------------------------------------------------------------------
| HTML Escaping
|--------------------------------------------------------------------------
*/

if (!function_exists('e')) {

    function e(?string $value): string
    {
        return htmlspecialchars(
            $value ?? '',
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
}


/*
|--------------------------------------------------------------------------
| HTTPS
|--------------------------------------------------------------------------
*/

$isHttps = nexora_is_https();


/*
|--------------------------------------------------------------------------
| Security Headers
|--------------------------------------------------------------------------
*/

header('X-Content-Type-Options: nosniff');

header('X-Frame-Options: SAMEORIGIN');

header(
    'Referrer-Policy: strict-origin-when-cross-origin'
);

header(
    'Permissions-Policy: '
    . 'camera=(), '
    . 'microphone=(), '
    . 'geolocation=(), '
    . 'payment=(), '
    . 'usb=(), '
    . 'bluetooth=()'
);


/*
|--------------------------------------------------------------------------
| Cache-Control
|--------------------------------------------------------------------------
|
| Dynamic/authenticated pages should never be publicly cached.
|--------------------------------------------------------------------------
*/

header(
    'Cache-Control: private, no-store, max-age=0, '
    . 'must-revalidate'
);

header('Pragma: no-cache');


/*
|--------------------------------------------------------------------------
| HSTS
|--------------------------------------------------------------------------
*/

if ($isHttps) {

    header(
        'Strict-Transport-Security: '
        . 'max-age=31536000; includeSubDomains'
    );
}


/*
|--------------------------------------------------------------------------
| Content Security Policy
|--------------------------------------------------------------------------
|
| Phase 1 uses only same-origin resources.
|
| We are deliberately removing:
|
| - Google Fonts CDN
| - jsDelivr
| - cdnjs
|
| Once all existing pages have been migrated away from inline
| JavaScript and inline styles, this can be tightened further.
|--------------------------------------------------------------------------
*/

header(
    "Content-Security-Policy: "
    . "default-src 'self'; "
    . "base-uri 'self'; "
    . "form-action 'self'; "
    . "frame-ancestors 'self'; "
    . "object-src 'none'; "
    . "script-src 'self'; "
    . "style-src 'self' 'unsafe-inline'; "
    . "font-src 'self'; "
    . "img-src 'self' data: blob:; "
    . "media-src 'self' blob:; "
    . "connect-src 'self'; "
    . "worker-src 'self' blob:; "
    . "manifest-src 'self';"
);


/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

$canonicalUrl = rtrim(
    (string) ($config['app']['base_url'] ?? ''),
    '/'
);

$currentPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

if (!is_string($currentPath) || $currentPath === '') {
    $currentPath = '/';
}

$canonical = $canonicalUrl . $currentPath;


/*
|--------------------------------------------------------------------------
| Optional Open Graph Metadata
|--------------------------------------------------------------------------
*/

$ogTitle = $ogTitle ?? $pageTitle . ' | Nexora';

$ogDescription = $ogDescription
    ?? $pageDescription;

$ogType = $ogType ?? 'website';

$ogImage = $ogImage
    ?? $canonicalUrl . '/assets/img/media/ChatGPT_Homepage.png';


/*
|--------------------------------------------------------------------------
| Security / Error Behavior
|--------------------------------------------------------------------------
*/

if (
    ($config['app']['environment'] ?? 'production')
    === 'production'
) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="description"
        content="<?= e($pageDescription) ?>"
    >

    <meta
        name="keywords"
        content="<?= e($pageKeywords) ?>"
    >

    <meta
        name="robots"
        content="<?= e($pageRobots) ?>"
    >

    <meta
        name="author"
        content="Nexora"
    >

    <meta
        name="theme-color"
        content="#050816"
    >

    <meta
        name="csrf-token"
        content="<?= e(nexora_csrf_token()) ?>"
    >

    <link
        rel="canonical"
        href="<?= e($canonical) ?>"
    >

    <meta
        property="og:title"
        content="<?= e($ogTitle) ?>"
    >

    <meta
        property="og:description"
        content="<?= e($ogDescription) ?>"
    >

    <meta
        property="og:type"
        content="<?= e($ogType) ?>"
    >

    <meta
        property="og:url"
        content="<?= e($canonical) ?>"
    >

    <meta
        property="og:image"
        content="<?= e($ogImage) ?>"
    >

    <meta
        name="twitter:card"
        content="summary_large_image"
    >

    <meta
        name="twitter:title"
        content="<?= e($ogTitle) ?>"
    >

    <meta
        name="twitter:description"
        content="<?= e($ogDescription) ?>"
    >

    <meta
        name="twitter:image"
        content="<?= e($ogImage) ?>"
    >

    <title><?= e($pageTitle) ?> | Nexora</title>


    <!-- ===================================================== -->
    <!-- LOCAL VENDOR CSS                                      -->
    <!-- ===================================================== -->

    <!-- Bootstrap -->
    <link
        rel="stylesheet"
        href="/assets/vendors/Bootstrap/css/bootstrap.min.css"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="/assets/vendors/FontAwesome/css/all.min.css"
    >

    <!-- Nexora -->
    <link
        rel="stylesheet"
        href="/assets/css/main.css"
    >

</head>

<body>

<div
    id="nexora-app"
    class="nexora-app"
>

    <?php

    $navFile = __DIR__ . '/nav.php';

    if (is_file($navFile)) {
        require_once $navFile;
    }

    ?>

    <main
        id="main-content"
        class="nexora-main"
    >