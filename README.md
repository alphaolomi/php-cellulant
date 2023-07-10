# Cellulant for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/php-cellulant.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/php-cellulant)
[![Tests](https://img.shields.io/github/actions/workflow/status/alphaolomi/php-cellulant/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alphaolomi/php-cellulant/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/php-cellulant.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/php-cellulant)

Cellulant for PHP is a PHP client for the [Tingg API](#) a product of Cellulant Inc.

## Installation

You can install the package via composer:

```bash
composer require alphaolomi/php-cellulant
```

## Usage

```php
use Alphaolomi\CellulantService;

$cellulant = CellulantService::create('accessKey', 'ivKey', 'secretKey');
// OR
$celluant = new CellulantService('accessKey', 'ivKey', 'secretKey');


// Checkout Request
$checkoutRes = $cellulant->checkoutRequest([
    // ... Tingg Checkout Request Payload
]);

// Charge Request
$chargeRes = $cellulant->chargeRequest([
    // ... Tingg Charge Request Payload
]);

// Query Status
$queryRes = $cellulant->queryStatus([
    // ... Tingg Checkout Status Payload
]);

// Acknowledgement Request
$ackRes = $cellulant->acknowledgementRequest([
    // ... Tingg Acknowledgement Payload
]);

// Refund Request
$refundRes = $cellulant->refundRequest([
    // ... Tingg Refund Payload
]);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alpha Olomi](https://github.com/alphaolomi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
