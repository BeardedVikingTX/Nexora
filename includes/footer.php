```php
<?php
/**
 * Nexora Social Platform
 * ============================================================
 * GLOBAL FOOTER / SITE CLOSURE
 *
 * Responsibilities:
 * - Brand closure
 * - Mission statement
 * - Core navigation
 * - Privacy/security principles
 * - Contact channel
 * - Client-only telemetry
 * - Dynamic year
 * - Back-to-top interaction
 * - Progressive enhancement
 * ============================================================
 */

declare(strict_types=1);

if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}


/*
|--------------------------------------------------------------------------
| Current Request
|--------------------------------------------------------------------------
*/

$currentPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

if (!is_string($currentPath) || $currentPath === '') {
    $currentPath = '/';
}


/*
|--------------------------------------------------------------------------
| Authentication State
|--------------------------------------------------------------------------
*/

$footerAuthenticated =
    !empty($_SESSION['nexora_user']['id'])
    ||
    !empty($_SESSION['nexora_user_id'])
    ||
    !empty($_SESSION['user_id']);

?>

<!-- =========================================================
     NEXORA GLOBAL FOOTER
     ========================================================= -->

<footer
    id="nexora-footer"
    class="nexora-footer"
>


    <!-- =====================================================
         VISUAL SIGNAL
         ===================================================== -->

    <div
        class="nexora-footer-signal"
        aria-hidden="true"
    >
        <span></span>
    </div>


    <div class="nexora-footer-shell">


        <!-- =================================================
             BRAND / MISSION
             ================================================= -->

        <section
            class="nexora-footer-identity"
            aria-labelledby="nexora-footer-title"
        >

            <div class="nexora-footer-brand">

                <span
                    class="nexora-brand-mark"
                    aria-hidden="true"
                >
                    NX
                </span>

                <div>

                    <h2 id="nexora-footer-title">
                        NEXORA
                    </h2>

                    <span>
                        SOCIAL INFRASTRUCTURE
                    </span>

                </div>

            </div>


            <p class="nexora-footer-mission">

                A privacy-first social environment built around
                human connection, meaningful participation,
                and security by design.

            </p>


            <!-- PRINCIPLES -->

            <div
                class="nexora-footer-principles"
                aria-label="Nexora principles"
            >

                <span>

                    <i
                        class="fa-solid fa-shield-halved"
                        aria-hidden="true"
                    ></i>

                    Privacy by Design

                </span>


                <span>

                    <i
                        class="fa-solid fa-ban"
                        aria-hidden="true"
                    ></i>

                    No Ads

                </span>


                <span>

                    <i
                        class="fa-solid fa-database"
                        aria-hidden="true"
                    ></i>

                    No Data Selling

                </span>


                <span>

                    <i
                        class="fa-solid fa-lock"
                        aria-hidden="true"
                    ></i>

                    Security First

                </span>

            </div>

        </section>


        <!-- =================================================
             FOOTER NAVIGATION
             ================================================= -->

        <nav
            class="nexora-footer-nav"
            aria-label="Footer navigation"
        >


            <!-- EXPLORE -->

            <div class="nexora-footer-nav-group">

                <span class="nexora-footer-label">
                    EXPLORE
                </span>


                <a
                    href="/"
                    <?= $currentPath === '/'
                        ? 'aria-current="page"'
                        : '' ?>
                >
                    Home
                </a>


                <a
                    href="/about.php"
                    <?= $currentPath === '/about.php'
                        ? 'aria-current="page"'
                        : '' ?>
                >
                    About Nexora
                </a>


                <a
                    href="/contact.php"
                    <?= $currentPath === '/contact.php'
                        ? 'aria-current="page"'
                        : '' ?>
                >
                    Contact Operations
                </a>

            </div>


            <!-- IDENTITY -->

            <div class="nexora-footer-nav-group">

                <span class="nexora-footer-label">
                    IDENTITY
                </span>


                <?php if ($footerAuthenticated): ?>

                    <a href="/users/dashboard.php">
                        Command Center
                    </a>

                    <a href="/users/profile.php">
                        Profile
                    </a>

                    <a href="/users/friends.php">
                        Network
                    </a>

                    <a href="/users/messages.php">
                        Messages
                    </a>

                    <a href="/users/settings.php">
                        Settings
                    </a>

                <?php else: ?>

                    <a href="/login.php">
                        Sign in
                    </a>

                    <a href="/register.php">
                        Join Nexora
                    </a>

                <?php endif; ?>

            </div>


            <!-- PRINCIPLES -->

            <div class="nexora-footer-nav-group">

                <span class="nexora-footer-label">
                    PRINCIPLES
                </span>


                <span class="nexora-footer-static">
                    Privacy-first architecture
                </span>


                <span class="nexora-footer-static">
                    Security-conscious design
                </span>


                <span class="nexora-footer-static">
                    Human-centered connection
                </span>


                <span class="nexora-footer-static">
                    No advertising model
                </span>

            </div>

        </nav>


        <!-- =================================================
             CLIENT TELEMETRY
             ================================================= -->

        <section
            class="nexora-footer-telemetry"
            aria-labelledby="nexora-footer-telemetry-title"
        >

            <div class="nexora-footer-telemetry-head">

                <div>

                    <span class="nexora-footer-label">
                        CLIENT TELEMETRY
                    </span>

                    <h3 id="nexora-footer-telemetry-title">
                        Your browser
                    </h3>

                </div>


                <span
                    id="nexora-client-state"
                    class="nexora-footer-client-state"
                >

                    <i aria-hidden="true"></i>

                    DETECTING

                </span>

            </div>


            <dl class="nexora-footer-telemetry-grid">


                <!-- CONNECTION -->

                <div>

                    <dt>
                        CONNECTION
                    </dt>

                    <dd id="nexora-footer-connection">
                        —
                    </dd>

                </div>


                <!-- TIME -->

                <div>

                    <dt>
                        TIME
                    </dt>

                    <dd id="nexora-footer-time">
                        —
                    </dd>

                </div>


                <!-- ENGINE -->

                <div>

                    <dt>
                        ENGINE
                    </dt>

                    <dd id="nexora-footer-engine">
                        —
                    </dd>

                </div>


                <!-- DISPLAY -->

                <div>

                    <dt>
                        DISPLAY
                    </dt>

                    <dd id="nexora-footer-display">
                        —
                    </dd>

                </div>


            </dl>


            <p class="nexora-footer-telemetry-note">

                Client telemetry is generated locally by your
                browser and is not presented here as a claim
                about Nexora server health.

            </p>

        </section>

    </div>


    <!-- =====================================================
         FOOTER BOTTOM BAR
         ===================================================== -->

    <div class="nexora-footer-bottom">

        <div class="nexora-footer-bottom-inner">


            <!-- LEGAL -->

            <div class="nexora-footer-legal">

                <span>

                    ©

                    <span id="nexora-footer-year">
                        <?= e((string) date('Y')) ?>
                    </span>

                    Nexora

                </span>


                <span
                    aria-hidden="true"
                    class="nexora-footer-separator"
                >
                    /
                </span>


                <span>
                    Built with privacy in mind.
                </span>

            </div>


            <!-- SYSTEM / RETURN -->

            <div class="nexora-footer-bottom-actions">


                <span class="nexora-footer-build">

                    <span>
                        CLIENT
                    </span>

                    <code id="nexora-footer-build-value">
                        NX-SHELL
                    </code>

                </span>


                <button
                    type="button"
                    id="nexora-back-to-top"
                    class="nexora-back-to-top"
                    aria-label="Back to top"
                    title="Back to top"
                >

                    <i
                        class="fa-solid fa-arrow-up"
                        aria-hidden="true"
                    ></i>

                </button>

            </div>

        </div>

    </div>

</footer>


<!-- =========================================================
     NEXORA SITE SHELL JAVASCRIPT
     ========================================================= -->

<script src="/assets/js/site-shell.js" defer></script>
```
