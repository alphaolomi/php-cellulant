# Cellulant for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/php-cellulant.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/php-cellulant)
[![Tests](https://img.shields.io/github/actions/workflow/status/alphaolomi/php-cellulant/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alphaolomi/php-cellulant/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/php-cellulant.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/php-cellulant)

Cellulant for PHP is a PHP client for the [Tingg API](#) a product of Cellulant Inc.

## Features

The package is centered around Custom Checkout APIs provided by Tingg. It provides the following features:

-   Checkout Request
-   Charge Request
-   Combined Checkout and Charge Request
-   Acknowledgement Request
-   Query Status
-   Refund Request
-   OTP Request and Validation

## Installation

## Pre-requisites

-  PHP >= 8.0
-  PHP `cURL` extension
-  Composer Package Manager
-  Cellulant API Credentials, obtain them from [Tingg Dev Portal](https://dev-portal.tingg.africa/)

Preferable way to install is with [Composer](https://getcomposer.org/).

You can install the package via Composer:

```bash
composer require alphaolomi/php-cellulant
```

## Usage

```php
use Alphaolomi\CellulantService;

$celluant = new CellulantService([
    'client_id' => 'clientId',
    'client_secret' => 'clientSecret',
    'apiKey' => 'your api key',
    'serviceCode' => 'your service code',
    'env' => 'sandbox', // or 'production'    
]);


// Authentication
// Get the access token
$authRes = $cellulant->authenticate();
// $authRes is an array with the following keys
// [
//  "token_type" => "bearer",
//  "expires_in" => 3600,
//  "access_token" => "WU3My1AHOcKsnxj3n",
//  "refresh_token" =>   "GSWtHRnJrMHBzdEFPbjhNS0FjODIwMTU1NVlTb3c9PQ=="
// ]

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

## Notes

-   Calling `authenticate()` is not automatic, you have to call it manually before making any request.

-   Calling `authenticate()` will keep the accessToken in memory for subsequent requests.

-   Calling `authenticate()` only supports the `client_credentials` grant type. And will return an array with the following keys: `token_type`, `expires_in`, `access_token`, `refresh_token`.

-   Error handling is not yet implemented, so you have to handle errors manually.

-   Methods accept an array of parameters, which are then converted to JSON before making the request. Refer to Tingg API documentation for the required parameters.

-   Methods array parameters are _NOT_ validated, so you have to make sure you pass the correct parameters.

-   Method names are the same as the Tingg API endpoints.

-   The package uses [Guzzle](https://docs.guzzlephp.org/) to make HTTP requests. Version ^7.0 is used.

## Testing

Tests are written with [Pest](https://pestphp.com/). To run the tests:

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

-   [Alpha Olomi](https://github.com/alphaolomi)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
