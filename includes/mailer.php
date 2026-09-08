<?php
declare(strict_types=1);

if (!defined('NEXORA_BOOTSTRAPPED')) {
    http_response_code(403);
    exit('Forbidden');
}

/**
 * Send a plain-text email through the server mail transport.
 *
 * The application address is always used as the From address.
 * Visitor addresses are only used as Reply-To addresses.
 */
function nexora_send_email(
    string $to,
    string $subject,
    string $body,
    ?string $replyTo = null,
    ?string $replyToName = null
): bool {

    /*
     * Validate destination.
     */
    if (
        filter_var($to, FILTER_VALIDATE_EMAIL) === false
    ) {
        error_log(
            '[NEXORA MAILER] Invalid destination email address.'
        );

        return false;
    }

    /*
     * Prevent header injection.
     */
    foreach ([$to, $subject, $replyTo, $replyToName] as $headerValue) {

        if (
            $headerValue !== null
            && (
                str_contains($headerValue, "\r")
                || str_contains($headerValue, "\n")
            )
        ) {
            error_log(
                '[NEXORA MAILER] Header injection attempt blocked.'
            );

            return false;
        }
    }

    /*
     * Load Nexora mail configuration.
     */
    $config = nexora_db_config();

    $mailConfig = $config['mail'] ?? [];

    $fromEmail = (string) (
        $mailConfig['from_email']
        ?? 'noreply@nexora.beardedviking.org'
    );

    $fromName = (string) (
        $mailConfig['from_name']
        ?? 'Nexora'
    );

    /*
     * Validate configured From address.
     */
    if (
        filter_var($fromEmail, FILTER_VALIDATE_EMAIL) === false
    ) {
        error_log(
            '[NEXORA MAILER] Invalid configured From address.'
        );

        return false;
    }

    /*
     * Encode potentially non-ASCII display names safely.
     */
    $encodedFromName = 'Nexora';

    if ($fromName !== '') {
        $encodedFromName =
            '=?UTF-8?B?'
            . base64_encode($fromName)
            . '?=';
    }

    /*
     * Build mail headers.
     */
    $headers = [];

    $headers[] = 'MIME-Version: 1.0';

    $headers[] =
        'Content-Type: text/plain; charset=UTF-8';

    $headers[] =
        'Content-Transfer-Encoding: 8bit';

    $headers[] =
        'From: '
        . $encodedFromName
        . ' <'
        . $fromEmail
        . '>';

    /*
     * Visitor email becomes Reply-To.
     *
     * NEVER put an untrusted visitor address into From.
     */
    if (
        $replyTo !== null
        && filter_var(
            $replyTo,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        if (
            $replyToName !== null
            && trim($replyToName) !== ''
        ) {

            $encodedReplyName =
                '=?UTF-8?B?'
                . base64_encode(
                    trim($replyToName)
                )
                . '?=';

            $headers[] =
                'Reply-To: '
                . $encodedReplyName
                . ' <'
                . $replyTo
                . '>';

        } else {

            $headers[] =
                'Reply-To: '
                . $replyTo;
        }
    }

    /*
     * Send the message.
     */
    try {

        return mail(
            $to,
            $subject,
            $body,
            implode("\r\n", $headers)
        );

    } catch (Throwable $exception) {

        error_log(
            '[NEXORA MAILER] '
            . get_class($exception)
            . ': '
            . $exception->getMessage()
        );

        return false;
    }
}