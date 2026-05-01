<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;

class Adjustments extends RestBase
{
    /**
     * List adjustments
     *
     * @return array<mixed>
     */
    public function list() : array
    {
        $url = '/adjustments';

        $params = [
            //'id' => [string],
            //'after' => string,
            //'action' => [string],
            //'customer_id' => [customer-id],
            //'order_by' => 'id[ASC]',
            //'per_page' => integer,
            //'status' => [pending_approval,approved,rejected,reversed],
            //'subscription_id' => [subscription-id],
            //'transaction_id' => [transaction-id],
        ];

        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get adjustment
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/adjustments/{$id}";

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create adjustment
     *
     * @param  string   $transactionId
     * @param  string   $action
     * @param  string   $reason
     * @param  string   $type
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create(string $action, string $reason, string $transactionId, string $type) : array
    {
        $adjustment = [
            'transaction_id' => $transactionId,
            'action' => $action, // refund,credit
            'reason' => $reason,
            'type' => $type, // full,partial
            //'tax_mode' => // internal,external
            //'items' => [], // List of transaction items to adjust. Required if type is not populated or set to partial.
        ];

        $url = '/adjustments';

        $response = $this->sendJsonRequest('POST', $url, [], $adjustment, 201);

        return $this->decodeResponse($response);
    }
}
