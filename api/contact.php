<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once dirname(__DIR__) . '/includes/cookies.php';
require_once dirname(__DIR__) . '/includes/database.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

header('Content-Type: application/json; charset=UTF-8');


function nexora_contact_response(
    bool $success,
    string $message,
    int $status = 200
): never {

    http_response_code($status);

    echo json_encode(
        [
            'success' => $success,
            'message' => $message,
        ],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| HTTP Method
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    nexora_contact_response(
        false,
        'This communication channel accepts POST transmissions only.',
        405
    );
}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$csrfToken = $_POST['csrf_token'] ?? null;

if (!nexora_verify_csrf(
    is_string($csrfToken) ? $csrfToken : null
)) {

    nexora_contact_response(
        false,
        'Security validation failed. Please refresh the page and try again.',
        403
    );
}


/*
|--------------------------------------------------------------------------
| Honeypot
|--------------------------------------------------------------------------
*/

$honeypot = $_POST['website'] ?? '';

if (is_string($honeypot) && trim($honeypot) !== '') {

    /*
     * Pretend the request succeeded.
     * Do not tell automated systems they triggered the honeypot.
     */

    nexora_contact_response(
        true,
        'Your transmission has been received.'
    );
}


/*
|--------------------------------------------------------------------------
| Input
|--------------------------------------------------------------------------
*/

$name = trim(
    is_string($_POST['name'] ?? null)
        ? $_POST['name']
        : ''
);

$email = trim(
    is_string($_POST['email'] ?? null)
        ? $_POST['email']
        : ''
);

$subject = trim(
    is_string($_POST['subject'] ?? null)
        ? $_POST['subject']
        : ''
);

$topic = trim(
    is_string($_POST['topic'] ?? null)
        ? $_POST['topic']
        : ''
);

$message = trim(
    is_string($_POST['message'] ?? null)
        ? $_POST['message']
        : ''
);


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$allowedTopics = [
    'general',
    'nexora',
    'collaboration',
    'security',
    'vulnerability',
    'media',
    'business',
    'feedback',
];


if ($name === '' || mb_strlen($name) > 100) {

    nexora_contact_response(
        false,
        'Please provide a valid name.',
        422
    );
}


if (
    $email === ''
    || mb_strlen($email) > 254
    || filter_var($email, FILTER_VALIDATE_EMAIL) === false
) {

    nexora_contact_response(
        false,
        'Please provide a valid email address.',
        422
    );
}


if ($subject === '' || mb_strlen($subject) > 180) {

    nexora_contact_response(
        false,
        'Please provide a valid subject.',
        422
    );
}


if (!in_array($topic, $allowedTopics, true)) {

    nexora_contact_response(
        false,
        'Please select a valid communication type.',
        422
    );
}


if ($message === '' || mb_strlen($message) > 5000) {

    nexora_contact_response(
        false,
        'Please provide a message between 1 and 5000 characters.',
        422
    );
}


/*
|--------------------------------------------------------------------------
| Header Injection Protection
|--------------------------------------------------------------------------
*/

foreach ([$name, $email, $subject] as $headerValue) {

    if (
        str_contains($headerValue, "\r")
        || str_contains($headerValue, "\n")
    ) {

        nexora_contact_response(
            false,
            'Invalid message data detected.',
            422
        );
    }
}


/*
|--------------------------------------------------------------------------
| Topic Labels
|--------------------------------------------------------------------------
*/

$topicLabels = [
    'general'       => 'General Inquiry',
    'nexora'        => 'Nexora / AI Race',
    'collaboration' => 'Collaboration / Partnership',
    'security'      => 'Security Research',
    'vulnerability'=> 'Responsible Vulnerability Disclosure',
    'media'         => 'Media / Interview',
    'business'      => 'Business Inquiry',
    'feedback'      => 'Website / Project Feedback',
];

$topicLabel = $topicLabels[$topic];


/*
|--------------------------------------------------------------------------
| Metadata
|--------------------------------------------------------------------------
*/

$ip = nexora_client_ip();

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (!is_string($userAgent)) {
    $userAgent = '';
}

$userAgent = mb_substr($userAgent, 0, 500);


/*
|--------------------------------------------------------------------------
| Email To Operations
|--------------------------------------------------------------------------
*/

$adminSubject =
    '[NEXORA CONTACT] '
    . $topicLabel
    . ' — '
    . $subject;


$adminBody =
    "NEXORA CONTACT TRANSMISSION\n"
    . "===========================\n\n"
    . "Name: " . $name . "\n"
    . "Email: " . $email . "\n"
    . "Topic: " . $topicLabel . "\n"
    . "Subject: " . $subject . "\n\n"
    . "Message\n"
    . "-------\n"
    . $message
    . "\n\n"
    . "Transmission Metadata\n"
    . "---------------------\n"
    . "IP: " . ($ip ?? 'Unavailable') . "\n"
    . "User Agent: " . $userAgent . "\n"
    . "Received: " . gmdate('Y-m-d H:i:s') . " UTC\n"
    . "Source: Nexora Contact Form\n";


/*
|--------------------------------------------------------------------------
| Send Operations Email
|--------------------------------------------------------------------------
*/

try {

    $sent = nexora_send_email(
        'info@beardedviking.org',
        $adminSubject,
        $adminBody,
        $email,
        $name
    );

    if (!$sent) {

        error_log(
            '[NEXORA CONTACT] Failed to send operations email.'
        );

        nexora_contact_response(
            false,
            'The transmission could not be delivered right now. Please email info@beardedviking.org directly.',
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Visitor Confirmation
    |--------------------------------------------------------------------------
    */

    $confirmationSubject =
        'Nexora received your transmission';


    $confirmationBody =
        "Hello " . $name . ",\n\n"
        . "Your message has been received by the Bearded Viking "
        . "operations channel.\n\n"
        . "Topic: " . $topicLabel . "\n"
        . "Subject: " . $subject . "\n\n"
        . "A member of the operation can review your transmission "
        . "and respond through the email address you provided.\n\n"
        . "Please do not reply to this automated confirmation "
        . "with passwords, private keys, authentication tokens, "
        . "or other sensitive credentials.\n\n"
        . "— Nexora\n"
        . "Bearded Viking Operations\n";


    $confirmationSent = nexora_send_email(
        $email,
        $confirmationSubject,
        $confirmationBody,
        'info@beardedviking.org',
        'Nexora Operations'
    );


    if (!$confirmationSent) {

        error_log(
            '[NEXORA CONTACT] Visitor confirmation email failed for '
            . hash('sha256', strtolower($email))
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Optional Audit Event
    |--------------------------------------------------------------------------
    */

    try {

        $pdo = nexora_db();

        $statement = $pdo->prepare(
            "INSERT INTO security_events
                (
                    event_type,
                    severity,
                    ip_hash,
                    user_agent_hash,
                    details,
                    created_at
                )
             VALUES
                (
                    :event_type,
                    :severity,
                    :ip_hash,
                    :user_agent_hash,
                    :details,
                    UTC_TIMESTAMP()
                )"
        );

        $statement->execute([
            ':event_type' =>
                'contact_form_submission',

            ':severity' =>
                'info',

            ':ip_hash' =>
                nexora_client_ip_hash(),

            ':user_agent_hash' =>
                nexora_user_agent_hash(),

            ':details' =>
                json_encode(
                    [
                        'topic' => $topic,
                        'email_hash' =>
                            hash(
                                'sha256',
                                strtolower($email)
                            ),
                    ],
                    JSON_UNESCAPED_SLASHES
                ),
        ]);

    } catch (Throwable $auditException) {

        error_log(
            '[NEXORA CONTACT] Audit logging failed: '
            . $auditException->getMessage()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    nexora_contact_response(
        true,
        'Transmission received. A confirmation has been sent to your email address.'
    );


} catch (Throwable $exception) {

    error_log(
        '[NEXORA CONTACT] '
        . get_class($exception)
        . ': '
        . $exception->getMessage()
    );

    nexora_contact_response(
        false,
        'The communications system encountered an unexpected error. Please try again later.',
        500
    );
}