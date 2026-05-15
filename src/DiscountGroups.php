<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;

class DiscountGroups extends RestBase
{
    /**
     * List discount groups
     *
     * @param array $conditions
     *
     * @return array<mixed>
     */
    public function list(array $conditions = []) : array
    {
        $url = '/discount-groups';

        /*
        $conditions = [
            'id' => [string],
            'after' => string,
            'per_page' => integer,
            'order_by' => 'id[ASC]',
        ];
        */

        if (count($conditions)) {
            $url .= '?' . http_build_query($conditions);
        }

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
     * @throws PaddleException|JsonException
     */
    public function create(string $name) : array
    {
        $group = [
            'name' => $name,
        ];

        return $this->createFromArray($group);
    }

    /**
     * Create from array
     *
     * @param  array  $group
     *
     * @return array
     */
    public function createFromArray(array $group) : array
    {
        $url = '/discount-groups';

        $response = $this->sendJsonRequest('POST', $url, [], $group, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update discount group
     *
     * @param string $id
     * @param string $key
     * @param string|bool|int|array $value
     *
     * @return array
     */
    public function update(string $id, string $key, string|bool|int|array $value) : array
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
     * @param string $id
     *
     * @return self
     */
    public function archive(string $id) : self
    {
        $this->update($id, 'status', 'archived');

        return $this;
    }
}
