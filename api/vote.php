<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Nexora AI Race Voting API
|--------------------------------------------------------------------------
|
| POST /api/vote.php
|
| Required:
|     ai_platform_id
|     voting_round_id
|     csrf_token
|
| Authentication:
|     Required
|
|--------------------------------------------------------------------------
*/

define('NEXORA_BOOTSTRAPPED', true);

require_once __DIR__ . '/../includes/cookies.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/mailer.php';


header('Content-Type: application/json; charset=UTF-8');


function nexora_vote_response(
    bool $success,
    string $message,
    int $statusCode = 200,
    array $extra = []
): never {

    http_response_code($statusCode);

    echo json_encode(
        array_merge(
            [
                'success' => $success,
                'message' => $message,
            ],
            $extra
        ),
        JSON_UNESCAPED_SLASHES
        | JSON_UNESCAPED_UNICODE
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Request Method
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    nexora_vote_response(
        false,
        'POST requests are required.',
        405
    );
}


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
|
| The login system will populate:
|
|     $_SESSION['user_id']
|
|--------------------------------------------------------------------------
*/

$userId = filter_var(
    $_SESSION['user_id'] ?? null,
    FILTER_VALIDATE_INT
);

if (
    $userId === false
    || $userId === null
    || $userId < 1
) {

    nexora_vote_response(
        false,
        'Authentication is required to vote.',
        401
    );
}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$csrfToken = $_POST['csrf_token'] ?? null;

if (
    !function_exists('nexora_verify_csrf')
    || !nexora_verify_csrf(
        is_string($csrfToken)
            ? $csrfToken
            : null
    )
) {

    nexora_vote_response(
        false,
        'Security validation failed.',
        403
    );
}


/*
|--------------------------------------------------------------------------
| Input Validation
|--------------------------------------------------------------------------
*/

$aiPlatformId = filter_var(
    $_POST['ai_platform_id'] ?? null,
    FILTER_VALIDATE_INT
);

$votingRoundId = filter_var(
    $_POST['voting_round_id'] ?? null,
    FILTER_VALIDATE_INT
);

if (
    $aiPlatformId === false
    || $votingRoundId === false
    || $aiPlatformId < 1
    || $votingRoundId < 1
) {

    nexora_vote_response(
        false,
        'Invalid voting selection.',
        422
    );
}


/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

try {

    $result = nexora_db_transaction(
        function (PDO $pdo) use (
            $userId,
            $aiPlatformId,
            $votingRoundId
        ): array {

            /*
            |--------------------------------------------------------------------------
            | Validate User
            |--------------------------------------------------------------------------
            */

            $userStmt = $pdo->prepare(
                'SELECT
                    id,
                    username,
                    email,
                    account_status
                 FROM users
                 WHERE id = :id
                 LIMIT 1'
            );

            $userStmt->execute([
                ':id' => $userId,
            ]);

            $user = $userStmt->fetch();

            if (!$user) {
                throw new RuntimeException(
                    'User account was not found.'
                );
            }

            if ($user['account_status'] !== 'active') {
                throw new RuntimeException(
                    'Your account is not eligible to vote.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Voting Round
            |--------------------------------------------------------------------------
            */

            $roundStmt = $pdo->prepare(
                'SELECT
                    id,
                    title,
                    status,
                    starts_at,
                    ends_at
                 FROM voting_rounds
                 WHERE id = :id
                 LIMIT 1'
            );

            $roundStmt->execute([
                ':id' => $votingRoundId,
            ]);

            $round = $roundStmt->fetch();

            if (!$round) {
                throw new RuntimeException(
                    'Voting round was not found.'
                );
            }

            if ($round['status'] !== 'active') {
                throw new RuntimeException(
                    'This voting round is not currently active.'
                );
            }

            if (
                $round['starts_at'] !== null
                && strtotime($round['starts_at']) > time()
            ) {
                throw new RuntimeException(
                    'This voting round has not started.'
                );
            }

            if (
                $round['ends_at'] !== null
                && strtotime($round['ends_at']) < time()
            ) {
                throw new RuntimeException(
                    'This voting round has ended.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Validate AI Platform
            |--------------------------------------------------------------------------
            */

            $platformStmt = $pdo->prepare(
                'SELECT
                    id,
                    name,
                    is_active
                 FROM ai_platforms
                 WHERE id = :id
                 LIMIT 1'
            );

            $platformStmt->execute([
                ':id' => $aiPlatformId,
            ]);

            $platform = $platformStmt->fetch();

            if (!$platform) {
                throw new RuntimeException(
                    'AI platform was not found.'
                );
            }

            if ((int) $platform['is_active'] !== 1) {
                throw new RuntimeException(
                    'This AI platform is not currently eligible.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Duplicate Vote Protection
            |--------------------------------------------------------------------------
            */

            $duplicateStmt = $pdo->prepare(
                'SELECT id
                 FROM ai_votes
                 WHERE user_id = :user_id
                   AND voting_round_id = :round_id
                 LIMIT 1'
            );

            $duplicateStmt->execute([
                ':user_id' => $userId,
                ':round_id' => $votingRoundId,
            ]);

            if ($duplicateStmt->fetch()) {
                throw new RuntimeException(
                    'You have already voted in this round.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Vote Insert
            |--------------------------------------------------------------------------
            */

            $insertStmt = $pdo->prepare(
                'INSERT INTO ai_votes
                (
                    user_id,
                    ai_platform_id,
                    voting_round_id,
                    ip_hash,
                    user_agent_hash,
                    confirmation_status
                )
                VALUES
                (
                    :user_id,
                    :ai_platform_id,
                    :voting_round_id,
                    :ip_hash,
                    :user_agent_hash,
                    :confirmation_status
                )'
            );

            $insertStmt->execute([
                ':user_id' => $userId,
                ':ai_platform_id' => $aiPlatformId,
                ':voting_round_id' => $votingRoundId,
                ':ip_hash' => nexora_client_ip_hash(),
                ':user_agent_hash' => nexora_user_agent_hash(),
                ':confirmation_status' => 'pending',
            ]);

            $voteId = (int) $pdo->lastInsertId();


            /*
            |--------------------------------------------------------------------------
            | Reputation Event
            |--------------------------------------------------------------------------
            */

            $reputationStmt = $pdo->prepare(
                'INSERT INTO reputation_events
                (
                    user_id,
                    event_type,
                    points,
                    reference_type,
                    reference_id
                )
                VALUES
                (
                    :user_id,
                    :event_type,
                    :points,
                    :reference_type,
                    :reference_id
                )'
            );

            $reputationStmt->execute([
                ':user_id' => $userId,
                ':event_type' => 'ai_race_vote',
                ':points' => 5,
                ':reference_type' => 'ai_vote',
                ':reference_id' => $voteId,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Automatically Award AI Race Voter Badge
            |--------------------------------------------------------------------------
            */

            $badgeStmt = $pdo->prepare(
                'SELECT id
                 FROM badges
                 WHERE badge_key = :badge_key
                 LIMIT 1'
            );

            $badgeStmt->execute([
                ':badge_key' => 'ai_race_voter',
            ]);

            $badgeId = $badgeStmt->fetchColumn();

            if ($badgeId !== false) {

                $badgeInsert = $pdo->prepare(
                    'INSERT IGNORE INTO user_badges
                    (
                        user_id,
                        badge_id
                    )
                    VALUES
                    (
                        :user_id,
                        :badge_id
                    )'
                );

                $badgeInsert->execute([
                    ':user_id' => $userId,
                    ':badge_id' => (int) $badgeId,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Update User Reputation
            |--------------------------------------------------------------------------
            */

            $reputationUpdate = $pdo->prepare(
                'UPDATE users
                 SET reputation_points =
                     reputation_points + 5
                 WHERE id = :id'
            );

            $reputationUpdate->execute([
                ':id' => $userId,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Audit Entry
            |--------------------------------------------------------------------------
            */

            $auditStmt = $pdo->prepare(
                'INSERT INTO audit_log
                (
                    user_id,
                    action,
                    target_type,
                    target_id,
                    ip_hash,
                    metadata
                )
                VALUES
                (
                    :user_id,
                    :action,
                    :target_type,
                    :target_id,
                    :ip_hash,
                    :metadata
                )'
            );

            $auditStmt->execute([
                ':user_id' => $userId,
                ':action' => 'ai_vote_created',
                ':target_type' => 'ai_vote',
                ':target_id' => $voteId,
                ':ip_hash' => nexora_client_ip_hash(),
                ':metadata' => json_encode([
                    'ai_platform_id' => $aiPlatformId,
                    'voting_round_id' => $votingRoundId,
                ]),
            ]);


            return [
                'vote_id' => $voteId,
                'username' => (string) $user['username'],
                'email' => (string) $user['email'],
                'ai_name' => (string) $platform['name'],
                'round_title' => (string) $round['title'],
            ];
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Email Confirmation
    |--------------------------------------------------------------------------
    */

    $confirmationSent = nexora_send_vote_confirmation(
        $result['email'],
        $result['username'],
        $result['ai_name'],
        $result['round_title']
    );


    /*
    |--------------------------------------------------------------------------
    | Engineer Notification
    |--------------------------------------------------------------------------
    */

    nexora_send_engineer_vote_alert(
        $result['username'],
        $result['ai_name'],
        $result['round_title']
    );


    /*
    |--------------------------------------------------------------------------
    | Update Email Status
    |--------------------------------------------------------------------------
    */

    $pdo = nexora_db();

    $statusStmt = $pdo->prepare(
        'UPDATE ai_votes
         SET confirmation_status = :status
         WHERE id = :id'
    );

    $statusStmt->execute([
        ':status' => $confirmationSent
            ? 'sent'
            : 'failed',
        ':id' => $result['vote_id'],
    ]);


    nexora_vote_response(
        true,
        'Your vote has been recorded successfully.',
        200,
        [
            'vote_id' => $result['vote_id'],
            'confirmation_email_sent' => $confirmationSent,
        ]
    );


} catch (PDOException $exception) {

    /*
    |--------------------------------------------------------------------------
    | Duplicate Constraint Safety
    |--------------------------------------------------------------------------
    */

    if ((int) $exception->errorInfo[1] === 1062) {

        nexora_vote_response(
            false,
            'You have already voted in this round.',
            409
        );
    }


    error_log(
        '[NEXORA][VOTE][PDO] '
        . $exception->getMessage()
    );

    nexora_vote_response(
        false,
        'The vote could not be recorded.',
        500
    );


} catch (Throwable $exception) {

    error_log(
        '[NEXORA][VOTE] '
        . $exception->getMessage()
    );

    nexora_vote_response(
        false,
        $exception->getMessage(),
        400
    );
}