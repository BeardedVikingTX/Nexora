<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Nexora Configuration Example
|--------------------------------------------------------------------------
|
| COPY THIS FILE TO:
|
|     includes/config.php
|
| Then replace the placeholder values with your REAL production values.
|
| IMPORTANT:
| config.php is intentionally excluded from GitHub.
|
|--------------------------------------------------------------------------
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Application
    |--------------------------------------------------------------------------
    */

    'app' => [
        'name'        => 'Nexora',
        'environment' => 'production',
        'base_url'    => 'https://nexora.beardedviking.org',
        'timezone'    => 'America/Chicago',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */

    'database' => [
        'host'     => 'localhost',
        'port'     => 3306,
        'name'     => 'CHANGE_ME',
        'username' => 'CHANGE_ME',
        'password' => 'CHANGE_ME',
        'charset'  => 'utf8mb4',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | Generate a long random value for this.
    |
    | Example:
    | php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
    |
    */

    'security' => [
        'app_secret' => 'CHANGE_ME_TO_A_LONG_RANDOM_SECRET',
    ],

    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    |
    | This first implementation uses PHP's configured mail transport.
    | SMTP can be added later without changing the voting architecture.
    |
    */

    'mail' => [
        'from_email' => 'noreply@nexora.beardedviking.org',
        'from_name'  => 'Nexora',
        'engineer_email' => 'info@beardedviking.org',
    ],
];