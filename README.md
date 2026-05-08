# Paddle REST API

An unoffical php implementation of the [Paddle REST API](https://developer.paddle.com/api-reference/overview) using `PSR-7`, `PSR-17` and `PSR-18` as I didn't like the official API's implementation.

The package is a work in progress and contributions are welcome. For now, it covers `Products`, `Prices`, `Discounts`, `Discount Groups`, `Transactions` (partial), `Adjustments` (refunds) and `Customers`.

## install package

    composer require 8ctopus/paddle-rest-api

## before you get started

Copy `.env.example` to `.env` and fill in your Paddle REST API key. If you don't have it yet, follow the guide:

    https://developer.paddle.com/api-reference/about/authentication#get-api-key

## demo

Here's a code snippet that shows the general architecture. To see all possibilites run `php demo.php`.

```php
use Nimbly\Capsule\Factory\RequestFactory;
use Nimbly\Capsule\Factory\StreamFactory;
use Nimbly\Shuttle\Shuttle;
use Oct8pus\Paddle\HttpHandler;
use Oct8pus\Paddle\Auth;
use Oct8pus\Paddle\Products;

$handler = new HttpHandler(
    // PSR-18 http client
    new Shuttle(),
    // PSR-17 request factory
    new RequestFactory(),
    // PSR-7 stream
    new StreamFactory()
);

$sandbox = true;

$auth = new Auth($sandbox, $handler, $env['secret']);

$products = new Products($sandbox, $handler, $auth);

var_dump($products->list());
```

## todo

- implement tests

## issues with the current minimalist architecture

- hard to use the demo for more complex things
- filter lists not implemented
- create complex items as arrays are not presently supported
