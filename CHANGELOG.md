# Changelog

All notable changes to `php-cellulant` will be documented in this file.

## v1.2.0 - 2023-07-15

### 🎉 1.2.0

#### What's New

- Added Guzzle\Client object as the second constructor argument
- Added gets and setters for API key and access token.
- internal: stable ValidationUtility
- internal: add more tests

**Full Changelog**: https://github.com/alphaolomi/php-cellulant/compare/v1.1.0...v1.2.0

## v1.1.0 - 2023-07-15

### 🎉  1.1.0

#### Updates

- Bugs fixes
- Improve documentation
- Improve validation
- Add more tests

**Full Changelog**: https://github.com/alphaolomi/php-cellulant/compare/v1.0.0...v1.1.0

## v1.0.0 - 2023-07-14

### 🎉  v1.0.0

#### [Features](https://alphaolomi.github.io/php-cellulant/#features)

The package is centered around Custom Checkout APIs provided by Tingg. It provides the following features:

- Checkout Request
- Charge Request
- Combined Checkout and Charge Request
- Acknowledgement Request
- Query Status
- Refund Request
- OTP Request and Validation

#### [Installation](https://alphaolomi.github.io/php-cellulant/#installation)

##### Pre-requisites

- PHP >= 8.0
- PHP `cURL` extension
- Composer Package Manager
- Cellulant API Credentials, obtain them from [Tingg Dev Portal](https://dev-portal.tingg.africa/)

The preferable way to install is with [Composer](https://getcomposer.org/).

You can install/update the package via Composer:

```bash
composer require alphaolomi/php-cellulant



```
**Full Changelog**: https://github.com/alphaolomi/php-cellulant/compare/v0.0.0...v1.0.0

## [Unreleased]

- Nothing

## [0.1.0] - 2020-04-20

## What's Changed

- Initial release

### Added

- Cellulant Service Class for making requests to Cellulant API
