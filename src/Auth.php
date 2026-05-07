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
     * @param string      $token - Api key
     *
     * @throws PaddleException
     */
    public function __construct(bool $sandbox, HttpHandler $handler, string $token)
    {
        parent::__construct($sandbox, $handler, null);

        if (empty($token)) {
            throw new PaddleException('api key missing');
        }

        $this->token = $token;
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
