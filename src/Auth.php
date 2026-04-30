<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use Oct8pus\Paddle\HttpHandler;
use Oct8pus\Paddle\RestBase;

class Auth extends RestBase
{
    private readonly string $token;

    /**
     * Constructor
     *
     * @param bool        $sandbox
     * @param HttpHandler $handler
     * @param string      $secret
     */
    public function __construct(bool $sandbox, HttpHandler $handler, string $secret)
    {
        parent::__construct($sandbox, $handler, null);

        $this->token = $secret;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function token() : string
    {
        return $this->token;
    }
}
