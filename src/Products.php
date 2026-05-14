<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use JsonException;

class Products extends RestBase
{
    /**
     * List products
     *
     * @param $conditions
     *
     * @return array<mixed>
     */
    public function list(array $conditions = []) : array
    {
        $url = '/products';

        /*
        $conditions = [
            'status' => 'active,archived',
        ];
        */

        if (count($conditions)) {
            $url .= '?' . http_build_query($conditions);
        }

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Get product
     *
     * @param string $id
     *
     * @return array<mixed>
     */
    public function get(string $id) : array
    {
        $url = "/products/{$id}";

        $response = $this->sendRequest('GET', $url, [], null, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Create product
     *
     * @param  string $name
     * @param  ?string $description
     * @param  string $type
     * @param  string $taxCategory
     * @param  ?string $imageUrl
     *
     * @return array
     *
     * @throws JsonException|PaddleException
     */
    public function create(string $name, ?string $description, string $type, string $taxCategory, ?string $imageUrl) : array
    {
        $product = [
            'name' => $name, // required
            'description' => $description,
            'type' => $type, // standard, custom
            'tax_category' => $taxCategory, // required digital-goods, ebooks, implementation-services, professional-services, saas, software-programming-services, standard, training-services, website-hosting
            'image_url' => $imageUrl,
            //'custom_data' => [],
        ];

        $url = '/products';

        $response = $this->sendJsonRequest('POST', $url, [], $product, 201);

        return $this->decodeResponse($response);
    }

    /**
     * Update product
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

        $url = "/products/{$id}";

        $response = $this->sendJsonRequest('PATCH', $url, [], $update, 200);

        return $this->decodeResponse($response);
    }

    /**
     * Archive product
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
