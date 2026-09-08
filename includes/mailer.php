<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Nexora Mailer
|--------------------------------------------------------------------------
|
| Centralized email delivery layer.
|
| This initial implementation uses PHP's configured mail transport.
| A dedicated SMTP/provider transport can be substituted later without
| changing the application-level email calls.
|
|--------------------------------------------------------------------------
*/

if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}


function nexora_mail_config(): array
{
    return nexora_db_config()['mail'] ?? [];
}


function nexora_send_email(
    string $recipient,
    string $subject,
    string $htmlBody,
    ?string $textBody = null
): bool {

    if (
        filter_var($recipient, FILTER_VALIDATE_EMAIL) === false
    ) {
        return false;
    }

    $config = nexora_mail_config();

    $fromEmail = (string) (
        $config['from_email']
        ?? 'noreply@nexora.beardedviking.org'
    );

    $fromName = (string) (
        $config['from_name']
        ?? 'Nexora'
    );

    if (
        filter_var($fromEmail, FILTER_VALIDATE_EMAIL) === false
    ) {
        return false;
    }

    $encodedSubject = mb_encode_mimeheader(
        $subject,
        'UTF-8'
    );

    $headers = [];

    $headers[] = 'MIME-Version: 1.0';

    $headers[] = 'From: '
        . mb_encode_mimeheader($fromName, 'UTF-8')
        . ' <'
        . $fromEmail
        . '>';

    $headers[] = 'Reply-To: ' . $fromEmail;

    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $headers[] = 'X-Mailer: Nexora';

    return mail(
        $recipient,
        $encodedSubject,
        $htmlBody,
        implode("\r\n", $headers)
    );
}


function nexora_send_vote_confirmation(
    string $recipient,
    string $username,
    string $aiName,
    string $roundTitle
): bool {

    $safeUsername = htmlspecialchars(
        $username,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $safeAiName = htmlspecialchars(
        $aiName,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $safeRoundTitle = htmlspecialchars(
        $roundTitle,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $subject = 'Nexora AI Race Vote Confirmation';

    $html = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Nexora Vote Confirmation</title>
</head>
<body>
    <h1>Nexora AI Race</h1>

    <p>Hello {$safeUsername},</p>

    <p>
        Your Nexora community vote has been successfully recorded.
    </p>

    <p>
        <strong>Voting Round:</strong>
        {$safeRoundTitle}
    </p>

    <p>
        <strong>Your Selection:</strong>
        {$safeAiName}
    </p>

    <p>
        Thank you for participating in the Nexora AI social media
        experiment.
    </p>

    <p>
        — Nexora Engineering
    </p>
</body>
</html>
HTML;

    return nexora_send_email(
        $recipient,
        $subject,
        $html
    );
}


function nexora_send_engineer_vote_alert(
    string $username,
    string $aiName,
    string $roundTitle
): bool {

    $config = nexora_mail_config();

    $engineerEmail = (string) (
        $config['engineer_email']
        ?? 'info@beardedviking.org'
    );

    if (
        filter_var($engineerEmail, FILTER_VALIDATE_EMAIL) === false
    ) {
        return false;
    }

    $safeUsername = htmlspecialchars(
        $username,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $safeAiName = htmlspecialchars(
        $aiName,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $safeRoundTitle = htmlspecialchars(
        $roundTitle,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $subject = 'Nexora AI Race — New Vote Recorded';

    $html = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Nexora Vote Alert</title>
</head>
<body>
    <h1>Nexora AI Race — New Vote</h1>

    <p>
        A new authenticated community vote has been recorded.
    </p>

    <p>
        <strong>User:</strong> {$safeUsername}
    </p>

    <p>
        <strong>AI Platform:</strong> {$safeAiName}
    </p>

    <p>
        <strong>Voting Round:</strong> {$safeRoundTitle}
    </p>

    <p>
        The vote was recorded by the Nexora backend after
        server-side validation.
    </p>
</body>
</html>
HTML;

    return nexora_send_email(
        $engineerEmail,
        $subject,
        $html
    );
}