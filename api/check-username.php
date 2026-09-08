<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once dirname(__DIR__) . '/includes/cookies.php';
require_once dirname(__DIR__) . '/includes/database.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

if (
    $_SERVER['REQUEST_METHOD'] !== 'GET'
) {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'available' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}


$alias =
    $_GET['alias'] ?? '';

if (
    !is_string($alias)
) {

    echo json_encode([
        'success' => false,
        'available' => false
    ]);

    exit;
}


$alias =
    trim(
        preg_replace(
            '/\s+/u',
            ' ',
            $alias
        ) ?? ''
    );


$username =
    function_exists('mb_strtolower')
        ? mb_strtolower(
            $alias,
            'UTF-8'
        )
        : strtolower($alias);


$username =
    preg_replace(
        '/\s+/u',
        '_',
        $username
    ) ?? '';


$username =
    preg_replace(
        '/[^a-z0-9_-]/i',
        '',
        $username
    ) ?? '';


$username =
    strtolower(
        trim(
            $username,
            '_-'
        )
    );


if (
    strlen($username) < 3 ||
    strlen($username) > 32
) {

    echo json_encode([
        'success' => true,
        'available' => false
    ]);

    exit;
}


$reserved = [
    'admin',
    'administrator',
    'root',
    'system',
    'support',
    'security',
    'moderator',
    'moderators',
    'staff',
    'nexora',
    'official',
    'api',
    'www',
    'mail',
    'webmaster',
    'help',
    'owner',
    'engineer',
    'beardedviking',
];


if (
    in_array(
        $username,
        $reserved,
        true
    )
) {

    echo json_encode([
        'success' => true,
        'available' => false
    ]);

    exit;
}


try {

    $pdo =
        nexora_db();

    $statement =
        $pdo->prepare(
            'SELECT id
             FROM users
             WHERE username = :username
             LIMIT 1'
        );

    $statement->execute([
        'username' => $username
    ]);

    $available =
        !$statement->fetch();

    echo json_encode([
        'success' => true,
        'available' => $available
    ]);

} catch (Throwable $exception) {

    error_log(
        '[NEXORA USERNAME CHECK] '
        . $exception->getMessage()
    );

    /*
     * Fail closed.
     */
    http_response_code(503);

    echo json_encode([
        'success' => false,
        'available' => false
    ]);
}