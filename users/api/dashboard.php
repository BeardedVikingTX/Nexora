<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once dirname(__DIR__, 2) . '/includes/cookies.php';
require_once dirname(__DIR__, 2) . '/includes/database.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: private, no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

function dash_json(bool $ok, array $data = [], string $message = ''): never
{
    http_response_code($ok ? 200 : (http_response_code() >= 400 ? http_response_code() : 500));
    echo json_encode(
        ['success' => $ok, 'message' => $message, 'data' => $data],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    dash_json(false, [], 'Method not allowed.');
}

$userId = (int) ($_SESSION['nexora_user_id'] ?? $_SESSION['user_id'] ?? 0);
if ($userId < 1) {
    http_response_code(401);
    dash_json(false, [], 'Authentication required.');
}

function dash_table_exists(PDO $pdo, string $table): bool
{
    static $cache = [];

    if (array_key_exists($table, $cache)) {
        return $cache[$table];
    }

    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );
    $stmt->execute([$table]);

    return $cache[$table] = ((int) $stmt->fetchColumn() > 0);
}

function dash_columns(PDO $pdo, string $table): array
{
    static $cache = [];

    if (isset($cache[$table])) {
        return $cache[$table];
    }

    if (!dash_table_exists($pdo, $table)) {
        return $cache[$table] = [];
    }

    $stmt = $pdo->prepare(
        'SELECT COLUMN_NAME FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );
    $stmt->execute([$table]);

    $columns = [];
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $column) {
        $columns[strtolower((string) $column)] = (string) $column;
    }

    return $cache[$table] = $columns;
}

function dash_pick(array $columns, array $candidates): ?string
{
    foreach ($candidates as $candidate) {
        $key = strtolower($candidate);
        if (isset($columns[$key])) {
            return $columns[$key];
        }
    }

    return null;
}

function dash_ident(string $identifier): string
{
    return '`' . str_replace('`', '``', $identifier) . '`';
}

function dash_count_total(PDO $pdo, string $table, array $userCandidates, int $userId): int
{
    $columns = dash_columns($pdo, $table);
    $userColumn = dash_pick($columns, $userCandidates);

    if (!$userColumn) {
        return 0;
    }

    $sql = sprintf(
        'SELECT COUNT(*) FROM %s WHERE %s = ?',
        dash_ident($table),
        dash_ident($userColumn)
    );

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);

    return (int) $stmt->fetchColumn();
}

function dash_count_period(
    PDO $pdo,
    string $table,
    array $userCandidates,
    array $dateCandidates,
    int $userId,
    int $days
): int {
    $columns = dash_columns($pdo, $table);
    $userColumn = dash_pick($columns, $userCandidates);
    $dateColumn = dash_pick($columns, $dateCandidates);

    if (!$userColumn || !$dateColumn) {
        return 0;
    }

    $sql = sprintf(
        'SELECT COUNT(*) FROM %s
         WHERE %s = ?
           AND %s >= UTC_TIMESTAMP() - INTERVAL ? DAY',
        dash_ident($table),
        dash_ident($userColumn),
        dash_ident($dateColumn)
    );

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $days, PDO::PARAM_INT);
    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

function dash_connections(PDO $pdo, int $userId): int
{
    if (!dash_table_exists($pdo, 'friendships')) {
        return 0;
    }

    $columns = dash_columns($pdo, 'friendships');
    $a = dash_pick($columns, ['user_id', 'user_one_id', 'user_a_id']);
    $b = dash_pick($columns, ['friend_id', 'user_two_id', 'user_b_id']);

    if (!$a || !$b) {
        return 0;
    }

    $status = dash_pick($columns, ['status', 'friendship_status']);
    $where = '(' . dash_ident($a) . ' = ? OR ' . dash_ident($b) . ' = ?)';

    if ($status) {
        $where .= ' AND LOWER(' . dash_ident($status) . ") IN ('accepted','active','friends','confirmed')";
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM friendships WHERE ' . $where);
    $stmt->execute([$userId, $userId]);

    return (int) $stmt->fetchColumn();
}

function dash_requests(PDO $pdo, int $userId, bool $incoming): int
{
    if (!dash_table_exists($pdo, 'friend_requests')) {
        return 0;
    }

    $columns = dash_columns($pdo, 'friend_requests');
    $from = dash_pick($columns, ['requester_id', 'sender_id', 'from_user_id', 'user_id']);
    $to = dash_pick($columns, ['recipient_id', 'receiver_id', 'to_user_id', 'friend_id']);
    $status = dash_pick($columns, ['status', 'request_status']);

    if (!$from || !$to) {
        return 0;
    }

    $column = $incoming ? $to : $from;
    $where = dash_ident($column) . ' = ?';

    if ($status) {
        $where .= ' AND LOWER(' . dash_ident($status) . ") IN ('pending','requested')";
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM friend_requests WHERE ' . $where);
    $stmt->execute([$userId]);

    return (int) $stmt->fetchColumn();
}

function dash_reactions_received(PDO $pdo, int $userId): int
{
    foreach (['post_reactions', 'reactions', 'likes'] as $table) {
        if (!dash_table_exists($pdo, $table) || !dash_table_exists($pdo, 'posts')) {
            continue;
        }

        $reactionColumns = dash_columns($pdo, $table);
        $postColumns = dash_columns($pdo, 'posts');

        $reactionTarget = dash_pick($reactionColumns, ['post_id', 'target_id']);
        $reactionUser = dash_pick($reactionColumns, ['user_id', 'reactor_user_id', 'author_id', 'created_by']);
        $postId = dash_pick($postColumns, ['id', 'post_id']);
        $postUser = dash_pick($postColumns, ['user_id', 'author_id', 'creator_id', 'created_by']);

        if (!$reactionTarget || !$reactionUser || !$postId || !$postUser) {
            continue;
        }

        try {
            $sql = sprintf(
                'SELECT COUNT(*) FROM %s r
                 INNER JOIN %s p ON p.%s = r.%s
                 WHERE p.%s = ?',
                dash_ident($table),
                dash_ident('posts'),
                dash_ident($postId),
                dash_ident($reactionTarget),
                dash_ident($postUser)
            );

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);

            return (int) $stmt->fetchColumn();
        } catch (Throwable) {
            continue;
        }
    }

    return 0;
}

function dash_badges(PDO $pdo, int $userId): array
{
    if (!dash_table_exists($pdo, 'badges') || !dash_table_exists($pdo, 'user_badges')) {
        return ['earned' => [], 'locked' => []];
    }

    $badges = dash_columns($pdo, 'badges');
    $userBadges = dash_columns($pdo, 'user_badges');

    $badgeId = dash_pick($badges, ['id', 'badge_id']);
    $badgeName = dash_pick($badges, ['name', 'badge_name', 'title']);
    $badgeDescription = dash_pick($badges, ['description', 'badge_description']);
    $badgeIcon = dash_pick($badges, ['icon', 'icon_class', 'fa_icon']);
    $userBadgeId = dash_pick($userBadges, ['badge_id']);
    $userBadgeUser = dash_pick($userBadges, ['user_id']);

    if (!$badgeId || !$badgeName || !$userBadgeId || !$userBadgeUser) {
        return ['earned' => [], 'locked' => []];
    }

    $select = sprintf(
        'b.%s AS id, b.%s AS name, %s AS description, %s AS icon',
        dash_ident($badgeId),
        dash_ident($badgeName),
        $badgeDescription ? 'b.' . dash_ident($badgeDescription) : 'NULL',
        $badgeIcon ? 'b.' . dash_ident($badgeIcon) : 'NULL'
    );

    $stmt = $pdo->prepare(
        'SELECT ' . $select . '
         FROM badges b
         INNER JOIN user_badges ub ON ub.' . dash_ident($userBadgeId) . ' = b.' . dash_ident($badgeId) . '
         WHERE ub.' . dash_ident($userBadgeUser) . ' = ?
         ORDER BY b.' . dash_ident($badgeId)
    );
    $stmt->execute([$userId]);
    $earned = $stmt->fetchAll();

    $earnedIds = array_map(static fn(array $row): string => (string) $row['id'], $earned);

    if ($earnedIds) {
        $placeholders = implode(',', array_fill(0, count($earnedIds), '?'));

        $stmt = $pdo->prepare(
            'SELECT ' . $select . '
             FROM badges b
             WHERE b.' . dash_ident($badgeId) . ' NOT IN (' . $placeholders . ')
             ORDER BY b.' . dash_ident($badgeId)
        );
        $stmt->execute($earnedIds);
        $locked = $stmt->fetchAll();
    } else {
        $stmt = $pdo->query(
            'SELECT ' . $select . ' FROM badges b ORDER BY b.' . dash_ident($badgeId)
        );
        $locked = $stmt->fetchAll();
    }

    return ['earned' => $earned, 'locked' => $locked];
}

function dash_activity(PDO $pdo, int $userId): array
{
    $events = [];

    $sources = [
        [
            'table' => 'posts',
            'label' => 'Published a post',
            'type' => 'post',
            'users' => ['user_id', 'author_id', 'creator_id', 'created_by'],
            'dates' => ['created_at', 'published_at', 'posted_at', 'timestamp'],
            'icon' => 'fa-solid fa-feather-pointed',
        ],
        [
            'table' => 'comments',
            'label' => 'Added a comment',
            'type' => 'comment',
            'users' => ['user_id', 'author_id', 'creator_id', 'created_by'],
            'dates' => ['created_at', 'commented_at', 'posted_at', 'timestamp'],
            'icon' => 'fa-solid fa-comment-dots',
        ],
        [
            'table' => 'reputation_events',
            'label' => 'Received a reputation event',
            'type' => 'reputation',
            'users' => ['user_id'],
            'dates' => ['created_at', 'occurred_at', 'event_at'],
            'icon' => 'fa-solid fa-arrow-trend-up',
        ],
        [
            'table' => 'user_badges',
            'label' => 'Unlocked an achievement',
            'type' => 'badge',
            'users' => ['user_id'],
            'dates' => ['created_at', 'earned_at', 'awarded_at'],
            'icon' => 'fa-solid fa-award',
        ],
    ];

    foreach ($sources as $source) {
        if (!dash_table_exists($pdo, $source['table'])) {
            continue;
        }

        $columns = dash_columns($pdo, $source['table']);
        $userColumn = dash_pick($columns, $source['users']);
        $dateColumn = dash_pick($columns, $source['dates']);

        if (!$userColumn || !$dateColumn) {
            continue;
        }

        try {
            $sql = sprintf(
                'SELECT %s AS event_time FROM %s
                 WHERE %s = ?
                 ORDER BY %s DESC LIMIT 8',
                dash_ident($dateColumn),
                dash_ident($source['table']),
                dash_ident($userColumn),
                dash_ident($dateColumn)
            );

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);

            foreach ($stmt->fetchAll() as $row) {
                $events[] = [
                    'type' => $source['type'],
                    'label' => $source['label'],
                    'icon' => $source['icon'],
                    'timestamp' => $row['event_time'] ?? null,
                ];
            }
        } catch (Throwable) {
            continue;
        }
    }

    usort(
        $events,
        static fn(array $a, array $b): int =>
            strcmp((string) ($b['timestamp'] ?? ''), (string) ($a['timestamp'] ?? ''))
    );

    return array_slice($events, 0, 10);
}

function dash_user(PDO $pdo, int $userId): array
{
    $columns = dash_columns($pdo, 'users');

    $id = dash_pick($columns, ['id']);
    $username = dash_pick($columns, ['username']);
    $displayName = dash_pick($columns, ['display_name']);
    $firstName = dash_pick($columns, ['first_name']);
    $lastName = dash_pick($columns, ['last_name']);
    $registrationType = dash_pick($columns, ['registration_type']);
    $accountStatus = dash_pick($columns, ['account_status']);
    $emailVerified = dash_pick($columns, ['email_verified_at']);
    $createdAt = dash_pick($columns, ['created_at']);
    $lastLogin = dash_pick($columns, ['last_login_at']);
    $lastActivity = dash_pick($columns, ['last_activity_at']);
    $reputation = dash_pick($columns, ['reputation_points']);
    $avatar = dash_pick($columns, ['avatar_path', 'avatar_url']);

    if (!$id || !$username) {
        throw new RuntimeException('Users schema is missing required identity columns.');
    }

    $select = [
        dash_ident($id) . ' AS id',
        dash_ident($username) . ' AS username',
        $displayName ? dash_ident($displayName) . ' AS display_name' : 'NULL AS display_name',
        $firstName ? dash_ident($firstName) . ' AS first_name' : 'NULL AS first_name',
        $lastName ? dash_ident($lastName) . ' AS last_name' : 'NULL AS last_name',
        $registrationType ? dash_ident($registrationType) . ' AS registration_type' : "'professional' AS registration_type",
        $accountStatus ? dash_ident($accountStatus) . ' AS account_status' : "'active' AS account_status",
        $emailVerified ? dash_ident($emailVerified) . ' AS email_verified_at' : 'NULL AS email_verified_at',
        $createdAt ? dash_ident($createdAt) . ' AS created_at' : 'NULL AS created_at',
        $lastLogin ? dash_ident($lastLogin) . ' AS last_login_at' : 'NULL AS last_login_at',
        $lastActivity ? dash_ident($lastActivity) . ' AS last_activity_at' : 'NULL AS last_activity_at',
        $reputation ? dash_ident($reputation) . ' AS reputation_points' : '0 AS reputation_points',
        $avatar ? dash_ident($avatar) . ' AS avatar_path' : 'NULL AS avatar_path',
    ];

    $stmt = $pdo->prepare(
        'SELECT ' . implode(',', $select) . '
         FROM users
         WHERE ' . dash_ident($id) . ' = ?
         LIMIT 1'
    );
    $stmt->execute([$userId]);

    $user = $stmt->fetch();
    if (!$user) {
        http_response_code(401);
        dash_json(false, [], 'Account not found.');
    }

    $avatarUrl = null;
    if (!empty($user['avatar_path'])) {
        $path = '/' . ltrim(str_replace('\\', '/', (string) $user['avatar_path']), '/');

        if (
            str_starts_with($path, '/users/images/avatars/') &&
            !str_contains($path, '..')
        ) {
            $avatarUrl = $path;
        }
    }

    $name = trim((string) ($user['display_name'] ?? ''));
    if ($name === '') {
        $name = trim(
            (string) ($user['first_name'] ?? '') . ' ' .
            (string) ($user['last_name'] ?? '')
        );
    }
    if ($name === '') {
        $name = (string) $user['username'];
    }

    return [
        'id' => (int) $user['id'],
        'username' => (string) $user['username'],
        'display_name' => $name,
        'registration_type' => (string) $user['registration_type'],
        'account_status' => (string) $user['account_status'],
        'email_verified' => !empty($user['email_verified_at']),
        'created_at' => $user['created_at'],
        'last_login_at' => $user['last_login_at'],
        'last_activity_at' => $user['last_activity_at'],
        'reputation_points' => (int) $user['reputation_points'],
        'avatar_url' => $avatarUrl,
    ];
}

try {
    $pdo = nexora_db();
    $user = dash_user($pdo, $userId);

    $postUsers = ['user_id', 'author_id', 'creator_id', 'created_by'];
    $postDates = ['created_at', 'published_at', 'posted_at', 'timestamp'];
    $commentDates = ['created_at', 'commented_at', 'posted_at', 'timestamp'];

    $periods = [];
    foreach ([30, 60, 180, 365] as $days) {
        $posts = dash_count_period($pdo, 'posts', $postUsers, $postDates, $userId, $days);
        $comments = dash_count_period($pdo, 'comments', $postUsers, $commentDates, $userId, $days);

        $periods[(string) $days] = [
            'posts' => $posts,
            'comments' => $comments,
            'total' => $posts + $comments,
        ];
    }

    $postsTotal = dash_count_total($pdo, 'posts', $postUsers, $userId);
    $commentsTotal = dash_count_total($pdo, 'comments', $postUsers, $userId);
    $reactions = dash_reactions_received($pdo, $userId);
    $connections = dash_connections($pdo, $userId);
    $incoming = dash_requests($pdo, $userId, true);
    $outgoing = dash_requests($pdo, $userId, false);

    /*
     * Influence is deliberately bounded and transparent.
     * The database remains authoritative for the raw signals;
     * this layer only converts those signals into a presentation index.
     */
    $posting = min(100, (int) round(log1p($periods['365']['posts']) / log(101) * 100));
    $discussion = min(100, (int) round(log1p($periods['365']['comments']) / log(101) * 100));
    $engagement = min(100, (int) round(log1p($reactions) / log(501) * 100));
    $networking = min(100, (int) round(log1p($connections) / log(101) * 100));

    $score = (int) round(
        ($posting * 0.35) +
        ($discussion * 0.20) +
        ($engagement * 0.25) +
        ($networking * 0.20)
    ) * 10;

    $tier =
        $score >= 850 ? 'NEXORA LEGEND' :
        ($score >= 700 ? 'COMMUNITY ARCHITECT' :
        ($score >= 550 ? 'INFLUENCE ENGINE' :
        ($score >= 400 ? 'RISING VOICE' :
        ($score >= 200 ? 'ACTIVE CITIZEN' : 'SIGNAL DETECTED'))));

    dash_json(
        true,
        [
            'user' => $user,
            'influence' => [
                'score' => $score,
                'tier' => $tier,
                'components' => [
                    'posting' => $posting,
                    'discussion' => $discussion,
                    'engagement' => $engagement,
                    'networking' => $networking,
                ],
            ],
            'metrics' => [
                'posts_total' => $postsTotal,
                'comments_total' => $commentsTotal,
                'reactions_received' => $reactions,
                'connections' => $connections,
                'friend_requests_incoming' => $incoming,
                'friend_requests_outgoing' => $outgoing,
            ],
            'periods' => $periods,
            'badges' => dash_badges($pdo, $userId),
            'recent_activity' => dash_activity($pdo, $userId),
            'generated_at' => gmdate('c'),
        ],
        'Dashboard telemetry synchronized.'
    );
} catch (Throwable $e) {
    error_log('[NEXORA DASHBOARD] ' . get_class($e) . ': ' . $e->getMessage());
    dash_json(false, [], 'Your dashboard could not be synchronized right now.');
}
