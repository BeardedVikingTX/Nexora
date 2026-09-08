<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once __DIR__ . '/includes/cookies.php';
require_once __DIR__ . '/includes/database.php';

$pageTitle = 'Create Your Nexora Identity';
$pageDescription = 'Create a privacy-focused Nexora account using anonymous or professional registration.';
$pageKeywords = 'Nexora registration, create account, social network, privacy, anonymous account';
$pageRobots = 'index,follow';

require_once __DIR__ . '/includes/header.php';
?>

<section class="register-page">

    <div class="container py-5">

        <!-- HERO -->
        <div class="register-hero text-center mb-5">

            <div class="register-terminal-label">
                <span class="register-terminal-dot"></span>
                NEXORA IDENTITY FORGE
            </div>

            <h1 class="display-4 fw-bold mt-3">
                Create Your <span class="text-gradient">Nexora Identity</span>
            </h1>

            <p class="lead mx-auto register-lead">
                Choose how much information you want to share.
                Nexora supports both privacy-first anonymous accounts
                and professional identities.
            </p>

            <div class="register-privacy-banner">
                <i class="fa-solid fa-shield-halved"></i>
                <span>
                    <strong>Privacy by design.</strong>
                    Your password is never stored in plaintext,
                    and personal information is optional unless you choose
                    professional registration.
                </span>
            </div>

        </div>


        <!-- REGISTRATION TYPE -->
        <div class="row justify-content-center">

            <div class="col-xl-9 col-lg-10">

                <div class="register-card">

                    <div class="register-card-header">

                        <div>
                            <div class="register-eyebrow">
                                IDENTITY CONFIGURATION
                            </div>

                            <h2 class="h3 mb-1">
                                Choose your registration mode
                            </h2>

                            <p class="text-secondary mb-0">
                                You can create a minimal identity or build
                                a professional profile.
                            </p>
                        </div>

                        <div class="register-status">
                            <span class="register-status-dot"></span>
                            REGISTRATION ONLINE
                        </div>

                    </div>


                    <div class="register-mode-grid">

                        <button
                            type="button"
                            class="register-mode active"
                            data-registration-mode="anonymous"
                            aria-pressed="true"
                        >

                            <div class="register-mode-icon">
                                <i class="fa-solid fa-user-secret"></i>
                            </div>

                            <div class="register-mode-content">

                                <h3>Anonymous</h3>

                                <p>
                                    Minimal identity. No real name or
                                    email address required.
                                </p>

                                <div class="register-mode-tags">
                                    <span>Alias</span>
                                    <span>Password</span>
                                    <span>Avatar optional</span>
                                </div>

                            </div>

                            <div class="register-mode-check">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                        </button>


                        <button
                            type="button"
                            class="register-mode"
                            data-registration-mode="professional"
                            aria-pressed="false"
                        >

                            <div class="register-mode-icon">
                                <i class="fa-solid fa-id-card"></i>
                            </div>

                            <div class="register-mode-content">

                                <h3>Professional</h3>

                                <p>
                                    Build a recognizable identity with
                                    optional contact information.
                                </p>

                                <div class="register-mode-tags">
                                    <span>Name</span>
                                    <span>Email</span>
                                    <span>Alias</span>
                                </div>

                            </div>

                            <div class="register-mode-check">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                        </button>

                    </div>


                    <!-- FORM -->
                    <form
                        id="nexora-register-form"
                        class="register-form"
                        method="post"
                        action="/api/register.php"
                        enctype="multipart/form-data"
                        novalidate
                    >

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= e(nexora_csrf_token()) ?>"
                        >

                        <input
                            type="hidden"
                            name="registration_type"
                            id="registration_type"
                            value="anonymous"
                        >

                        <!-- Honeypot -->
                        <div
                            class="register-honeypot"
                            aria-hidden="true"
                        >
                            <label for="website">
                                Website
                            </label>

                            <input
                                type="text"
                                id="website"
                                name="website"
                                tabindex="-1"
                                autocomplete="off"
                            >
                        </div>


                        <!-- IDENTITY -->
                        <div class="register-section">

                            <div class="register-section-heading">
                                <span>01</span>
                                <div>
                                    <h3>Identity</h3>
                                    <p>
                                        Choose the name people will know you by.
                                    </p>
                                </div>
                            </div>


                            <div class="row g-4">

                                <div
                                    class="col-12 col-md-6 professional-field"
                                    hidden
                                >

                                    <label
                                        for="first_name"
                                        class="form-label"
                                    >
                                        First Name
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="first_name"
                                        name="first_name"
                                        maxlength="80"
                                        autocomplete="given-name"
                                    >

                                </div>


                                <div
                                    class="col-12 col-md-6 professional-field"
                                    hidden
                                >

                                    <label
                                        for="last_name"
                                        class="form-label"
                                    >
                                        Last Name
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="last_name"
                                        name="last_name"
                                        maxlength="80"
                                        autocomplete="family-name"
                                    >

                                </div>


                                <div class="col-12">

                                    <label
                                        for="alias"
                                        class="form-label"
                                    >
                                        Alias / Username
                                        <span class="required">*</span>
                                    </label>

                                    <div class="register-input-status">

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="alias"
                                            name="alias"
                                            required
                                            maxlength="80"
                                            minlength="3"
                                            autocomplete="username"
                                            placeholder="Choose your Nexora identity"
                                        >

                                        <span
                                            id="alias-status"
                                            class="register-field-status"
                                            aria-live="polite"
                                        ></span>

                                    </div>

                                    <div class="form-text">
                                        3–80 characters for your public display
                                        name. The system creates a safe internal
                                        username automatically.
                                    </div>

                                </div>


                                <div
                                    class="col-12 professional-field"
                                    hidden
                                >

                                    <label
                                        for="email"
                                        class="form-label"
                                    >
                                        Email Address
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        maxlength="254"
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                    >

                                    <div class="form-text">
                                        Optional from a privacy perspective,
                                        but recommended for professional
                                        accounts so Nexora can contact you.
                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- PASSWORD -->
                        <div class="register-section">

                            <div class="register-section-heading">
                                <span>02</span>
                                <div>
                                    <h3>Security</h3>
                                    <p>
                                        Protect your identity with a strong
                                        password.
                                    </p>
                                </div>
                            </div>


                            <div class="row g-4">

                                <div class="col-12 col-md-6">

                                    <label
                                        for="password"
                                        class="form-label"
                                    >
                                        Password
                                        <span class="required">*</span>
                                    </label>

                                    <div class="password-wrapper">

                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            required
                                            minlength="12"
                                            maxlength="128"
                                            autocomplete="new-password"
                                            placeholder="Minimum 12 characters"
                                        >

                                        <button
                                            type="button"
                                            class="password-toggle"
                                            data-password-target="password"
                                            aria-label="Show password"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                    </div>

                                    <div
                                        id="password-strength"
                                        class="password-strength"
                                    >

                                        <div class="password-strength-bar">
                                            <span></span>
                                        </div>

                                        <div
                                            class="password-strength-text"
                                            aria-live="polite"
                                        >
                                            Enter a password
                                        </div>

                                    </div>

                                </div>


                                <div class="col-12 col-md-6">

                                    <label
                                        for="password_confirm"
                                        class="form-label"
                                    >
                                        Confirm Password
                                        <span class="required">*</span>
                                    </label>

                                    <div class="password-wrapper">

                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password_confirm"
                                            name="password_confirm"
                                            required
                                            minlength="12"
                                            maxlength="128"
                                            autocomplete="new-password"
                                            placeholder="Enter it again"
                                        >

                                        <button
                                            type="button"
                                            class="password-toggle"
                                            data-password-target="password_confirm"
                                            aria-label="Show password"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                    </div>

                                    <div
                                        id="password-match"
                                        class="register-password-match"
                                        aria-live="polite"
                                    ></div>

                                </div>

                            </div>


                            <div class="register-security-note">

                                <i class="fa-solid fa-lock"></i>

                                <div>
                                    <strong>Your password never leaves
                                    the security boundary.</strong>

                                    <p>
                                        Nexora stores a cryptographic password
                                        hash rather than your original password.
                                        Even database administrators cannot
                                        retrieve the original password.
                                    </p>
                                </div>

                            </div>

                        </div>


                        <!-- AVATAR -->
                        <div class="register-section">

                            <div class="register-section-heading">
                                <span>03</span>
                                <div>
                                    <h3>Avatar</h3>
                                    <p>
                                        Personalize your identity — completely
                                        optional.
                                    </p>
                                </div>
                            </div>


                            <div class="register-avatar-area">

                                <div
                                    id="avatar-preview"
                                    class="register-avatar-preview"
                                >
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                <div class="register-avatar-controls">

                                    <label
                                        for="avatar"
                                        class="form-label"
                                    >
                                        Profile Avatar
                                    </label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        id="avatar"
                                        name="avatar"
                                        accept="image/jpeg,image/png,image/webp,image/gif"
                                    >

                                    <div class="form-text">
                                        Optional. Maximum 5 MB.
                                        JPG, PNG, WebP or GIF.
                                    </div>

                                    <button
                                        type="button"
                                        id="remove-avatar"
                                        class="btn btn-sm btn-outline-secondary mt-2"
                                        hidden
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                        Remove Avatar
                                    </button>

                                </div>

                            </div>

                        </div>


                        <!-- PRIVACY -->
                        <div class="register-section">

                            <div class="register-section-heading">
                                <span>04</span>
                                <div>
                                    <h3>Privacy & Agreement</h3>
                                    <p>
                                        A few final acknowledgements before
                                        your identity enters Nexora.
                                    </p>
                                </div>
                            </div>


                            <div class="register-consent">

                                <label class="register-checkbox">

                                    <input
                                        type="checkbox"
                                        name="accept_terms"
                                        value="1"
                                        required
                                    >

                                    <span class="register-checkbox-box">
                                        <i class="fa-solid fa-check"></i>
                                    </span>

                                    <span>
                                        I understand that I am responsible
                                        for the information and content I
                                        submit to Nexora.
                                    </span>

                                </label>


                                <label class="register-checkbox">

                                    <input
                                        type="checkbox"
                                        name="accept_privacy"
                                        value="1"
                                        required
                                    >

                                    <span class="register-checkbox-box">
                                        <i class="fa-solid fa-check"></i>
                                    </span>

                                    <span>
                                        I acknowledge Nexora's privacy-first
                                        approach and understand that optional
                                        information is only collected when I
                                        choose to provide it.
                                    </span>

                                </label>

                            </div>

                        </div>


                        <!-- RESPONSE -->
                        <div
                            id="register-response"
                            class="register-response"
                            role="alert"
                            aria-live="polite"
                            hidden
                        ></div>


                        <!-- SUBMIT -->
                        <div class="register-submit-area">

                            <button
                                type="submit"
                                id="register-submit"
                                class="btn register-submit"
                            >
                                <span class="register-submit-normal">
                                    <i class="fa-solid fa-user-plus"></i>
                                    Forge My Nexora Identity
                                </span>

                                <span
                                    class="register-submit-loading"
                                    hidden
                                >
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        aria-hidden="true"
                                    ></span>
                                    Creating Secure Identity…
                                </span>
                            </button>

                            <p class="register-submit-note">
                                <i class="fa-solid fa-shield-halved"></i>
                                Secure registration • CSRF protected •
                                Password hashed server-side
                            </p>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        <!-- BOTTOM INFORMATION -->
        <div class="row justify-content-center mt-5">

            <div class="col-xl-9 col-lg-10">

                <div class="register-info-grid">

                    <div class="register-info-card">

                        <i class="fa-solid fa-user-secret"></i>

                        <h3>Privacy First</h3>

                        <p>
                            Anonymous registration requires only an alias
                            and password. Your real-world identity is not
                            required.
                        </p>

                    </div>


                    <div class="register-info-card">

                        <i class="fa-solid fa-fingerprint"></i>

                        <h3>One Identity</h3>

                        <p>
                            Your Nexora identity receives a unique internal
                            profile directory for avatars, banners and
                            future profile assets.
                        </p>

                    </div>


                    <div class="register-info-card">

                        <i class="fa-solid fa-server"></i>

                        <h3>Your Data</h3>

                        <p>
                            Nexora separates public identity information from
                            internal security data and never stores plaintext
                            passwords.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<link
    rel="stylesheet"
    href="/assets/css/register.css"
>

<script
    src="/assets/js/register.js"
    defer
></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>