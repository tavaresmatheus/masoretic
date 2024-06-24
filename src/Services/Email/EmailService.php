<?php

declare(strict_types=1);

namespace Masoretic\Services\Email;

use Exception;
use SendGrid;
use SendGrid\Mail\Mail;

class EmailService implements EmailServiceInterface
{
    protected const FROM = 'tm.developer.mt@gmail.com';
    protected const NAME = 'Masoretic';

    public function sendConfirmationEmail(
        string $to,
        string $name,
        string $subject,
        string $body
    ): int {
        $email = new Mail();
        $email->setFrom(self::FROM, self::NAME);
        $email->setSubject($subject);
        $email->addTo($to, $name);
        $email->addContent('text/html', $body);
        $sendGrid = new SendGrid(getenv('SENDGRID_API_KEY'));

        try {
            $response = $sendGrid->send($email);
            return $response->statusCode();
        } catch (\Exception $e) {
            throw new $e();
        }
    }
}
