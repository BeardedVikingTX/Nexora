<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/header.php
 * Purpose: Global application header / document bootstrap
 * ------------------------------------------------------------
 */

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Nexora Bootstrap Flag
|--------------------------------------------------------------------------
|
| Must be defined before cookies.php is included.
|
*/

if (!defined('NEXORA_BOOTSTRAPPED')) {
    define('NEXORA_BOOTSTRAPPED', true);
}

/*
|--------------------------------------------------------------------------
| Secure Session / Cookie Initialization
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/cookies.php';
require_once __DIR__ . '/database.php';

/*
|--------------------------------------------------------------------------
| Application Defaults
|--------------------------------------------------------------------------
*/

$pageTitle = $pageTitle ?? 'Nexora';

$pageDescription = $pageDescription
    ?? 'Nexora — The Next Generation of Social Connection.';

$pageKeywords = $pageKeywords
    ?? 'Nexora, social network, community, futuristic social platform';

$pageRobots = $pageRobots
    ?? 'index, follow';

/*
|--------------------------------------------------------------------------
| Escape Helper
|--------------------------------------------------------------------------
|
| Safe HTML output helper.
|
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
| Security Headers
|--------------------------------------------------------------------------
|
| These are intentionally established before HTML output.
|
*/

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

/*
|--------------------------------------------------------------------------
| Content Security Policy
|--------------------------------------------------------------------------
|
| This is intentionally restrictive while still allowing the initial
| Nexora frontend stack.
|
| As the project matures, we can tighten this substantially and move
| additional resources locally.
|
*/

header(
    "Content-Security-Policy: " .
    "default-src 'self'; " .
    "base-uri 'self'; " .
    "form-action 'self'; " .
    "frame-ancestors 'self'; " .
    "object-src 'none'; " .
    "script-src 'self' https://cdn.jsdelivr.net; " .
    "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
    "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
    "img-src 'self' data: blob:; " .
    "connect-src 'self';"
);

/*
|--------------------------------------------------------------------------
| HSTS
|--------------------------------------------------------------------------
|
| Only send HSTS when the current request is HTTPS.
|
*/

$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ||
    (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
);

if ($isHttps) {
    header(
        'Strict-Transport-Security: max-age=31536000; includeSubDomains'
    );
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

    <title><?= e($pageTitle) ?> | Nexora</title>

    <!-- ===================================================== -->
    <!-- Google Fonts                                          -->
    <!-- ===================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- ===================================================== -->
    <!-- Bootstrap 5                                           -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    >

    <!-- ===================================================== -->
    <!-- Font Awesome                                           -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
    >

    <!-- ===================================================== -->
    <!-- Nexora Core CSS                                        -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="/assets/css/main.css"
    >

</head>

<body>

    <!-- ===================================================== -->
    <!-- Nexora Application Interface                           -->
    <!-- ===================================================== -->

    <div
        id="nexora-app"
        class="nexora-app"
    >

        <!-- ================================================= -->
        <!-- Navigation                                         -->
        <!-- ================================================= -->

        <?php
        $navFile = __DIR__ . '/nav.php';

        if (is_file($navFile)) {
            require_once $navFile;
        }
        ?>

        <!-- ================================================= -->
        <!-- Main Content                                      -->
        <!-- ================================================= -->

        <main
            id="main-content"
            class="nexora-main"
        >