# Paddle REST API

A php implementation of the Paddle REST API using `PSR-7`, `PSR-17` and `PSR-18` as I didn't like the official API's excessive use of dependencies.

The package is a work in progress and contributions are welcome. For now, it covers `Products`, `Prices`, `Discounts` and `Discount Groups`.

## install package

    composer require 8ctopus/paddle-rest-api

## before you get started

Copy `.env.example` to `.env` and fill in your Paddle REST API key. If you don't have it yet, follow the guide:

    https://developer.paddle.com/api-reference/about/authentication#get-api-key

## demo

Here's a code sample that shows the general architecture. To see all possibilites run `php demo.php`.

```php
TO DO
```

## issues with the current minimalist architecture

- hard to use the demo for more complex things
    - list items with filters
    - create complex items as arrays are not presently supported
    - it's not possible to update a key which is not a string
