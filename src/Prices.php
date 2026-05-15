<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;

class Prices extends RestBase
{
    /**
     * List prices
     *
     * @param array $conditions
     *
     * @return array<mixed>
     */
    public function list(array $conditions = []) : array
    {
        $url = '/prices';

        /*
        $conditions = [
            'id' => [string],
            'after' => string,
            'per_page' => integer,
            'include' => [
                'product',
            ],
            'order_by' => 'id[ASC]',
            'product_id' => [],
            'status' => [active,archived],
            'recurring' => bool,
            'billing_cycle.interval' => 'day,week,month,year',
            'billing_cycle.frequency' => integer,
            'type' => 'standard,custom',
        ];
        */

        if (count($conditions)) {
            $url .= '?' . http_build_query($conditions);
        }

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
     * @param string  $productId
     * @param ?string $name         - Displayed to your customers at the checkout and on invoices, to help them understand what they are paying for
     * @param ?string $description  - Add a short label to identify this price. This won't be shown to your customers.
     * @param string  $amount       - Lowest denomination in the currency, eg. 0.01 EUR = 1 and 1 JPY = 1
     * @param string  $currencyCode
     *
     * @return array
     *
     * @throws PaddleException|JsonException
     */
    public function create(string $productId, ?string $name, ?string $description, string $amount, string $currencyCode) : array
    {
        $price = [
            'product_id' => $productId, // required
            'name' => $name, // ?string
            'description' => $description, // ?string
            'unit_price' => [
                'amount' => $amount, // string
                'currency_code' => $currencyCode, // string
            ],
            'type' => 'standard', // standard,custom
            'tax_mode' => 'account_setting', // account_setting,external,internal,location
            'quantity' => [
                'minimum' => 1,
                'maximum' => 1,
            ],
            /*
            'billing_cycle' => [
                'frequency' => integer,
                'interval' => 'day,week,month,year',
            ],
            'trial_period' => [
                'frequency' => integer,
                'interval' => 'day,week,month,year',
            ],
            'unit_price_overrides' => ,
            'custom_data' => null,
            */
        ];

        return $this->createFromArray($price);
    }

    /**
     * Create from array
     *
     * @param  array  $price
     *
     * @return array
     *
     * @throws PaddleException|JsonException
     */
    public function createFromArray(array $price) : array
    {
        $url = '/prices';

        $response = $this->sendJsonRequest('POST', $url, [], $price, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update price
     *
     * @param string $id
     * @param string $key
     * @param string|bool|int|array $value
     *
     * @return array
     */
    public function update(string $id, string $key, string|bool|int|array $value) : array
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
