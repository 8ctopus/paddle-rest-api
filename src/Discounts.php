<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use DateTime;
use JsonException;
use Oct8pus\Paddle\Auth;

class Discounts extends RestBase
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
     * List discounts
     *
     * @return array<mixed>
     */
    public function list() : array
    {
        $url = '/discounts';

        $params = [
            //'id' => [string],
            //'after' => string,
            //'per_page' => integer,
            //'include' => [string discount-group-id],
            //'code' => [string],
            //'order_by' => 'id[ASC]',
            //'status' => [active,archived],
            //'mode' => 'standard,custom',
            //'discount_group_id' => '',
        ];

        if (count($params)) {
            $url .= '?' . http_build_query($params);
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
     * @param  ?string   $code
     * @param  string    $description
     * @param  string    $type
     * @param  string    $amount - Lowest denomination in the currency, eg. 0.01 EUR = 1 and 1 JPY = 1
     * @param  ?string   $currencyCode
     * @param  bool      $enabledForCheckout
     * @param  ?string   $discountGroup
     * @param  ?array    $restrictTo
     * @param  ?int      $usageLimit
     * @param  ?DateTime $expiresAt
     * @param  bool      $recurr
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create(?string $code, string $description, string $type, string $amount, ?string $currencyCode, bool $enabledForCheckout, ?string $discountGroup, ?array $restrictTo, ?int $usageLimit, ?DateTime $expiresAt, bool $recurr) : array
    {
        $discount = [
            'code' => $code, // if not provided and enabled for checkout, then paddle generates one
            'description' => $description,
            'type' => $type, // flat, flat_per_seat, percentage
            'amount' => $amount,
            'currency_code' => $currencyCode, // required for flat and flat per seat discounts
            'mode' => 'standard', // standard discounts are shown in the dashboard, custom are not
            'enabled_for_checkout' => $enabledForCheckout,

            'discount_group_id' => $discountGroup,
            'restrict_to' => $restrictTo, // product or prices
            'usage_limit' => $usageLimit, // integer or null for unlimited
            'expires_at' => $expiresAt,

            'recur' => $recurr,
            'custom_data' => null,
        ];

        $url = '/discounts';

        $response = $this->sendJsonRequest('POST', $url, [], $discount, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update discount
     *
     * @param string $id
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function update(string $id, string $key, string $value) : array
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
