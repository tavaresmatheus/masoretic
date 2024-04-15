<?php

declare(strict_types=1);

namespace Masoretic\Services\Email;

use Dotenv\Dotenv;
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
    ): int
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->safeLoad();

        $email = new Mail();
        $email->setFrom(self::FROM, self::NAME);
        $email->setSubject($subject);
        $email->addTo($to, $name);
        $email->addContent('text/html', $body);
        $sendGrid = new SendGrid($_ENV['SENDGRID_API_KEY']);

        try {
            $response = $sendGrid->send($email);
            return $response->statusCode();
        } catch (\Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
}
