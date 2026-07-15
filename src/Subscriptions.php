<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use DateTime;
use DateTimeInterface;
use JsonException;

class Subscriptions extends RestBase
{
    /**
     * List subscriptions
     *
     * @param array $conditions
     *
     * @return array<mixed>
     */
    public function list(array $conditions = []) : array
    {
        $url = '/subscriptions';

        /*
        $conditions = [
        ];
        */

        if (count($conditions)) {
            $url .= '?' . http_build_query($conditions);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get subscription
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/subscriptions/{$id}";

        $params = [
        ];

        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create subscription
     *
     * @return array
     *
     * @throws PaddleException|JsonException
     */
    public function create() : array
    {
        throw new PaddleException('not implemented on API side');
    }

    /**
     * Update subscription
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

        $url = "/subscriptions/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Cancel subscription
     *
     * @param string $id
     * @param bool $immediately
     *
     * @return array
     */
    public function cancel(string $id, bool $immediately) : array
    {
        $url = "/subscriptions/{$id}/cancel";

        $params = [
            'effective_from' => $immediately ? 'immediately' : 'next_billing_period',
        ];

        $response = $this->sendJsonRequest('POST', $url, [], $params, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Pause subscription
     *
     * @param string $id
     * @param bool $immediately
     *
     * @return array
     */
    public function pause(string $id, bool $immediately) : array
    {
        $url = "/subscriptions/{$id}/pause";

        $params = [
            'effective_from' => $immediately ? 'immediately' : 'next_billing_period',
            //'resume_at' => '2024-09-01T16:30:00Z',
            //'on_resume' => 'start_new_billing_period', 'continue_existing_billing_period',
        ];

        $response = $this->sendJsonRequest('POST', $url, [], $params, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Resume subscription
     *
     * @param string $id
     * @param true|DateTime $effectiveFrom
     *
     * @return array
     */
    public function resume(string $id, true|DateTime $effectiveFrom) : array
    {
        $url = "/subscriptions/{$id}/resume";

        $params = [
            'effective_from' => ($effectiveFrom === true) ? 'immediately' : $effectiveFrom->format(DateTimeInterface::RFC3339),
            //'on_resume' => 'start_new_billing_period', 'continue_existing_billing_period',
        ];

        $response = $this->sendJsonRequest('POST', $url, [], $params, 200);

        return $this->decodeResponse($response);
    }
}
