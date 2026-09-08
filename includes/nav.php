<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/nav.php
 * Purpose: Global responsive navigation system
 * ------------------------------------------------------------
 *
 * Expected variables:
 * - $currentPage (optional)
 *
 * The navigation is intentionally presentation-light.
 * Visual styling belongs in:
 *
 *     /assets/css/main.css
 *
 * Dynamic behavior belongs in:
 *
 *     /assets/js/functions.js
 *
 * ------------------------------------------------------------
 */

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Determine Current Page
|--------------------------------------------------------------------------
*/

$currentPage = $currentPage
    ?? basename($_SERVER['PHP_SELF'] ?? 'index.php');

/*
|--------------------------------------------------------------------------
| Navigation Helper
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_nav_active')) {

    function nexora_nav_active(
        string $page,
        string $currentPage
    ): string {
        return $page === $currentPage
            ? 'active'
            : '';
    }
}

/*
|--------------------------------------------------------------------------
| Navigation URLs
|--------------------------------------------------------------------------
|
| Centralized paths make future routing changes easier.
|
*/

$homeUrl    = '/index.php';
$aboutUrl   = '/about.php';
$contactUrl = '/contact.php';
$loginUrl   = '/login.php';
$registerUrl = '/register.php';

?>

<!-- ========================================================= -->

<!-- NEXORA PRIMARY NAVIGATION                                -->

<!-- ========================================================= -->

<nav
    id="nexora-navigation"
    class="nexora-navbar navbar navbar-expand-lg"
    aria-label="Primary navigation"
    data-nexora-component="navigation"
>

```
<div class="container-fluid nexora-navbar-container">

    <!-- ================================================= -->
    <!-- BRAND / HOME                                      -->
    <!-- ================================================= -->

    <a
        href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>"
        class="navbar-brand nexora-brand"
        aria-label="Nexora home"
        data-nexora-action="home"
    >

        <span
            class="nexora-brand-icon"
            aria-hidden="true"
        >
            <i class="fa-solid fa-atom"></i>
        </span>

        <span class="nexora-brand-text">
            NEXORA
        </span>

    </a>

    <!-- ================================================= -->
    <!-- MOBILE NAVIGATION TOGGLE                          -->
    <!-- ================================================= -->

    <button
        class="navbar-toggler nexora-navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#nexoraPrimaryNavigation"
        aria-controls="nexoraPrimaryNavigation"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >

        <span
            class="navbar-toggler-icon"
            aria-hidden="true"
        ></span>

        <span
            class="nexora-menu-label"
        >
            MENU
        </span>

    </button>

    <!-- ================================================= -->
    <!-- NAVIGATION CONTENT                                -->
    <!-- ================================================= -->

    <div
        id="nexoraPrimaryNavigation"
        class="collapse navbar-collapse nexora-navigation-collapse"
    >

        <!-- ============================================= -->
        <!-- RIGHT-SIDE NAVIGATION                         -->
        <!-- ============================================= -->

        <ul
            class="navbar-nav ms-auto align-items-lg-center nexora-nav-list"
            id="nexora-nav-list"
        >

            <!-- ========================================= -->
            <!-- HOME                                      -->
            <!-- ========================================= -->

            <li class="nav-item">

                <a
                    href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link nexora-nav-link <?= nexora_nav_active('index.php', $currentPage) ?>"
                    <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>
                    data-nexora-nav="home"
                >

                    <i
                        class="fa-solid fa-house"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Home
                    </span>

                </a>

            </li>

            <!-- ========================================= -->
            <!-- ABOUT                                     -->
            <!-- ========================================= -->

            <li class="nav-item">

                <a
                    href="<?= htmlspecialchars($aboutUrl, ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link nexora-nav-link <?= nexora_nav_active('about.php', $currentPage) ?>"
                    <?= $currentPage === 'about.php' ? 'aria-current="page"' : '' ?>
                    data-nexora-nav="about"
                >

                    <i
                        class="fa-solid fa-circle-info"
                        aria-hidden="true"
                    ></i>

                    <span>
                        About
                    </span>

                </a>

            </li>

            <!-- ========================================= -->
            <!-- CONTACT                                   -->
            <!-- ========================================= -->

            <li class="nav-item">

                <a
                    href="<?= htmlspecialchars($contactUrl, ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link nexora-nav-link <?= nexora_nav_active('contact.php', $currentPage) ?>"
                    <?= $currentPage === 'contact.php' ? 'aria-current="page"' : '' ?>
                    data-nexora-nav="contact"
                >

                    <i
                        class="fa-solid fa-satellite-dish"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Contact
                    </span>

                </a>

            </li>

            <!-- ========================================= -->
            <!-- NAVIGATION DIVIDER                        -->
            <!-- ========================================= -->

            <li
                class="nav-item nexora-nav-divider"
                aria-hidden="true"
            >
                <span></span>
            </li>

            <!-- ========================================= -->
            <!-- LOGIN                                     -->
            <!-- ========================================= -->

            <li class="nav-item">

                <a
                    href="<?= htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link nexora-nav-link nexora-login-link"
                    data-nexora-nav="login"
                >

                    <i
                        class="fa-solid fa-right-to-bracket"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Login
                    </span>

                </a>

            </li>

            <!-- ========================================= -->
            <!-- REGISTER                                  -->
            <!-- ========================================= -->

            <li class="nav-item nexora-register-item">

                <a
                    href="<?= htmlspecialchars($registerUrl, ENT_QUOTES, 'UTF-8') ?>"
                    class="btn nexora-register-button"
                    data-nexora-nav="register"
                >

                    <i
                        class="fa-solid fa-user-astronaut"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Register
                    </span>

                </a>

            </li>

        </ul>

    </div>

</div>
```

</nav>

<!-- ========================================================= -->

<!-- NAVIGATION STATUS / AJAX HOOK                             -->

<!-- ========================================================= -->

<div
    id="nexora-navigation-status"
    class="nexora-navigation-status visually-hidden"
    role="status"
    aria-live="polite"
    data-nexora-live="navigation"
></div>
