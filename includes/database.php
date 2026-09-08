<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Nexora Database Connector
|--------------------------------------------------------------------------
|
| Centralized PDO connection layer.
|
| All database access should ultimately pass through this file.
|
|--------------------------------------------------------------------------
*/

if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}

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
                'Nexora production configuration is missing.'
            );
        }

        $loaded = require $configFile;

        if (!is_array($loaded) || !isset($loaded['database'])) {
            throw new RuntimeException(
                'Nexora database configuration is invalid.'
            );
        }

        $config = $loaded;

        return $config;
    }
}


/*
|--------------------------------------------------------------------------
| PDO Connection
|--------------------------------------------------------------------------
*/

function nexora_db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = nexora_db_config()['database'];

    $host = (string) ($config['host'] ?? 'localhost');
    $port = (int) ($config['port'] ?? 3306);
    $name = (string) ($config['name'] ?? '');
    $user = (string) ($config['username'] ?? '');
    $pass = (string) ($config['password'] ?? '');
    $charset = (string) ($config['charset'] ?? 'utf8mb4');

    if (
        $name === ''
        || $user === ''
    ) {
        throw new RuntimeException(
            'Nexora database credentials are incomplete.'
        );
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $host,
        $port,
        $name,
        $charset
    );

    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
            PDO::ATTR_TIMEOUT            => 10,
        ]
    );

    return $pdo;
}


/*
|--------------------------------------------------------------------------
| Transaction Helpers
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
| Security Hash Helpers
|--------------------------------------------------------------------------
*/

function nexora_app_secret(): string
{
    $config = nexora_db_config();

    $secret = (string) (
        $config['security']['app_secret'] ?? ''
    );

    if (
        $secret === ''
        || strlen($secret) < 32
    ) {
        throw new RuntimeException(
            'Nexora application secret is missing or too short.'
        );
    }

    return $secret;
}


function nexora_hash_identifier(?string $value): ?string
{
    if ($value === null || $value === '') {
        return null;
    }

    return hash_hmac(
        'sha256',
        $value,
        nexora_app_secret()
    );
}


function nexora_client_ip(): ?string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;

    if (
        !is_string($ip)
        || filter_var($ip, FILTER_VALIDATE_IP) === false
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