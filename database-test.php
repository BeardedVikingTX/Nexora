<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once __DIR__ . '/includes/database.php';

header('Content-Type: text/plain; charset=UTF-8');

echo "NEXORA DATABASE DIAGNOSTIC\n";
echo "===========================\n\n";

try {

    $config = nexora_db_config();

    echo "Configuration file: FOUND\n";

    echo "Database host: "
        . ($config['database']['host'] ?? 'MISSING')
        . "\n";

    echo "Database port: "
        . ($config['database']['port'] ?? 'MISSING')
        . "\n";

    echo "Database name: "
        . ($config['database']['name'] ?? 'MISSING')
        . "\n";

    echo "Database username: "
        . ($config['database']['username'] ?? 'MISSING')
        . "\n";

    echo "Database password: [HIDDEN]\n\n";


    echo "Attempting PDO connection...\n";

    $pdo = nexora_db();

    echo "PDO CONNECTION: SUCCESS\n\n";


    /*
    |--------------------------------------------------------------------------
    | Basic Database Test
    |--------------------------------------------------------------------------
    */

    echo "Testing basic query...\n";

    $statement = $pdo->query(
        'SELECT 1'
    );

    $result = $statement->fetchColumn();

    if ((int) $result !== 1) {
        throw new RuntimeException(
            'Basic database query returned an unexpected result.'
        );
    }

    echo "BASIC QUERY: SUCCESS\n\n";


    /*
    |--------------------------------------------------------------------------
    | Database Identity
    |--------------------------------------------------------------------------
    */

    echo "Testing database identity...\n";

    $statement = $pdo->query(
        'SELECT DATABASE()'
    );

    $databaseName = $statement->fetchColumn();

    echo "Connected database: "
        . ($databaseName ?: 'unknown')
        . "\n\n";


    /*
    |--------------------------------------------------------------------------
    | MariaDB Version
    |--------------------------------------------------------------------------
    */

    echo "Testing database version...\n";

    $statement = $pdo->query(
        'SELECT VERSION()'
    );

    $version = $statement->fetchColumn();

    echo "Database version: "
        . ($version ?: 'unknown')
        . "\n\n";


    /*
    |--------------------------------------------------------------------------
    | Nexora Table Count
    |--------------------------------------------------------------------------
    */

    echo "Testing Nexora schema...\n";

    $statement = $pdo->query(
        "SELECT COUNT(*)
         FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE()"
    );

    $tableCount = (int) $statement->fetchColumn();

    echo "Tables found: "
        . $tableCount
        . "\n\n";


    /*
    |--------------------------------------------------------------------------
    | Required Nexora Tables
    |--------------------------------------------------------------------------
    */

    $requiredTables = [
        'users',
        'ai_platforms',
        'voting_rounds',
        'ai_votes',
        'posts',
        'comments',
        'friend_requests',
        'friendships',
        'conversations',
        'conversation_members',
        'messages',
        'badges',
        'user_badges',
        'reputation_events',
        'rate_limit_events',
        'security_events',
        'audit_log',
    ];

    echo "Checking required Nexora tables...\n\n";

    $tableCheck = $pdo->prepare(
        "SELECT COUNT(*)
         FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = :table_name"
    );

    $missingTables = [];

    foreach ($requiredTables as $table) {

        $tableCheck->execute([
            ':table_name' => $table,
        ]);

        $exists = (int) $tableCheck->fetchColumn() > 0;

        echo str_pad(
            $table,
            30,
            '.'
        );

        echo $exists
            ? " ONLINE\n"
            : " MISSING\n";

        if (!$exists) {
            $missingTables[] = $table;
        }
    }


    echo "\n";


    /*
    |--------------------------------------------------------------------------
    | AI Competition Data
    |--------------------------------------------------------------------------
    */

    echo "Checking AI competition data...\n\n";

    $statement = $pdo->query(
        "SELECT
            id,
            name,
            provider,
            codename
         FROM ai_platforms
         WHERE is_active = 1
         ORDER BY display_order ASC"
    );

    $platforms = $statement->fetchAll();

    foreach ($platforms as $platform) {

        echo "#"
            . $platform['id']
            . " "
            . $platform['name']
            . " — "
            . $platform['provider']
            . " — "
            . $platform['codename']
            . "\n";
    }


    echo "\n";


    /*
    |--------------------------------------------------------------------------
    | Voting Round
    |--------------------------------------------------------------------------
    */

    echo "Checking voting rounds...\n\n";

    $statement = $pdo->query(
        "SELECT
            id,
            round_key,
            title,
            status
         FROM voting_rounds
         ORDER BY id ASC"
    );

    $rounds = $statement->fetchAll();

    foreach ($rounds as $round) {

        echo "#"
            . $round['id']
            . " "
            . $round['round_key']
            . " — "
            . $round['title']
            . " — "
            . $round['status']
            . "\n";
    }


    echo "\n";


    /*
    |--------------------------------------------------------------------------
    | Final Result
    |--------------------------------------------------------------------------
    */

    if (!empty($missingTables)) {

        echo "NEXORA DATABASE CONNECTION: ONLINE\n";
        echo "NEXORA DATABASE SCHEMA: INCOMPLETE\n\n";

        echo "Missing tables:\n";

        foreach ($missingTables as $table) {
            echo " - " . $table . "\n";
        }

    } else {

        echo "NEXORA DATABASE CONNECTION: ONLINE\n";
        echo "NEXORA DATABASE SCHEMA: ONLINE\n";
        echo "NEXORA AI COMPETITION DATA: ONLINE\n";
    }

} catch (Throwable $exception) {

    http_response_code(500);

    echo "\n";
    echo "NEXORA DATABASE DIAGNOSTIC: FAILED\n";
    echo "===================================\n\n";

    echo "Exception type:\n";
    echo get_class($exception);
    echo "\n\n";

    echo "Error message:\n";
    echo $exception->getMessage();
    echo "\n\n";

    echo "File:\n";
    echo $exception->getFile();
    echo "\n\n";

    echo "Line:\n";
    echo $exception->getLine();
    echo "\n\n";

    echo "IMPORTANT:\n";
    echo "This diagnostic intentionally does NOT display the database password.\n";
}