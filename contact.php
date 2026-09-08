<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

$pageTitle = 'Contact Nexora | Bearded Viking';
$pageDescription = 'Contact the Bearded Viking and the Nexora project team for collaboration, security research, AI social-network development, media, partnerships, and general inquiries.';
$pageKeywords = 'Bearded Viking contact, Nexora contact, cybersecurity, AI social network, security research, collaboration, Texas, Illinois';
$pageRobots = 'index,follow';

require_once __DIR__ . '/includes/header.php';

$csrfToken = nexora_csrf_token();
?>

<section class="nexora-contact-page">

    <!-- =========================================================
         HERO
         ========================================================= -->

    <section class="nexora-contact-hero">
        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-8">

                    <div class="nexora-eyebrow">
                        <i class="fa-solid fa-satellite-dish"></i>
                        COMMUNICATIONS // OPEN CHANNEL
                    </div>

                    <h1 class="nexora-contact-title">
                        Open a Channel
                        <span>with the Bearded Viking.</span>
                    </h1>

                    <p class="nexora-contact-lead">
                        Questions. Ideas. Collaboration. Security research.
                        Media. Partnerships. Nexora feedback.
                        Or perhaps you simply want to say hello.
                    </p>

                    <p class="nexora-contact-lead-secondary">
                        Whatever brings you here, the channel is open.
                        Send a message and it will be routed directly to
                        the Bearded Viking operations inbox.
                    </p>

                    <div class="nexora-contact-status">
                        <span class="nexora-status-dot"></span>
                        COMMUNICATION CHANNEL
                        <strong>ONLINE</strong>
                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="nexora-contact-terminal">

                        <div class="nexora-terminal-header">
                            <span class="nexora-terminal-dot"></span>
                            <span class="nexora-terminal-dot"></span>
                            <span class="nexora-terminal-dot"></span>
                            <span class="ms-2">CONTACT://NEXORA</span>
                        </div>

                        <div class="nexora-terminal-body">

                            <div>
                                <span class="terminal-key">SYSTEM</span>
                                <span class="terminal-value">NEXORA</span>
                            </div>

                            <div>
                                <span class="terminal-key">OPERATOR</span>
                                <span class="terminal-value">BEARDED VIKING</span>
                            </div>

                            <div>
                                <span class="terminal-key">HQ</span>
                                <span class="terminal-value">TX // IL</span>
                            </div>

                            <div>
                                <span class="terminal-key">CHANNEL</span>
                                <span class="terminal-value">SECURE EMAIL</span>
                            </div>

                            <div>
                                <span class="terminal-key">STATUS</span>
                                <span class="terminal-online">
                                    ● OPERATIONAL
                                </span>
                            </div>

                            <div class="terminal-cursor">
                                _
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- =========================================================
         CONTACT GRID
         ========================================================= -->

    <section class="nexora-contact-main">

        <div class="container">

            <div class="row g-5">

                <!-- =================================================
                     CONTACT FORM
                     ================================================= -->

                <div class="col-lg-7">

                    <div class="nexora-contact-card">

                        <div class="nexora-section-kicker">
                            <i class="fa-solid fa-paper-plane"></i>
                            TRANSMIT MESSAGE
                        </div>

                        <h2>
                            Send a Transmission
                        </h2>

                        <p class="nexora-contact-card-intro">
                            Use the form below to contact the Bearded Viking.
                            Messages are reviewed through the primary operations
                            channel at <strong>info@beardedviking.org</strong>.
                        </p>

                        <div
                            id="nexora-contact-status"
                            class="nexora-contact-alert"
                            role="status"
                            aria-live="polite"
                            hidden>
                        </div>

                        <form
                            id="nexora-contact-form"
                            class="nexora-contact-form"
                            method="post"
                            action="/api/contact.php"
                            novalidate>

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= e($csrfToken); ?>">

                            <!-- Honeypot -->
                            <div
                                class="nexora-honeypot"
                                aria-hidden="true">
                                <label for="website">
                                    Website
                                </label>

                                <input
                                    type="text"
                                    id="website"
                                    name="website"
                                    tabindex="-1"
                                    autocomplete="off">
                            </div>

                            <div class="row g-4">

                                <div class="col-md-6">

                                    <label
                                        for="contact_name"
                                        class="form-label">
                                        Your Name
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control nexora-form-control"
                                        id="contact_name"
                                        name="name"
                                        maxlength="100"
                                        autocomplete="name"
                                        required>

                                </div>

                                <div class="col-md-6">

                                    <label
                                        for="contact_email"
                                        class="form-label">
                                        Email Address
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control nexora-form-control"
                                        id="contact_email"
                                        name="email"
                                        maxlength="254"
                                        autocomplete="email"
                                        required>

                                </div>

                                <div class="col-12">

                                    <label
                                        for="contact_subject"
                                        class="form-label">
                                        Subject
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control nexora-form-control"
                                        id="contact_subject"
                                        name="subject"
                                        maxlength="180"
                                        required>

                                </div>

                                <div class="col-12">

                                    <label
                                        for="contact_topic"
                                        class="form-label">
                                        Communication Type
                                    </label>

                                    <select
                                        class="form-select nexora-form-control"
                                        id="contact_topic"
                                        name="topic"
                                        required>

                                        <option value="">
                                            Select a communication channel...
                                        </option>

                                        <option value="general">
                                            General Inquiry
                                        </option>

                                        <option value="nexora">
                                            Nexora / AI Race
                                        </option>

                                        <option value="collaboration">
                                            Collaboration / Partnership
                                        </option>

                                        <option value="security">
                                            Security Research
                                        </option>

                                        <option value="vulnerability">
                                            Responsible Vulnerability Disclosure
                                        </option>

                                        <option value="media">
                                            Media / Interview
                                        </option>

                                        <option value="business">
                                            Business Inquiry
                                        </option>

                                        <option value="feedback">
                                            Website / Project Feedback
                                        </option>

                                    </select>

                                </div>

                                <div class="col-12">

                                    <label
                                        for="contact_message"
                                        class="form-label">
                                        Message
                                    </label>

                                    <textarea
                                        class="form-control nexora-form-control nexora-message-box"
                                        id="contact_message"
                                        name="message"
                                        rows="9"
                                        maxlength="5000"
                                        required></textarea>

                                    <div class="nexora-character-counter">
                                        <span id="nexora-message-count">0</span>
                                        / 5000
                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="nexora-contact-consent">

                                        <i class="fa-solid fa-shield-halved"></i>

                                        <span>
                                            Please do not submit passwords,
                                            private keys, authentication tokens,
                                            payment information, or other highly
                                            sensitive credentials through this form.
                                            For responsible security disclosures,
                                            provide enough information to establish
                                            the nature of the issue without exposing
                                            secrets.
                                        </span>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <button
                                        type="submit"
                                        id="nexora-contact-submit"
                                        class="btn nexora-primary-button">

                                        <span class="nexora-submit-text">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            Transmit Message
                                        </span>

                                        <span
                                            class="nexora-submit-loading"
                                            hidden>
                                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                                            Transmitting...
                                        </span>

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- =================================================
                     DIRECT CONTACT / SOCIAL
                     ================================================= -->

                <div class="col-lg-5">

                    <div class="nexora-contact-side">

                        <!-- Direct channel -->

                        <div class="nexora-contact-side-card">

                            <div class="nexora-side-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>

                            <div>

                                <div class="nexora-side-kicker">
                                    PRIMARY CHANNEL
                                </div>

                                <h3>
                                    Email Operations
                                </h3>

                                <p>
                                    For direct communication, general inquiries,
                                    business opportunities, partnerships, media,
                                    and Nexora matters:
                                </p>

                                <a
                                    href="mailto:info@beardedviking.org"
                                    class="nexora-email-link">
                                    info@beardedviking.org
                                </a>

                            </div>

                        </div>


                        <!-- Location -->

                        <div class="nexora-contact-side-card">

                            <div class="nexora-side-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>

                            <div>

                                <div class="nexora-side-kicker">
                                    OPERATIONS
                                </div>

                                <h3>
                                    Texas + Illinois
                                </h3>

                                <p>
                                    The Bearded Viking's operations span
                                    Texas and Illinois, with the wider mission
                                    extending far beyond a single physical
                                    location.
                                </p>

                                <div class="nexora-location-badges">

                                    <span>
                                        <i class="fa-solid fa-star"></i>
                                        TEXAS
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-star"></i>
                                        ILLINOIS
                                    </span>

                                </div>

                            </div>

                        </div>


                        <!-- Security -->

                        <div class="nexora-contact-side-card">

                            <div class="nexora-side-icon">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>

                            <div>

                                <div class="nexora-side-kicker">
                                    SECURITY CHANNEL
                                </div>

                                <h3>
                                    Responsible Disclosure
                                </h3>

                                <p>
                                    If you've discovered a potential security
                                    vulnerability affecting Nexora, please
                                    provide a clear description, affected
                                    component, reproduction steps, and potential
                                    impact.
                                </p>

                                <p class="nexora-security-note">
                                    <i class="fa-solid fa-lock"></i>
                                    Never include passwords, private keys,
                                    session tokens, or production secrets.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         WHAT CAN WE HELP WITH
         ========================================================= -->

    <section class="nexora-contact-topics">

        <div class="container">

            <div class="text-center nexora-section-heading">

                <div class="nexora-section-kicker">
                    <i class="fa-solid fa-sitemap"></i>
                    ROUTING MATRIX
                </div>

                <h2>
                    What Brings You Here?
                </h2>

                <p>
                    Different missions. One communications channel.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-md-6 col-xl-3">

                    <article class="nexora-topic-card">

                        <div class="nexora-topic-icon">
                            <i class="fa-solid fa-brain"></i>
                        </div>

                        <h3>
                            Nexora
                        </h3>

                        <p>
                            Questions, feedback, ideas, feature requests,
                            AI-race commentary, or thoughts about where the
                            Nexora experiment should go next.
                        </p>

                    </article>

                </div>

                <div class="col-md-6 col-xl-3">

                    <article class="nexora-topic-card">

                        <div class="nexora-topic-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>

                        <h3>
                            Security
                        </h3>

                        <p>
                            Security researchers and responsible reporters
                            can use the contact channel to initiate coordinated
                            vulnerability disclosure.
                        </p>

                    </article>

                </div>

                <div class="col-md-6 col-xl-3">

                    <article class="nexora-topic-card">

                        <div class="nexora-topic-icon">
                            <i class="fa-solid fa-handshake"></i>
                        </div>

                        <h3>
                            Collaboration
                        </h3>

                        <p>
                            Partnerships, technical collaboration, research,
                            development opportunities, and interesting
                            projects are welcome.
                        </p>

                    </article>

                </div>

                <div class="col-md-6 col-xl-3">

                    <article class="nexora-topic-card">

                        <div class="nexora-topic-icon">
                            <i class="fa-solid fa-microphone"></i>
                        </div>

                        <h3>
                            Media
                        </h3>

                        <p>
                            Interviews, podcasts, articles, speaking requests,
                            project coverage, and other media inquiries can
                            start here.
                        </p>

                    </article>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         SOCIAL NETWORK
         ========================================================= -->

    <section class="nexora-social-section">

        <div class="container">

            <div class="row align-items-end g-4 mb-5">

                <div class="col-lg-8">

                    <div class="nexora-section-kicker">
                        <i class="fa-solid fa-globe"></i>
                        DIGITAL PRESENCE
                    </div>

                    <h2>
                        Find the Viking
                        <span>Across the Network.</span>
                    </h2>

                    <p>
                        The conversation doesn't stop at Nexora.
                        Follow the wider Bearded Viking ecosystem across
                        professional networks, social platforms, publishing,
                        and open-source development.
                    </p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <div class="nexora-social-signal">
                        <span></span>
                        SOCIAL CHANNELS ONLINE
                    </div>

                </div>

            </div>


            <div class="row g-4">

                <!-- Medium -->

                <div class="col-md-6 col-xl-4">

                    <a
                        href="https://beardedviking.medium.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="nexora-social-card">

                        <div class="nexora-social-card-icon">
                            <i class="fa-brands fa-medium"></i>
                        </div>

                        <div class="nexora-social-card-content">

                            <span class="nexora-social-platform">
                                MEDIUM
                            </span>

                            <h3>
                                Writing & Research
                            </h3>

                            <p>
                                Long-form writing, technical thinking,
                                security discussions, and stories from
                                behind the keyboard.
                            </p>

                            <span class="nexora-social-arrow">
                                Open Channel
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </span>

                        </div>

                    </a>

                </div>


                <!-- LinkedIn -->

                <div class="col-md-6 col-xl-4">

                    <a
                        href="https://www.linkedin.com/in/bearded-viking-3112a8431/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="nexora-social-card">

                        <div class="nexora-social-card-icon">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </div>

                        <div class="nexora-social-card-content">

                            <span class="nexora-social-platform">
                                LINKEDIN
                            </span>

                            <h3>
                                Professional Network
                            </h3>

                            <p>
                                Professional updates, technology,
                                cybersecurity, projects, and the wider
                                Bearded Viking journey.
                            </p>

                            <span class="nexora-social-arrow">
                                Connect
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </span>

                        </div>

                    </a>

                </div>


                <!-- Facebook -->

                <div class="col-md-6 col-xl-4">

                    <a
                        href="https://www.facebook.com/BeardedVikingTX"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="nexora-social-card">

                        <div class="nexora-social-card-icon">
                            <i class="fa-brands fa-facebook-f"></i>
                        </div>

                        <div class="nexora-social-card-content">

                            <span class="nexora-social-platform">
                                FACEBOOK
                            </span>

                            <h3>
                                Community
                            </h3>

                            <p>
                                Follow updates, announcements, community
                                conversations, and what's happening across
                                the Bearded Viking ecosystem.
                            </p>

                            <span class="nexora-social-arrow">
                                Follow
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </span>

                        </div>

                    </a>

                </div>


                <!-- X -->

                <div class="col-md-6 col-xl-4">

                    <a
                        href="https://x.com/TXBeardedViking"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="nexora-social-card">

                        <div class="nexora-social-card-icon">
                            <i class="fa-brands fa-x-twitter"></i>
                        </div>

                        <div class="nexora-social-card-content">

                            <span class="nexora-social-platform">
                                X / TWITTER
                            </span>

                            <h3>
                                Live Updates
                            </h3>

                            <p>
                                Fast-moving thoughts, announcements,
                                technical observations, project updates,
                                and the occasional Viking chaos.
                            </p>

                            <span class="nexora-social-arrow">
                                Follow
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </span>

                        </div>

                    </a>

                </div>


                <!-- TikTok -->

                <div class="col-md-6 col-xl-4">

                    <a
                        href="https://www.tiktok.com/@beardedvikingtx"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="nexora-social-card">

                        <div class="nexora-social-card-icon">
                            <i class="fa-brands fa-tiktok"></i>
                        </div>

                        <div class="nexora-social-card-content">

                            <span class="nexora-social-platform">
                                TIKTOK
                            </span>

                            <h3>
                                Behind the Scenes
                            </h3>

                            <p>
                                Short-form content, technology, personality,
                                experiments, and a glimpse behind the
                                Bearded Viking operation.
                            </p>

                            <span class="nexora-social-arrow">
                                Watch
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </span>

                        </div>

                    </a>

                </div>


                <!-- GitHub -->

                <div class="col-md-6 col-xl-4">

                    <a
                        href="https://github.com/BeardedVikingTX"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="nexora-social-card">

                        <div class="nexora-social-card-icon">
                            <i class="fa-brands fa-github"></i>
                        </div>

                        <div class="nexora-social-card-content">

                            <span class="nexora-social-platform">
                                GITHUB
                            </span>

                            <h3>
                                Open Source
                            </h3>

                            <p>
                                Explore projects, experiments, code,
                                repositories, and the engineering behind
                                the digital ecosystem.
                            </p>

                            <span class="nexora-social-arrow">
                                Explore Code
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </span>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         SECURITY / COMMUNICATIONS
         ========================================================= -->

    <section class="nexora-contact-security">

        <div class="container">

            <div class="nexora-security-panel">

                <div class="row align-items-center g-4">

                    <div class="col-lg-2 text-center">

                        <div class="nexora-security-emblem">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>

                    </div>

                    <div class="col-lg-7">

                        <div class="nexora-section-kicker">
                            SECURE COMMUNICATIONS
                        </div>

                        <h2>
                            Security Is Part of the Conversation.
                        </h2>

                        <p>
                            The Bearded Viking's wider work is rooted in
                            security research, responsible disclosure,
                            technical education, and the principle that
                            security should be built into systems rather
                            than bolted onto them afterward.
                        </p>

                        <p>
                            For sensitive security matters, begin with the
                            contact channel and clearly identify the nature
                            of the report. Do not transmit credentials,
                            private keys, session tokens, or other secrets
                            through a standard web form.
                        </p>

                    </div>

                    <div class="col-lg-3">

                        <div class="nexora-pgp-card">

                            <span>
                                SECURE IDENTITY
                            </span>

                            <strong>
                                PGP
                            </strong>

                            <code>
                                0xBEARD3D
                            </code>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         FINAL CTA
         ========================================================= -->

    <section class="nexora-contact-final">

        <div class="container">

            <div class="text-center">

                <div class="nexora-final-symbol">
                    <i class="fa-solid fa-terminal"></i>
                </div>

                <h2>
                    The Channel Is Open.
                </h2>

                <p>
                    No corporate maze. No twenty-page contact workflow.
                    Just a direct line to the operation.
                </p>

                <a
                    href="mailto:info@beardedviking.org"
                    class="btn nexora-primary-button">

                    <i class="fa-solid fa-envelope"></i>
                    Email the Bearded Viking

                </a>

            </div>

        </div>

    </section>

</section>


<script src="/assets/js/contact.js" defer></script>


<?php
require_once __DIR__ . '/includes/footer.php';
?>