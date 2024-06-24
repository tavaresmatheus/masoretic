<?php

declare(strict_types=1);

namespace Masoretic\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;

class AuthenticationException extends HttpSpecializedException
{
    protected ServerRequestInterface $request;
    protected $code = 403;
    protected $message = 'Authentication exception.';
    protected string $title = '403 Invalid/Expired token.';
    protected string $description = 'Your request dont have a valid Token.';

    public function __construct(
        ServerRequestInterface $request,
        int $code,
        string $title,
        string $message = 'Authentication exception.',
        string $description = 'Your request dont have a valid Token.'
    ) {
        $this->request = $request;
        $this->code = $code;
        $this->title = $title;
        $this->message = $message;
        $this->description = $description;
    }
}
