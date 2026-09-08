<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/footer.php
 * Purpose: Global footer / application shutdown markup
 * ------------------------------------------------------------
 *
 * Dynamic behavior is intentionally delegated to:
 *
 *     /assets/js/functions.js
 *
 * Styling is delegated to:
 *
 *     /assets/css/main.css
 *
 * ------------------------------------------------------------
 */

declare(strict_types=1);

$currentYear = (int) date('Y');

?>

```
    </main>

    <!-- ================================================= -->
    <!-- NEXORA FOOTER                                    -->
    <!-- ================================================= -->

    <footer
        id="nexora-footer"
        class="nexora-footer"
        data-nexora-component="footer"
    >

        <!-- ============================================= -->
        <!-- FOOTER SYSTEM STATUS                         -->
        <!-- ============================================= -->

        <div
            class="nexora-footer-status"
            aria-live="polite"
        >

            <div class="container-fluid">

                <div class="nexora-system-status">

                    <span
                        class="nexora-status-indicator"
                        id="nexora-status-indicator"
                        aria-hidden="true"
                    ></span>

                    <span
                        id="nexora-system-status"
                        data-nexora-live="system-status"
                    >
                        NEXORA SYSTEM ONLINE
                    </span>

                    <span
                        class="nexora-status-separator"
                        aria-hidden="true"
                    >
                        //
                    </span>

                    <span
                        id="nexora-client-time"
                        data-nexora-live="client-time"
                    >
                        Synchronizing...
                    </span>

                </div>

            </div>

        </div>

        <!-- ============================================= -->
        <!-- FOOTER CONTENT                                -->
        <!-- ============================================= -->

        <div class="container-fluid nexora-footer-container">

            <div class="row g-4">

                <!-- ===================================== -->
                <!-- BRAND / MISSION                       -->
                <!-- ===================================== -->

                <div class="col-12 col-lg-5">

                    <section
                        class="nexora-footer-section nexora-footer-brand"
                        aria-labelledby="footer-brand-title"
                    >

                        <h2
                            id="footer-brand-title"
                            class="nexora-footer-title"
                        >

                            <i
                                class="fa-solid fa-atom"
                                aria-hidden="true"
                            ></i>

                            NEXORA

                        </h2>

                        <p class="nexora-footer-description">

                            The next generation of social connection.

                            A security-focused, futuristic community
                            built around identity, communication,
                            discovery, reputation, and human connection.

                        </p>

                        <div
                            class="nexora-footer-tagline"
                            aria-label="Nexora mission"
                        >
                            CONNECT BEYOND THE ORDINARY.
                        </div>

                    </section>

                </div>

                <!-- ===================================== -->
                <!-- QUICK NAVIGATION                     -->
                <!-- ===================================== -->

                <div class="col-6 col-md-4 col-lg-2">

                    <section
                        class="nexora-footer-section"
                        aria-labelledby="footer-navigation-title"
                    >

                        <h2
                            id="footer-navigation-title"
                            class="nexora-footer-heading"
                        >
                            Navigation
                        </h2>

                        <ul class="nexora-footer-links">

                            <li>
                                <a
                                    href="/index.php"
                                    data-nexora-footer-link="home"
                                >
                                    <i
                                        class="fa-solid fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Home
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/about.php"
                                    data-nexora-footer-link="about"
                                >
                                    <i
                                        class="fa-solid fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    About
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/contact.php"
                                    data-nexora-footer-link="contact"
                                >
                                    <i
                                        class="fa-solid fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Contact
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/login.php"
                                    data-nexora-footer-link="login"
                                >
                                    <i
                                        class="fa-solid fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Login
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/register.php"
                                    data-nexora-footer-link="register"
                                >
                                    <i
                                        class="fa-solid fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Register
                                </a>
                            </li>

                        </ul>

                    </section>

                </div>

                <!-- ===================================== -->
                <!-- RESOURCES                             -->
                <!-- ===================================== -->

                <div class="col-6 col-md-4 col-lg-2">

                    <section
                        class="nexora-footer-section"
                        aria-labelledby="footer-resources-title"
                    >

                        <h2
                            id="footer-resources-title"
                            class="nexora-footer-heading"
                        >
                            Resources
                        </h2>

                        <ul class="nexora-footer-links">

                            <li>
                                <a
                                    href="/sitemap.xml"
                                    rel="sitemap"
                                    data-nexora-footer-link="sitemap"
                                >
                                    <i
                                        class="fa-solid fa-sitemap"
                                        aria-hidden="true"
                                    ></i>
                                    Sitemap
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/about.php"
                                    data-nexora-footer-link="project"
                                >
                                    <i
                                        class="fa-solid fa-microchip"
                                        aria-hidden="true"
                                    ></i>
                                    Project
                                </a>
                            </li>

                            <li>
                                <a
                                    href="/contact.php"
                                    data-nexora-footer-link="support"
                                >
                                    <i
                                        class="fa-solid fa-headset"
                                        aria-hidden="true"
                                    ></i>
                                    Support
                                </a>
                            </li>

                        </ul>

                    </section>

                </div>

                <!-- ===================================== -->
                <!-- LIVE TELEMETRY                       -->
                <!-- ===================================== -->

                <div class="col-12 col-md-4 col-lg-3">

                    <section
                        class="nexora-footer-section nexora-telemetry"
                        aria-labelledby="footer-telemetry-title"
                    >

                        <h2
                            id="footer-telemetry-title"
                            class="nexora-footer-heading"
                        >
                            <i
                                class="fa-solid fa-satellite"
                                aria-hidden="true"
                            ></i>

                            Telemetry
                        </h2>

                        <div
                            class="nexora-telemetry-panel"
                            id="nexora-telemetry-panel"
                            data-nexora-telemetry="footer"
                        >

                            <div class="nexora-telemetry-row">

                                <span>
                                    System
                                </span>

                                <strong
                                    id="nexora-telemetry-system"
                                >
                                    ONLINE
                                </strong>

                            </div>

                            <div class="nexora-telemetry-row">

                                <span>
                                    Interface
                                </span>

                                <strong
                                    id="nexora-telemetry-interface"
                                >
                                    READY
                                </strong>

                            </div>

                            <div class="nexora-telemetry-row">

                                <span>
                                    Connection
                                </span>

                                <strong
                                    id="nexora-telemetry-connection"
                                >
                                    STANDBY
                                </strong>

                            </div>

                            <div class="nexora-telemetry-row">

                                <span>
                                    Client
                                </span>

                                <strong
                                    id="nexora-telemetry-client"
                                >
                                    WEB
                                </strong>

                            </div>

                        </div>

                    </section>

                </div>

            </div>

        </div>

        <!-- ============================================= -->
        <!-- FOOTER COMMAND BAR                            -->
        <!-- ============================================= -->

        <div class="nexora-footer-command-bar">

            <div class="container-fluid">

                <div class="nexora-footer-command-content">

                    <span
                        class="nexora-footer-copyright"
                        id="nexora-copyright"
                    >
                        &copy;
                        <?= htmlspecialchars((string) $currentYear, ENT_QUOTES, 'UTF-8') ?>
                        Nexora.
                        All systems reserved.
                    </span>

                    <span
                        class="nexora-footer-version"
                        data-nexora-version="display"
                    >
                        NEXORA // CORE
                    </span>

                    <button
                        type="button"
                        id="nexora-back-to-top"
                        class="nexora-back-to-top"
                        aria-label="Return to the top of the page"
                        data-nexora-action="back-to-top"
                    >

                        <span>
                            Return to top
                        </span>

                        <i
                            class="fa-solid fa-arrow-up"
                            aria-hidden="true"
                        ></i>

                    </button>

                </div>

            </div>

        </div>

    </footer>

</div>

<!-- ===================================================== -->
<!-- BOOTSTRAP JAVASCRIPT                                  -->
<!-- ===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
></script>

<!-- ===================================================== -->
<!-- NEXORA JAVASCRIPT                                    -->
<!-- ===================================================== -->

<script
    src="/assets/js/functions.js"
    defer
></script>
```

</body>
</html>
