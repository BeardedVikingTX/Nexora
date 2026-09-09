<?php
/**
 * Nexora Social Platform
 * ------------------------------------------------------------
 * File: includes/database.php
 * Purpose: Centralized secure PDO database layer
 * ------------------------------------------------------------
 */

declare(strict_types=1);

if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

if (!function_exists('nexora_db_config')) {

    function nexora_db_config(): array
    {
        static $config = null;

        if ($config !== null) {
            return $config;
        }

        $configFile = __DIR__ . '/config.php';

        if (!is_file($configFile)) {
            throw new RuntimeException(
                'Nexora configuration is unavailable.'
            );
        }

        $loaded = require $configFile;

        if (!is_array($loaded)) {
            throw new RuntimeException(
                'Nexora configuration is invalid.'
            );
        }

        $config = $loaded;

        return $config;
    }
}


/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/

function nexora_db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $database = nexora_db_config()['database'] ?? [];

    $host = (string) ($database['host'] ?? 'localhost');
    $port = (int) ($database['port'] ?? 3306);
    $name = (string) ($database['name'] ?? '');
    $user = (string) ($database['username'] ?? '');
    $pass = (string) ($database['password'] ?? '');
    $charset = (string) ($database['charset'] ?? 'utf8mb4');

    if ($name === '' || $user === '') {
        throw new RuntimeException(
            'Nexora database configuration is incomplete.'
        );
    }

    if (
        !preg_match('/^[a-zA-Z0-9_]+$/', $name)
        ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $charset)
    ) {
        throw new RuntimeException(
            'Nexora database configuration contains invalid identifiers.'
        );
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $host,
        $port,
        $name,
        $charset
    );

    try {

        $pdo = new PDO(
            $dsn,
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,

                /*
                 * Fail quickly rather than allowing a broken
                 * database connection to hang a request.
                 */
                PDO::ATTR_TIMEOUT => 8,
            ]
        );

        /*
         * Explicitly establish UTF-8 behavior.
         */
        $pdo->exec(
            "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        );

        return $pdo;

    } catch (PDOException $exception) {

        /*
         * Never expose database credentials, DSNs, SQL errors,
         * usernames, or server information to visitors.
         */
        error_log(
            '[NEXORA DB] Connection failure: '
            . $exception->getMessage()
        );

        throw new RuntimeException(
            'Nexora database service is temporarily unavailable.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Transactions
|--------------------------------------------------------------------------
*/

function nexora_db_transaction(callable $callback): mixed
{
    $pdo = nexora_db();

    if ($pdo->inTransaction()) {
        return $callback($pdo);
    }

    $pdo->beginTransaction();

    try {

        $result = $callback($pdo);

        $pdo->commit();

        return $result;

    } catch (Throwable $exception) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $exception;
    }
}


/*
|--------------------------------------------------------------------------
| Application Cryptographic Keys
|--------------------------------------------------------------------------
*/

function nexora_app_key(): string
{
    $key = (string) (
        nexora_db_config()['security']['app_key'] ?? ''
    );

    if (
        $key === ''
        ||
        strlen($key) < 64
        ||
        !ctype_xdigit($key)
    ) {
        throw new RuntimeException(
            'Nexora application key is unavailable.'
        );
    }

    return $key;
}


function nexora_hash_key(): string
{
    $key = (string) (
        nexora_db_config()['security']['hash_key'] ?? ''
    );

    if (
        $key === ''
        ||
        strlen($key) < 64
        ||
        !ctype_xdigit($key)
    ) {
        throw new RuntimeException(
            'Nexora hashing key is unavailable.'
        );
    }

    return $key;
}


/*
|--------------------------------------------------------------------------
| Privacy-Preserving Identifier Hashing
|--------------------------------------------------------------------------
*/

function nexora_hash_identifier(?string $value): ?string
{
    if ($value === null || $value === '') {
        return null;
    }

    return hash_hmac(
        'sha256',
        $value,
        nexora_hash_key()
    );
}


/*
|--------------------------------------------------------------------------
| Client Metadata
|--------------------------------------------------------------------------
*/

function nexora_client_ip(): ?string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;

    if (
        !is_string($ip)
        ||
        filter_var($ip, FILTER_VALIDATE_IP) === false
    ) {
        return null;
    }

    return $ip;
}


function nexora_client_ip_hash(): ?string
{
    return nexora_hash_identifier(
        nexora_client_ip()
    );
}


function nexora_user_agent_hash(): ?string
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

    if (!is_string($userAgent) || $userAgent === '') {
        return null;
    }

    return nexora_hash_identifier(
        $userAgent
    );
}