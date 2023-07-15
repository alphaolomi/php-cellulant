# Cellulant for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/php-cellulant.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/php-cellulant)
[![Tests](https://img.shields.io/github/actions/workflow/status/alphaolomi/php-cellulant/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alphaolomi/php-cellulant/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/php-cellulant.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/php-cellulant)

Cellulant for PHP is a PHP client for the [Tingg API](https://dev-portal.tingg.africa/) a product of Cellulant.

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

-   PHP >= 8.0
-   PHP `cURL` extension
-   Composer Package Manager
-   Cellulant API Credentials, obtain them from [Tingg Dev Portal](https://dev-portal.tingg.africa/)

Preferable way to install is with [Composer](https://getcomposer.org/).

You can install the package via Composer:

```bash
composer require alphaolomi/php-cellulant
```

## Usage

```php
use Alphaolomi\CellulantService;

$cellulant = new CellulantService([
    'clientId' => 'your clientId',
    'clientSecret' => 'your clientSecret',
    'apiKey' => 'your api key',
    'serviceCode' => 'your service code',
    'callbackUrl' => 'your callback url',
    'env' => 'sandbox', // or 'production' // default is sandbox
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

Refer to Features Test Cases for more usage examples.

Refer to Tingg API documentation for the required parameters reference for each method.

## Notes

-   Calling `authenticate()` is not automatic, you have to call it manually before making any request.

-   Calling `authenticate()` will keep the accessToken in memory for subsequent requests.

-   Calling `authenticate()` only supports the `client_credentials` grant type. And will return an array with the following keys: `token_type`, `expires_in`, `access_token`, `refresh_token`.

-   Error handling is not yet implemented, so you have to handle errors manually.

-   Methods accept an array of parameters, which are then converted to JSON before making the request. Refer to Tingg API documentation for the required parameters.

-   Methods array parameters are _NOT_ validated, so you have to make sure you pass the correct parameters. With exception of `construct()` method which validates the required parameters.

-   Method names are the same as the Tingg API endpoints.

-   The package uses [Guzzle](https://docs.guzzlephp.org/) to make HTTP requests. Version ^7.0 is used.

-   Function Return Types are not implemented yet, so you have to manually cast the return values to the expected type.

## Testing

Tests are written with [Pest](https://pestphp.com/). To run the tests:

```bash
composer test
```

## Support the development

Do you like this project? Support it by [Star this repository ⭐️](https://github.com/alphaolomi/php-cellulant) and follow me on [Twitter](https://twitter.com/alphaolomi) for more updates 👍.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Versioning

This project follows [RomVer](https://github.com/romversioning/romver).

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Alpha Olomi via alphaolomi@gmail.com. All security vulnerabilities will be promptly addressed.

## Credits

-   [Alpha Olomi](https://github.com/alphaolomi)
-   [All Contributors](../../contributors)

## Reaching Me

If you are having issues with this package, feel free to contact me on [Twitter](https://twitter.com/alphaolomi).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
