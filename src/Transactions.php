<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;

class Transactions extends RestBase
{
    /**
     * List transactions
     *
     * @return array<mixed>
     */
    public function list() : array
    {
        $url = '/transactions';

        $params = [
            //'include' => [string],
            //'id' => [string transaction-id],
            //'after' => string,
            //'billed_at' => string,
            //'collection_mode' => automatic,manual
            //'created_at' => string,
            //'customer_id' => [string],
            //'invoice_number' => [string],
            //'origin' => [string],
            //'order_by' => 'id[ASC]',
            //'status' => [draft,ready,billed,paid,completed,canceled,past_due],
            //'subscription_id' => string,
            //'per_page' => integer,
            //'updated_at' => string,
        ];

        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get transaction
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/transactions/{$id}";

        $params = [
            'include' => 'adjustments', // address,adjustments,adjustments_totals,available_payment_methods,business,customer,discount
        ];

        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create transaction
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create() : array
    {
        throw new PaddleException('not implemented');
        $transaction = [];

        $url = '/transactions';

        $response = $this->sendJsonRequest('POST', $url, [], $transaction, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update transaction
     *
     * @param string $id
     * @param string $key
     * @param string|bool|int|array $value
     *
     * @return array
     */
    public function update(string $id, string $key, string|bool|int|array $value) : array
    {
        // possible keys: status, customer_id, address_id, business_id, custom_data, currency_code,
        // origin, collection_mode, discount_id, discount, billing_details, billing_period, items, checkout

        $update = [
            $key => $value,
        ];

        $url = "/transactions/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Preview transaction
     *
     * @param string $id
     *
     * @return array
     */
    public function preview(string $id) : array
    {
        throw new PaddleException('not implemented');
        $transaction = [];

        $url = "/transactions/{$id}/preview";

        $response = $this->sendJsonRequest('POST', $url, [], $transaction, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Get pdf
     *
     * @param string $id
     *
     * @return array
     */
    public function pdf(string $id) : array
    {
        $url = "/transactions/{$id}/invoice";

        /*
        $params = [
            'disposition' => 'attachment,inline'
        ];
        */

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Revise transaction
     *
     * @param string $id
     *
     * @return array
     */
    public function revise(string $id) : array
    {
        throw new PaddleException('not implemented');
        $transaction = [];

        $url = "/transactions/{$id}/preview";

        $response = $this->sendJsonRequest('POST', $url, [], $transaction, 201);

        return $this->decodeResponse($response);
    }
}
