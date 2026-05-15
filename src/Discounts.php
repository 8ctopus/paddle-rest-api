<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use DateTime;
use JsonException;

class Discounts extends RestBase
{
    /**
     * List discounts
     *
     * @param array $conditions
     *
     * @return array<mixed>
     */
    public function list(array $conditions = []) : array
    {
        $url = '/discounts';

        /*
        $conditions = [
            'id' => [string],
            'after' => string,
            'per_page' => integer,
            'include' => [
                'discount_group',
            ],
            'code' => [string],
            'order_by' => 'id[ASC]',
            'status' => [active,archived],
            'mode' => 'standard,custom',
            'discount_group_id' => '',
        ];
        */

        if (count($conditions)) {
            $url .= '?' . http_build_query($conditions);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get discount
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/discounts/{$id}";

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create discount
     *
     * @param ?string   $code
     * @param string    $description
     * @param string    $type
     * @param string    $mode
     * @param string    $amount             - Lowest denomination in the currency, eg. 0.01 EUR = 1 and 1 JPY = 1
     * @param ?string   $currencyCode
     * @param bool      $enabledForCheckout
     * @param ?string   $discountGroup
     * @param ?array    $restrictTo
     * @param ?int      $usageLimit
     * @param ?DateTime $expiresAt
     * @param bool      $recurr
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create(?string $code, string $description, string $type, string $mode, string $amount, ?string $currencyCode, bool $enabledForCheckout, ?string $discountGroup, ?array $restrictTo, ?int $usageLimit, ?DateTime $expiresAt, bool $recurr) : array
    {
        $discount = [
            'code' => $code, // if not provided and enabled for checkout, then paddle generates one
            'description' => $description,
            'type' => $type, // flat, flat_per_seat, percentage
            'mode' => $mode, // standard discounts are shown in the dashboard, custom are not
            'amount' => $amount,
            'currency_code' => $currencyCode, // required for flat and flat per seat discounts
            'enabled_for_checkout' => $enabledForCheckout,

            'discount_group_id' => $discountGroup,
            'restrict_to' => $restrictTo, // product or prices
            'usage_limit' => $usageLimit, // integer or null for unlimited
            'expires_at' => $expiresAt,

            'recur' => $recurr,
            'custom_data' => null,
        ];

        return $this->createFromArray($discount);
    }

    /**
     * Create discount
     *
     * @param array    $discount
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function createFromArray(array $discount) : array
    {
        $url = '/discounts';

        $response = $this->sendJsonRequest('POST', $url, [], $discount, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update discount
     *
     * @param string $id
     * @param string $key
     * @param string|bool|int|array $value
     *
     * @return array
     */
    public function update(string $id, string $key, string|bool|int|array $value) : array
    {
        // possible keys: status, description, enabled_for_checkout, code, type, mode, amount, currency_code, recur,
        // maximum_recurring_intervals, usage_limit, restrict_to, expires_at, custom_data, discount_group_id

        $update = [
            $key => $value,
        ];

        $url = "/discounts/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Archive discount
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
