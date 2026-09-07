<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/cookies.php
 * Purpose: Secure session and cookie initialization
 *
 * IMPORTANT:
 * - This file must execute BEFORE any HTML/output.
 * - Session cookies contain only an opaque session identifier.
 * - Sensitive application data belongs server-side.
 * - Do NOT store passwords, tokens, private messages, or
 *   sensitive user information directly inside browser cookies.
 * ------------------------------------------------------------
 */

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Prevent direct access
|--------------------------------------------------------------------------
|
| This file is intended to be included by Nexora application files.
| Direct browser execution should not expose application internals.
|
*/
if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}

/*
|--------------------------------------------------------------------------
| Session Configuration
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {

    /*
    |--------------------------------------------------------------------------
    | Detect HTTPS
    |--------------------------------------------------------------------------
    |
    | Shared hosting environments can sometimes sit behind proxies.
    | We still default to the safest behavior possible.
    |
    */

    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ||
        (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
    );

    /*
    |--------------------------------------------------------------------------
    | Secure PHP Session Cookie
    |--------------------------------------------------------------------------
    */

    session_name('NEXORA_SESSION');

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Harden PHP Session Handling
    |--------------------------------------------------------------------------
    */

    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_cookies', '1');
    ini_set('session.use_strict_mode', '1');

    /*
    |--------------------------------------------------------------------------
    | Prevent Session IDs From Being Passed Through URLs
    |--------------------------------------------------------------------------
    */

    ini_set('session.use_trans_sid', '0');

    /*
    |--------------------------------------------------------------------------
    | Start Session
    |--------------------------------------------------------------------------
    */

    session_start();
}

/*
|--------------------------------------------------------------------------
| Session Initialization
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['nexora_initialized'])) {
    $_SESSION['nexora_initialized'] = time();
}

/*
|--------------------------------------------------------------------------
| CSRF Protection Token
|--------------------------------------------------------------------------
|
| A cryptographically secure token is generated once per session.
|
*/

if (
    !isset($_SESSION['csrf_token'])
    ||
    !is_string($_SESSION['csrf_token'])
    ||
    strlen($_SESSION['csrf_token']) < 64
) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
|--------------------------------------------------------------------------
| Session Fingerprint
|--------------------------------------------------------------------------
|
| This is NOT intended to uniquely identify a person.
| It provides a lightweight consistency check against some forms
| of session theft.
|
| Do not use highly identifying information here.
|
*/

if (!isset($_SESSION['session_fingerprint'])) {

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $_SESSION['session_fingerprint'] = hash(
        'sha256',
        $userAgent
    );
}

/*
|--------------------------------------------------------------------------
| Basic Session Integrity Check
|--------------------------------------------------------------------------
*/

$currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

$currentFingerprint = hash(
    'sha256',
    $currentUserAgent
);

if (
    isset($_SESSION['session_fingerprint'])
    &&
    !hash_equals(
        (string) $_SESSION['session_fingerprint'],
        $currentFingerprint
    )
) {
    /*
    |--------------------------------------------------------------------------
    | Possible Session Hijacking
    |--------------------------------------------------------------------------
    |
    | Destroy the session rather than trusting it.
    |
    */

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            [
                'expires'  => time() - 42000,
                'path'     => $params['path'] ?? '/',
                'domain'   => $params['domain'] ?? '',
                'secure'   => (bool) ($params['secure'] ?? false),
                'httponly' => (bool) ($params['httponly'] ?? true),
                'samesite' => $params['samesite'] ?? 'Lax',
            ]
        );
    }

    session_destroy();

    /*
    |--------------------------------------------------------------------------
    | Start a Fresh Session
    |--------------------------------------------------------------------------
    */

    session_start();

    $_SESSION['nexora_initialized'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['session_fingerprint'] = $currentFingerprint;
}

/*
|--------------------------------------------------------------------------
| Session Age Tracking
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
}

/*
|--------------------------------------------------------------------------
| Session Activity Tracking
|--------------------------------------------------------------------------
*/

$_SESSION['last_activity'] = time();

/*
|--------------------------------------------------------------------------
| Helper: Retrieve CSRF Token
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_csrf_token')) {

    function nexora_csrf_token(): string
    {
        return (string) ($_SESSION['csrf_token'] ?? '');
    }
}

/*
|--------------------------------------------------------------------------
| Helper: Verify CSRF Token
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_verify_csrf')) {

    function nexora_verify_csrf(?string $token): bool
    {
        if (
            $token === null ||
            $token === '' ||
            !isset($_SESSION['csrf_token'])
        ) {
            return false;
        }

        return hash_equals(
            (string) $_SESSION['csrf_token'],
            $token
        );
    }
}

/*
|--------------------------------------------------------------------------
| Helper: Regenerate Session ID
|--------------------------------------------------------------------------
|
| This should be called after authentication state changes such as:
|
| - Successful login
| - Privilege escalation
| - Password change
| - Account recovery
|
*/

if (!function_exists('nexora_regenerate_session')) {

    function nexora_regenerate_session(bool $deleteOldSession = true): bool
    {
        return session_regenerate_id($deleteOldSession);
    }
}

/*
|--------------------------------------------------------------------------
| Prevent Session Cache Issues
|--------------------------------------------------------------------------
*/

if (function_exists('header_remove')) {
    header_remove('X-Powered-By');
}