<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;
use Oct8pus\Paddle\Auth\Auth;

class DiscountGroups extends RestBase
{
    /**
     * Constructor
     *
     * @param bool        $sandbox
     * @param HttpHandler $handler
     * @param Auth        $auth
     */
    public function __construct(bool $sandbox, HttpHandler $handler, Auth $auth)
    {
        parent::__construct($sandbox, $handler, $auth);
    }

    /**
     * List discount groups
     *
     * @return array<mixed>
     */
    public function list() : array
    {
        $url = '/discount-groups';

        $params = [
            //'id' => [string],
            //'after' => string,
            //'per_page' => integer,
            'order_by' => 'ASC',
        ];

        $url .= '?' . http_build_query($params);

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get discount group
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/discount-groups/{$id}";

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create discount group
     *
     * @param string $name
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create(string $name) : array
    {
        $group = [
            'name' => $name,
        ];

        $url = '/discount-groups';

        $response = $this->sendJsonRequest('POST', $url, [], $group, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update discount group
     *
     * @param string $id
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function update(string $id, string $key, string $value) : array
    {
        // possible keys: name, status

        $update = [
            $key => $value,
        ];

        $url = "/discount-groups/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Archive discount group
     *
     * @param  string $id
     *
     * @return self
     */
    public function archive(string $id) : self
    {
        $this->update($id, 'status', 'archived');

        return $this;
    }
}
