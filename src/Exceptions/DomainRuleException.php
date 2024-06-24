<?php

declare(strict_types=1);

namespace Masoretic\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;

class DomainRuleException extends HttpSpecializedException
{
    protected ServerRequestInterface $request;

    /**
     * @var int
     */
    protected $code = 422;

    /**
     * @var string
     */
    protected $message = 'Domain rule exception.';

    protected string $title = '422 Domain rule exception.';
    protected string $description = 'Your request dont obey the domain rules.';

    public function __construct(
        ServerRequestInterface $request,
        int $code,
        string $title,
        string $message = 'Domain rule exception.',
        string $description = 'Your request dont obey the domain rules.'
    ) {
        $this->request = $request;
        $this->code = $code;
        $this->title = $title;
        $this->message = $message;
        $this->description = $description;
    }
}
