<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;
use Oct8pus\Paddle\Auth;

class Prices extends RestBase
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
     * List prices
     *
     * @return array<mixed>
     */
    public function list() : array
    {
        $url = '/prices';

        $params = [
            //'id' => [string],
            //'after' => string,
            //'per_page' => integer,
            //'include' => [string product-id],
            'order_by' => 'ASC',
            //'product_id' => [string product-id],
            //'status' => [active,archived],
            //'recurring' => bool,
            //'billing_cycle.interval' => 'day,week,month,year',
            //'billing_cycle.frequency' => integer,
            //'type' => 'standard,custom',
        ];

        $url .= '?' . http_build_query($params);

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get price
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/prices/{$id}";

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create price
     *
     * @param  string $productId
     * @param  string $description
     * @param  float  $amount
     * @param  string $currencyCode
     * @param  string $type
     * @param  ?string $name
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create(string $productId, string $description, float $amount, string $currencyCode, string $type, ?string $name) : array
    {
        $price = [
            'product_id' => $productId,
            'description' => $description,
            'unit_price' => [
                'amount' => $amount,
                'currency_code' => $currencyCode,
            ],
            'type' => $type, // standard,custom
            'name' => $name,
            /*
            'billing_cycle' => [
                'frequency' => integer,
                'interval' => 'day,week,month,year',
            ],
            'trial_period' => [
                'frequency' => integer,
                'interval' => 'day,week,month,year',
            ],
            'tax_mode' => 'account_setting,external,internal,location',
            'unit_price_overrides' => ,
            'quantity' => object,
            'custom_data' => null,
            */
        ];

        $url = '/prices';

        $response = $this->sendJsonRequest('POST', $url, [], $price, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update price
     *
     * @param string $id
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function update(string $id, string $key, string $value) : array
    {
        // possible keys: description, type, name, billing_cycle, trial_period, tax_mode, unit_price, unit_price_overrides,
        // quantity, status, custom_data

        $update = [
            $key => $value,
        ];

        $url = "/prices/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Archive price
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
