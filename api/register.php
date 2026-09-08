<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once dirname(__DIR__) . '/includes/cookies.php';
require_once dirname(__DIR__) . '/includes/database.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function nexora_register_response(
    bool $success,
    string $message,
    int $status = 200,
    array $extra = []
): never {
    http_response_code($status);

    echo json_encode(
        array_merge(
            [
                'success' => $success,
                'message' => $message,
            ],
            $extra
        ),
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    exit;
}


/*
 * Only POST is permitted.
 */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    nexora_register_response(
        false,
        'Registration requests must use POST.',
        405
    );
}


/*
 * CSRF protection.
 */
$csrfToken = $_POST['csrf_token'] ?? null;

if (!nexora_verify_csrf(
    is_string($csrfToken) ? $csrfToken : null
)) {
    nexora_register_response(
        false,
        'Your security token is invalid or expired. Please refresh the page and try again.',
        403
    );
}


/*
 * Honeypot.
 */
$honeypot = $_POST['website'] ?? '';

if (is_string($honeypot) && trim($honeypot) !== '') {
    nexora_register_response(
        true,
        'Your registration request has been received.',
        200,
        [
            'redirect' => '/users/dashboard.php'
        ]
    );
}


/*
 * Helpers.
 */
function nexora_register_string(
    string $key,
    int $maxLength = 255
): string {
    $value = $_POST[$key] ?? '';

    if (!is_string($value)) {
        return '';
    }

    $value = trim($value);

    if (function_exists('mb_substr')) {
        return mb_substr(
            $value,
            0,
            $maxLength,
            'UTF-8'
        );
    }

    return substr($value, 0, $maxLength);
}


function nexora_register_clean_name(string $value): string
{
    $value = preg_replace(
        '/[\x00-\x1F\x7F]/u',
        '',
        $value
    ) ?? '';

    return trim($value);
}


/*
 * Database.
 */
try {
    $pdo = nexora_db();
} catch (Throwable $exception) {

    error_log(
        '[NEXORA REGISTER] Database initialization failed: '
        . $exception->getMessage()
    );

    nexora_register_response(
        false,
        'Nexora registration is temporarily unavailable. Please try again later.',
        503
    );
}


/*
 * Registration rate limiting.
 *
 * This assumes registration_rate_limits exists.
 */
function nexora_registration_rate_limited(PDO $pdo): bool
{
    $ipHash = nexora_client_ip_hash();

    if ($ipHash === null) {
        return true;
    }

    $cutoff = (new DateTimeImmutable(
        'now',
        new DateTimeZone('UTC')
    ))
        ->modify('-15 minutes')
        ->format('Y-m-d H:i:s');

    try {
        $cleanup = $pdo->prepare(
            'DELETE FROM registration_rate_limits
             WHERE attempted_at < :cleanup_cutoff'
        );

        $cleanup->execute([
            ':cleanup_cutoff' => (new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC')
            ))
                ->modify('-1 day')
                ->format('Y-m-d H:i:s')
        ]);
    } catch (Throwable $exception) {
        error_log(
            '[NEXORA REGISTER] Rate-limit cleanup failed: '
            . $exception->getMessage()
        );
    }

    $check = $pdo->prepare(
        'SELECT COUNT(*)
         FROM registration_rate_limits
         WHERE ip_hash = :ip_hash
         AND attempted_at >= :cutoff'
    );

    $check->execute([
        ':ip_hash' => $ipHash,
        ':cutoff' => $cutoff
    ]);

    return ((int) $check->fetchColumn()) >= 5;
}


function nexora_record_registration_attempt(PDO $pdo): void
{
    $ipHash = nexora_client_ip_hash();

    if ($ipHash === null) {
        return;
    }

    $statement = $pdo->prepare(
        'INSERT INTO registration_rate_limits
            (ip_hash, attempted_at)
         VALUES
            (:ip_hash, UTC_TIMESTAMP())'
    );

    $statement->execute([
        ':ip_hash' => $ipHash
    ]);
}


/*
 * Rate limit before expensive processing.
 */
try {

    if (nexora_registration_rate_limited($pdo)) {
        nexora_register_response(
            false,
            'Too many registration attempts. Please wait 15 minutes before trying again.',
            429
        );
    }

    nexora_record_registration_attempt($pdo);

} catch (Throwable $exception) {

    error_log(
        '[NEXORA REGISTER] Rate limiter failed: '
        . $exception->getMessage()
    );

    nexora_register_response(
        false,
        'Registration is temporarily unavailable. Please try again later.',
        503
    );
}


/*
 * Registration mode.
 */
$registrationType = nexora_register_string(
    'registration_type',
    32
);

if (!in_array(
    $registrationType,
    ['anonymous', 'professional'],
    true
)) {
    nexora_register_response(
        false,
        'Invalid registration type.',
        422
    );
}


/*
 * Alias.
 */
$alias = nexora_register_clean_name(
    nexora_register_string('alias', 80)
);

if ($alias === '') {
    nexora_register_response(
        false,
        'Please choose an alias.',
        422
    );
}

$aliasLength = function_exists('mb_strlen')
    ? mb_strlen($alias, 'UTF-8')
    : strlen($alias);

if ($aliasLength < 3) {
    nexora_register_response(
        false,
        'Please choose an alias containing at least 3 characters.',
        422
    );
}

if ($aliasLength > 80) {
    nexora_register_response(
        false,
        'Your alias cannot exceed 80 characters.',
        422
    );
}

if (preg_match(
    '/[\x00-\x1F\x7F]/u',
    $alias
)) {
    nexora_register_response(
        false,
        'Your alias contains unsupported characters.',
        422
    );
}


/*
 * Normalize alias into internal username.
 */
$username = function_exists('mb_strtolower')
    ? mb_strtolower($alias, 'UTF-8')
    : strtolower($alias);

$username = preg_replace(
    '/\s+/u',
    '_',
    $username
) ?? '';

$username = preg_replace(
    '/[^a-z0-9_-]/i',
    '',
    $username
) ?? '';

$username = strtolower(
    trim($username, '_-')
);

if (strlen($username) < 3 || strlen($username) > 32) {
    nexora_register_response(
        false,
        'That alias cannot be used as a Nexora username. Please choose a simpler alias.',
        422
    );
}


/*
 * Reserved usernames.
 */
$reservedUsernames = [
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

if (in_array(
    $username,
    $reservedUsernames,
    true
)) {
    nexora_register_response(
        false,
        'That username is reserved by Nexora.',
        409
    );
}


/*
 * Professional identity fields.
 */
$firstName = nexora_register_clean_name(
    nexora_register_string('first_name', 80)
);

$lastName = nexora_register_clean_name(
    nexora_register_string('last_name', 80)
);

if ($registrationType === 'professional') {

    if ($firstName === '' || $lastName === '') {
        nexora_register_response(
            false,
            'Professional registration requires your first and last name.',
            422
        );
    }

} else {

    $firstName = '';
    $lastName = '';
}


/*
 * Email.
 *
 * Anonymous: optional.
 * Professional: REQUIRED.
 */
$email = nexora_register_string(
    'email',
    254
);

if ($email === '') {
    $email = null;
}

if ($registrationType === 'professional') {

    if ($email === null) {
        nexora_register_response(
            false,
            'Professional registration requires an email address.',
            422
        );
    }

}

if ($email !== null) {

    $email = strtolower($email);

    if (filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    ) === false) {
        nexora_register_response(
            false,
            'Please provide a valid email address.',
            422
        );
    }
}


/*
 * Password.
 */
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

if (
    !is_string($password) ||
    !is_string($passwordConfirm)
) {
    nexora_register_response(
        false,
        'Invalid password submission.',
        422
    );
}

$passwordLength = strlen($password);

if ($passwordLength < 12) {
    nexora_register_response(
        false,
        'Your password must contain at least 12 characters.',
        422
    );
}

if ($passwordLength > 128) {
    nexora_register_response(
        false,
        'Your password cannot exceed 128 characters.',
        422
    );
}

if (!hash_equals(
    $password,
    $passwordConfirm
)) {
    nexora_register_response(
        false,
        'Your passwords do not match.',
        422
    );
}


/*
 * Consent.
 */
if (
    ($_POST['accept_terms'] ?? '') !== '1' ||
    ($_POST['accept_privacy'] ?? '') !== '1'
) {
    nexora_register_response(
        false,
        'Please acknowledge the required registration and privacy statements.',
        422
    );
}


/*
 * Password hashing.
 *
 * Argon2id when available.
 * Bcrypt fallback.
 */
try {

    $passwordAlgorithm = defined('PASSWORD_ARGON2ID')
        ? PASSWORD_ARGON2ID
        : PASSWORD_BCRYPT;

    $passwordHash = password_hash(
        $password,
        $passwordAlgorithm
    );

    if (
        !is_string($passwordHash) ||
        $passwordHash === ''
    ) {
        throw new RuntimeException(
            'Password hashing failed.'
        );
    }

} catch (Throwable $exception) {

    error_log(
        '[NEXORA REGISTER] Password hashing failed: '
        . $exception->getMessage()
    );

    nexora_register_response(
        false,
        'Registration is temporarily unavailable. Please try again later.',
        503
    );
}


/*
 * Profile directory.
 *
 * Username itself is already normalized and unique.
 */
$profileDirectory = $username;

$avatarsRoot = dirname(__DIR__)
    . '/users/images/avatars';

$bannersRoot = dirname(__DIR__)
    . '/users/images/banners';

$avatarDirectory = $avatarsRoot
    . '/'
    . $profileDirectory;

$bannerDirectory = $bannersRoot
    . '/'
    . $profileDirectory;


/*
 * Avatar upload metadata.
 */
$avatarFile = $_FILES['avatar'] ?? null;

if (
    is_array($avatarFile) &&
    isset($avatarFile['error']) &&
    (int) $avatarFile['error'] !== UPLOAD_ERR_NO_FILE
) {

    if (
        (int) $avatarFile['error'] !== UPLOAD_ERR_OK
    ) {
        nexora_register_response(
            false,
            'The avatar upload could not be processed.',
            422
        );
    }

    if (
        !isset($avatarFile['size']) ||
        (int) $avatarFile['size'] > 5 * 1024 * 1024
    ) {
        nexora_register_response(
            false,
            'Your avatar must be 5 MB or smaller.',
            422
        );
    }

    if (
        !isset($avatarFile['tmp_name']) ||
        !is_uploaded_file($avatarFile['tmp_name'])
    ) {
        nexora_register_response(
            false,
            'The avatar upload is invalid.',
            422
        );
    }
}


/*
 * Check whether the profile directory already exists.
 */
try {

    $directoryCheck = $pdo->prepare(
        'SELECT id
         FROM users
         WHERE profile_directory = :profile_directory
         LIMIT 1'
    );

    $directoryCheck->execute([
        ':profile_directory' => $profileDirectory
    ]);

    if ($directoryCheck->fetch()) {
        nexora_register_response(
            false,
            'That identity is already registered. Please choose another alias.',
            409
        );
    }

    if (
        file_exists($avatarDirectory) ||
        file_exists($bannerDirectory)
    ) {
        nexora_register_response(
            false,
            'That identity workspace already exists. Please choose another alias.',
            409
        );
    }

} catch (Throwable $exception) {

    error_log(
        '[NEXORA REGISTER] Profile directory check failed: '
        . $exception->getMessage()
    );

    nexora_register_response(
        false,
        'Registration is temporarily unavailable. Please try again later.',
        503
    );
}


/*
 * Create profile directories.
 */
try {

    if (
        !is_dir($avatarsRoot) &&
        !mkdir($avatarsRoot, 0755, true) &&
        !is_dir($avatarsRoot)
    ) {
        throw new RuntimeException(
            'Avatar root directory could not be created.'
        );
    }

    if (
        !is_dir($bannersRoot) &&
        !mkdir($bannersRoot, 0755, true) &&
        !is_dir($bannersRoot)
    ) {
        throw new RuntimeException(
            'Banner root directory could not be created.'
        );
    }

    if (
        !mkdir($avatarDirectory, 0755, true) &&
        !is_dir($avatarDirectory)
    ) {
        throw new RuntimeException(
            'Avatar directory could not be created.'
        );
    }

    if (
        !mkdir($bannerDirectory, 0755, true) &&
        !is_dir($bannerDirectory)
    ) {
        throw new RuntimeException(
            'Banner directory could not be created.'
        );
    }

} catch (Throwable $exception) {

    error_log(
        '[NEXORA REGISTER] Directory creation failed: '
        . $exception->getMessage()
    );

    nexora_register_response(
        false,
        'Your profile workspace could not be created. Please try again later.',
        500
    );
}


/*
 * Avatar validation/storage.
 */
$avatarRelativePath = null;
$avatarAbsolutePath = null;

if (
    is_array($avatarFile) &&
    isset($avatarFile['error']) &&
    (int) $avatarFile['error'] === UPLOAD_ERR_OK
) {

    $tmpName = (string) $avatarFile['tmp_name'];

    $imageInfo = @getimagesize($tmpName);

    if (
        $imageInfo === false ||
        empty($imageInfo['mime'])
    ) {
        @rmdir($avatarDirectory);
        @rmdir($bannerDirectory);

        nexora_register_response(
            false,
            'The selected avatar is not a valid image.',
            422
        );
    }

    $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    $mime = (string) $imageInfo['mime'];

    if (!isset($allowedMimeTypes[$mime])) {

        @rmdir($avatarDirectory);
        @rmdir($bannerDirectory);

        nexora_register_response(
            false,
            'That avatar image format is not supported.',
            422
        );
    }

    $width = isset($imageInfo[0])
        ? (int) $imageInfo[0]
        : 0;

    $height = isset($imageInfo[1])
        ? (int) $imageInfo[1]
        : 0;

    if (
        $width < 32 ||
        $height < 32 ||
        $width > 6000 ||
        $height > 6000
    ) {

        @rmdir($avatarDirectory);
        @rmdir($bannerDirectory);

        nexora_register_response(
            false,
            'Your avatar dimensions are outside the supported range.',
            422
        );
    }

    $extension = $allowedMimeTypes[$mime];

    $avatarFilename =
        'avatar_' .
        bin2hex(random_bytes(24)) .
        '.' .
        $extension;

    $avatarAbsolutePath =
        $avatarDirectory .
        '/' .
        $avatarFilename;

    if (!move_uploaded_file(
        $tmpName,
        $avatarAbsolutePath
    )) {

        @rmdir($avatarDirectory);
        @rmdir($bannerDirectory);

        nexora_register_response(
            false,
            'The avatar could not be stored. Please try again.',
            500
        );
    }

    @chmod(
        $avatarAbsolutePath,
        0644
    );

    $avatarRelativePath =
        '/users/images/avatars/' .
        $profileDirectory .
        '/' .
        $avatarFilename;
}


/*
 * Database registration.
 */
try {

    $pdo->beginTransaction();

    /*
     * IMPORTANT:
     * We do NOT repeat named placeholders.
     *
     * We also rely on the UNIQUE constraints on username/email
     * as the final race-safe protection.
     */

    if ($email !== null) {

        $duplicate = $pdo->prepare(
            'SELECT id, username, email
             FROM users
             WHERE username = :username
                OR email = :email
             LIMIT 1'
        );

        $duplicate->execute([
            ':username' => $username,
            ':email' => $email
        ]);

    } else {

        $duplicate = $pdo->prepare(
            'SELECT id, username
             FROM users
             WHERE username = :username
             LIMIT 1'
        );

        $duplicate->execute([
            ':username' => $username
        ]);
    }

    $existing = $duplicate->fetch();

    if ($existing) {

        if (
            isset($existing['username']) &&
            (string) $existing['username'] === $username
        ) {
            throw new RuntimeException(
                'USERNAME_ALREADY_EXISTS'
            );
        }

        throw new RuntimeException(
            'EMAIL_ALREADY_EXISTS'
        );
    }


    /*
     * Insert user.
     */
    $insert = $pdo->prepare(
        'INSERT INTO users
        (
            username,
            first_name,
            last_name,
            registration_type,
            email,
            password_hash,
            display_name,
            avatar_path,
            banner_path,
            profile_directory,
            account_status,
            email_verified_at,
            reputation_points,
            created_at,
            updated_at
        )
        VALUES
        (
            :username,
            :first_name,
            :last_name,
            :registration_type,
            :email,
            :password_hash,
            :display_name,
            :avatar_path,
            NULL,
            :profile_directory,
            "active",
            NULL,
            0,
            UTC_TIMESTAMP(),
            UTC_TIMESTAMP()
        )'
    );

    $insert->execute([
        ':username' =>
            $username,

        ':first_name' =>
            $firstName !== ''
                ? $firstName
                : null,

        ':last_name' =>
            $lastName !== ''
                ? $lastName
                : null,

        ':registration_type' =>
            $registrationType,

        ':email' =>
            $email,

        ':password_hash' =>
            $passwordHash,

        ':display_name' =>
            $alias,

        ':avatar_path' =>
            $avatarRelativePath,

        ':profile_directory' =>
            $profileDirectory,
    ]);

    $userId = (int) $pdo->lastInsertId();


    /*
     * Audit logging is BEST EFFORT.
     *
     * It must never destroy a successful registration.
     */
    try {

        $audit = $pdo->prepare(
            'INSERT INTO audit_log
            (
                user_id,
                action,
                created_at
            )
            VALUES
            (
                :user_id,
                :action,
                UTC_TIMESTAMP()
            )'
        );

        $audit->execute([
            ':user_id' =>
                $userId,

            ':action' =>
                'account_registered'
        ]);

    } catch (Throwable $auditException) {

        error_log(
            '[NEXORA REGISTER] Audit logging failed: ' .
            $auditException->getMessage()
        );
    }


    $pdo->commit();

} catch (Throwable $exception) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    /*
     * Remove uploaded avatar if registration failed.
     */
    if (
        $avatarAbsolutePath !== null &&
        is_file($avatarAbsolutePath)
    ) {
        @unlink($avatarAbsolutePath);
    }

    @rmdir($avatarDirectory);
    @rmdir($bannerDirectory);


    $errorMessage = strtolower(
        $exception->getMessage()
    );

    error_log(
        '[NEXORA REGISTER] Database registration failed: ' .
        $exception->getMessage()
    );


    if (
        $exception instanceof RuntimeException &&
        $exception->getMessage() === 'USERNAME_ALREADY_EXISTS'
    ) {
        nexora_register_response(
            false,
            'That alias is already registered. Please choose another.',
            409
        );
    }


    if (
        $exception instanceof RuntimeException &&
        $exception->getMessage() === 'EMAIL_ALREADY_EXISTS'
    ) {
        nexora_register_response(
            false,
            'That email address is already associated with a Nexora account.',
            409
        );
    }


    /*
     * Handle database UNIQUE races.
     */
    if (
        $exception instanceof PDOException &&
        (
            $exception->getCode() === '23000' ||
            str_contains($errorMessage, 'duplicate')
        )
    ) {
        nexora_register_response(
            false,
            'That username or email address has just been registered by another account.',
            409
        );
    }


    nexora_register_response(
        false,
        'Your registration could not be completed. Please try again later.',
        500
    );
}


/*
 * Establish authenticated session.
 */
try {

    nexora_regenerate_session(true);

    $_SESSION['nexora_authenticated'] = true;
    $_SESSION['nexora_user_id'] = $userId;
    $_SESSION['nexora_username'] = $username;
    $_SESSION['nexora_display_name'] = $alias;
    $_SESSION['nexora_registration_type'] =
        $registrationType;
    $_SESSION['nexora_last_activity'] =
        time();

    /*
     * Rotate CSRF token after authentication.
     */
    $_SESSION['nexora_csrf_token'] =
        bin2hex(random_bytes(32));

} catch (Throwable $exception) {

    error_log(
        '[NEXORA REGISTER] Session initialization failed: ' .
        $exception->getMessage()
    );

    nexora_register_response(
        false,
        'Your account was created, but your secure session could not be initialized. Please log in manually.',
        500
    );
}


/*
 * Welcome email.
 *
 * Non-fatal.
 */
if ($email !== null) {

    $welcomeSubject =
        'Welcome to Nexora — Your Identity Has Been Forged';

    $welcomeBody =
        "Welcome to Nexora, {$alias}!\n\n" .
        "Your Nexora identity has been successfully created.\n\n" .
        "Username: {$username}\n" .
        "Registration mode: {$registrationType}\n\n" .
        "You can now enter your personal Nexora dashboard " .
        "and begin building your presence in the network.\n\n" .
        "For your security, Nexora never stores your password " .
        "in plaintext.\n\n" .
        "If you did not create this account, please contact " .
        "Nexora Operations at info@beardedviking.org.\n\n" .
        "Welcome aboard.\n\n" .
        "— Nexora Operations\n" .
        "https://nexora.beardedviking.org";

    try {

        $mailSent = nexora_send_email(
            $email,
            $welcomeSubject,
            $welcomeBody,
            'info@beardedviking.org',
            'Nexora Operations'
        );

        if (!$mailSent) {
            error_log(
                '[NEXORA REGISTER] Welcome email could not be sent for user ID ' .
                $userId
            );
        }

    } catch (Throwable $mailException) {

        error_log(
            '[NEXORA REGISTER] Welcome email exception: ' .
            $mailException->getMessage()
        );
    }
}


/*
 * Final response.
 */
nexora_register_response(
    true,
    'Welcome to Nexora, ' . $alias . '. Your secure identity has been created.',
    201,
    [
        'redirect' => '/users/dashboard.php'
    ]
);