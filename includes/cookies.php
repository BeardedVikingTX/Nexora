<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/cookies.php
 * Purpose: Secure session, CSRF, and cookie lifecycle
 * ------------------------------------------------------------
 */

declare(strict_types=1);

if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}


/*
|--------------------------------------------------------------------------
| HTTPS Detection
|--------------------------------------------------------------------------
*/

function nexora_is_https(): bool
{
    return (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ||
        (isset($_SERVER['SERVER_PORT'])
            && (int) $_SERVER['SERVER_PORT'] === 443)
    );
}


/*
|--------------------------------------------------------------------------
| Session Configuration
|--------------------------------------------------------------------------
*/

if (session_status() !== PHP_SESSION_ACTIVE) {

    $isHttps = nexora_is_https();

    session_name('NEXORA_SESSION');

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_trans_sid', '0');

    /*
     * Prevent PHP from unnecessarily exposing the session
     * identifier through URL-related mechanisms.
     */
    ini_set('session.use_strict_mode', '1');

    /*
     * Session lifetime controls.
     *
     * These are intentionally conservative starting values.
     * We can later make them configurable per account type.
     */
    ini_set('session.gc_maxlifetime', '7200');

    session_start();
}


/*
|--------------------------------------------------------------------------
| Session Initialization
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['nexora_initialized'])
    ||
    $_SESSION['nexora_initialized'] !== true
) {

    $_SESSION['nexora_initialized'] = true;

    $_SESSION['nexora_created_at'] = time();

    $_SESSION['nexora_last_activity'] = time();
}


/*
|--------------------------------------------------------------------------
| Session Timeout
|--------------------------------------------------------------------------
|
| Idle timeout:
| 2 hours.
|
| Absolute timeout:
| 24 hours.
|
| Authentication workflows can establish a new session
| after successful login.
|--------------------------------------------------------------------------
*/

$now = time();

$lastActivity = (int) (
    $_SESSION['nexora_last_activity'] ?? $now
);

$createdAt = (int) (
    $_SESSION['nexora_created_at'] ?? $now
);

$idleTimeout = 7200;
$absoluteTimeout = 86400;

if (
    ($now - $lastActivity) > $idleTimeout
    ||
    ($now - $createdAt) > $absoluteTimeout
) {

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
     * Start a completely new anonymous session.
     */
    session_start();

    $_SESSION['nexora_initialized'] = true;
    $_SESSION['nexora_created_at'] = time();
    $_SESSION['nexora_last_activity'] = time();
}


/*
|--------------------------------------------------------------------------
| Activity Timestamp
|--------------------------------------------------------------------------
*/

$_SESSION['nexora_last_activity'] = time();


/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['nexora_csrf_token'])
    ||
    !is_string($_SESSION['nexora_csrf_token'])
    ||
    strlen($_SESSION['nexora_csrf_token']) !== 64
) {
    $_SESSION['nexora_csrf_token'] = bin2hex(
        random_bytes(32)
    );
}


if (!function_exists('nexora_csrf_token')) {

    function nexora_csrf_token(): string
    {
        return (string) (
            $_SESSION['nexora_csrf_token'] ?? ''
        );
    }
}


if (!function_exists('nexora_verify_csrf')) {

    function nexora_verify_csrf(?string $token): bool
    {
        if (
            !is_string($token)
            ||
            $token === ''
        ) {
            return false;
        }

        $sessionToken = $_SESSION['nexora_csrf_token'] ?? '';

        if (
            !is_string($sessionToken)
            ||
            $sessionToken === ''
        ) {
            return false;
        }

        return hash_equals(
            $sessionToken,
            $token
        );
    }
}


/*
|--------------------------------------------------------------------------
| Session Regeneration
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_regenerate_session')) {

    function nexora_regenerate_session(
        bool $deleteOldSession = true
    ): bool {

        $result = session_regenerate_id(
            $deleteOldSession
        );

        if ($result) {

            $_SESSION['nexora_last_activity'] = time();

            /*
             * Rotate CSRF token whenever the authentication/session
             * security boundary changes.
             */
            $_SESSION['nexora_csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }

        return $result;
    }
}


/*
|--------------------------------------------------------------------------
| Authentication Session Reset
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_start_authenticated_session')) {

    function nexora_start_authenticated_session(
        int $userId
    ): void {

        /*
         * Prevent session fixation.
         */
        session_regenerate_id(true);

        $_SESSION = [];

        $_SESSION['nexora_initialized'] = true;
        $_SESSION['nexora_created_at'] = time();
        $_SESSION['nexora_last_activity'] = time();

        $_SESSION['nexora_user_id'] = $userId;

        $_SESSION['nexora_csrf_token'] = bin2hex(
            random_bytes(32)
        );
    }
}


/*
|--------------------------------------------------------------------------
| Authentication Session Destruction
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_destroy_session')) {

    function nexora_destroy_session(): void
    {
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
    }
}


/*
|--------------------------------------------------------------------------
| Remove PHP Technology Disclosure
|--------------------------------------------------------------------------
*/

if (function_exists('header_remove')) {
    header_remove('X-Powered-By');
}