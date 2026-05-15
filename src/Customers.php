<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;

class Customers extends RestBase
{
    /**
     * List customers
     *
     * @param $conditions
     *
     * @return array<mixed>
     */
    public function list(array $conditions = []) : array
    {
        $url = '/customers';

        /*
        $conditions = [
            'id' => [string],
            'after' => string,
            'per_page' => integer,
            'email' => [string],
            'order_by' => 'id[ASC]',
            'status' => 'active,archived',
            'search' => string,
        ];
        */

        if (count($conditions)) {
            $url .= '?' . http_build_query($conditions);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get customer
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/customers/{$id}";

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create customer
     *
     * @param  string $email
     * @param  ?string $name
     *
     * @return array
     *
     * @throws PaddleException|JsonException
     */
    public function create(string $email, ?string $name) : array
    {
        $customer = [
            'email' => $email,
            'name' => $name,
            //'locale' => $locale,
            //'custom_data' => [],
        ];

        return $this->createFromArray($customer);
    }

    /**
     * Create from array
     *
     * @param  array  $customer
     *
     * @return array
     *
     * @throws PaddleException|JsonException
     */
    public function createFromArray(array $customer) : array
    {
        $url = '/customers';

        $response = $this->sendJsonRequest('POST', $url, [], $customer, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update customer
     *
     * @param string $id
     * @param string $key
     * @param string|bool|int|array $value
     *
     * @return array
     */
    public function update(string $id, string $key, string|bool|int|array $value) : array
    {
        $update = [
            $key => $value,
        ];

        $url = "/customers/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }
}
