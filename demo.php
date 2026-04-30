<?php

declare(strict_types=1);

use Clue\Commander\Router;
use Nimbly\Capsule\Factory\RequestFactory;
use Nimbly\Capsule\Factory\StreamFactory;
use Nimbly\Shuttle\Shuttle;
use NunoMaduro\Collision\Provider;
use Oct8pus\Paddle\HttpHandler;
use Oct8pus\Paddle\Auth;
use Oct8pus\Paddle\DiscountGroups;
use Oct8pus\Paddle\Discounts;
use Oct8pus\Paddle\Prices;
use Oct8pus\Paddle\Products;

$vendor = __DIR__ . '/vendor/autoload.php';

if (!file_exists($vendor)) {
    echo <<<'TXT'
    Please run composer install

    TXT;
    return;
}

require_once $vendor;

(new Provider())
    ->register();

$file = __DIR__ . '/.env.php';

if (!file_exists($file)) {
    echo <<<'TXT'
    Please create env.php based on env.php.example

    TXT;
    return;
}

$env = require_once $file;

date_default_timezone_set('UTC');

$handler = new HttpHandler(
    // PSR-18 http client
    new Shuttle(),
    // PSR-17 request factory
    new RequestFactory(),
    // PSR-7 stream
    new StreamFactory()
);

$sandbox = $env['sandbox'];

$auth = new Auth($sandbox, $handler, $env['secret']);

$router = new Router();

$router->add('products list', static function () use ($sandbox, $handler, $auth) : void {
    $products = new Products($sandbox, $handler, $auth);
    dump($products->list());
});

$router->add('products get <product-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $products = new Products($sandbox, $handler, $auth);
    dump($products->get($args['product-id']));
});

$router->add('products create <name> <tax-category> <description> <image-url>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $products = new Products($sandbox, $handler, $auth);
    dump($products->create([
        'name' => $args['name'],
        'tax_category' => $args['tax-category'],
        'description' => $args['description'],
        'type' => 'standard',
        'image_url' => $args['image-url'] !== 'null' ? $args['image-url'] : null,
    ]));
});

$router->add('products update <product-id> <key> <value>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $products = new Products($sandbox, $handler, $auth);
    dump($products->update($args['product-id'], $args['key'], $args['value']));
});

$router->add('products archive <product-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $products = new Products($sandbox, $handler, $auth);
    $products->archive($args['product-id']);
});

$router->add('discount groups list', static function () use ($sandbox, $handler, $auth) : void {
    $discountGroups = new DiscountGroups($sandbox, $handler, $auth);
    dump($discountGroups->list());
});

$router->add('discount groups get <group-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discountGroups = new DiscountGroups($sandbox, $handler, $auth);
    dump($discountGroups->get($args['group-id']));
});

$router->add('discount groups create <name>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discountGroups = new DiscountGroups($sandbox, $handler, $auth);
    dump($discountGroups->create($args['name']));
});

$router->add('discount groups update <group-id> <key> <value>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discountGroups = new DiscountGroups($sandbox, $handler, $auth);
    dump($discountGroups->update($args['group-id'], $args['key'], $args['value']));
});

$router->add('discount groups archive <group-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discountGroups = new DiscountGroups($sandbox, $handler, $auth);
    $discountGroups->archive($args['group-id']);
});

$router->add('discounts list', static function () use ($sandbox, $handler, $auth) : void {
    $discounts = new Discounts($sandbox, $handler, $auth);
    dump($discounts->list());
});

$router->add('discounts get <discount-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discounts = new Discounts($sandbox, $handler, $auth);
    dump($discounts->get($args['discount-id']));
});

$router->add('discounts create <code> <description> <type> <amount> <currency-code> <mode> <enabled-for-checkout> <discount-group> <restrict> <usage-limit> <expires> <recurr>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discounts = new Discounts($sandbox, $handler, $auth);
    dump($discounts->create($args['code'], $args['description'], $args['type'], $args['amount'], $args['currency-code'], $args['mode'], $args['enabled-for-checkout'], $args['discount-group'], $args['restrict'], $args['usage-limit'], $args['expires'], $args['recurr']));
});

$router->add('discounts update <discount-id> <key> <value>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discounts = new Discounts($sandbox, $handler, $auth);
    dump($discounts->update($args['discount-id'], $args['key'], $args['value']));
});

$router->add('discounts archive <discount-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $discounts = new Discounts($sandbox, $handler, $auth);
    $discounts->archive($args['discount-id']);
});

$router->add('prices list', static function () use ($sandbox, $handler, $auth) : void {
    $prices = new Prices($sandbox, $handler, $auth);
    dump($prices->list());
});

$router->add('prices get <price-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $prices = new Prices($sandbox, $handler, $auth);
    dump($prices->get($args['price-id']));
});

$router->add('prices create <product-id> <description> <amount> <currency-code> <type> <name>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $prices = new Prices($sandbox, $handler, $auth);
    dump($prices->create($args['product-id'], $args['description'], $args['amount'], $args['currency-code'], $args['type'], $args['name']));
});

$router->add('prices update <price-id> <key> <value>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $prices = new Prices($sandbox, $handler, $auth);
    dump($prices->update($args['price-id'], $args['key'], $args['value']));
});

$router->add('prices archive <price-id>', static function (array $args) use ($sandbox, $handler, $auth) : void {
    $prices = new Prices($sandbox, $handler, $auth);
    $prices->archive($args['price-id']);
});

$router->add('help', static function () use ($router) : void {
    echo "commands:\n";

    foreach ($router->getRoutes() as $command) {
        echo "{$command}\n";
    }
});

$env = $sandbox ? 'SANDBOX' : 'PRODUCTION';
$color = $sandbox ? 32 : 31;
$output = "\033[01;{$color}m{$env}\033[0m";

echo <<<TXT
{$output}
Type "help" to view commands

TXT;

$stdin = fopen('php://stdin', 'r');

if ($stdin === false) {
    throw new Exception('fopen');
}

$input = $argv;

while (true) {
    echo "\n> ";
    $input = trim(fgets($stdin));

    if (in_array($input, ['', 'exit', 'quit', 'q'], true)) {
        break;
    }

    $input = splitArguments("dummy {$input}");

    $router->execArgv($input);
}

fclose($stdin);
exit(0);

/**
 * Dump variable
 *
 * @param mixed $variable
 *
 * @return void
 */
function dump(mixed $variable) : void
{
    $variable = json_encode($variable, JSON_PRETTY_PRINT) . "\n";

    echo $variable;

    if (true) {
        file_put_contents(__DIR__ . '/dump.json', $variable);
    }
}

function splitArguments(string $input) : array
{
    $result = [];

    $input = trim($input) . ' ';
    $length = strlen($input);

    $lastPosition = 0;
    $doubleQuote = false;

    for ($i = 0; $i < $length; ++$i) {
        $char = $input[$i];

        switch ($char) {
            case '"':
                if (!$doubleQuote) {
                    $doubleQuote = true;
                    break;
                }

                $result[] = substr($input, $lastPosition + 1, $i - $lastPosition - 1);
                ++$i;
                $lastPosition = $i + 1;
                $doubleQuote = false;
                break;

            case ' ':
                if ($doubleQuote) {
                    break;
                }

                $result[] = substr($input, $lastPosition, $i - $lastPosition);
                $lastPosition = $i + 1;
                break;
        }
    }

    return $result;
}
