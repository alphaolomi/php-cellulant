<?php

use Alphaolomi\Cellulant\CellulantService;

it('can instantiate the cellulant class', function () {
    $cellulant = new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]);
    expect($cellulant)->toBeInstanceOf(CellulantService::class);
});

// use custom client
it('can instantiate the cellulant class with custom client', function () {
    $cellulant = new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ], new \GuzzleHttp\Client([
        'base_uri' => CellulantService::SANDBOX_HOST,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'apiKey' => 'your api key',
        ],
        'timeout' => 60,
        'debug' => false,
        'http_errors' => false,
    ]));
    expect($cellulant)->toBeInstanceOf(CellulantService::class);
});

// it can get the token
it('can get the api key', function () {
    $cellulant = new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]);
    expect($cellulant->getApiKey())->toBe('your api key');
});

// it can set the api key
it('can set the api key', function () {
    $cellulant = new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]);
    $cellulant->setApiKey('new api key');
    expect($cellulant->getApiKey())->toBe('new api key');
});

// it can set access token
it('can set access token', function () {
    $cellulant = new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]);
    $cellulant->setAccessToken('new access token');
    expect($cellulant->getAccessToken())->toBe('new access token');
});


it('throws an exception when clientId is not passed', function () {
    expect(fn () => new CellulantService([
        // 'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]))->toThrow(\InvalidArgumentException::class);
});


it('throws an exception when clientSecret is not passed', function () {
    expect(fn () => new CellulantService([
        'clientId' => 'clientId',
        // 'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]))->toThrow(\InvalidArgumentException::class);
});


it('throws an exception when apiKey is not passed', function () {
    expect(fn () => new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        // 'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]))->toThrow(\InvalidArgumentException::class);
});



it('throws an exception when serviceCode is not passed', function () {
    expect(fn () => new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        // 'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]))->toThrow(\InvalidArgumentException::class);
});



it('throws an exception when callbackUrl is not passed', function () {
    expect(fn () => new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        // 'callbackUrl' => 'your callback url',
        'env' => 'sandbox', // or 'production'
    ]))->toThrow(\InvalidArgumentException::class);
});



it('not throws an exception when env is not passed', function () {
    expect(new CellulantService([
        'clientId' => 'clientId',
        'clientSecret' => 'clientSecret',
        'apiKey' => 'your api key',
        'serviceCode' => 'your service code',
        'callbackUrl' => 'your callback url',
        // 'env' => 'sandbox', // or 'production'
    ]))->toBeInstanceOf(CellulantService::class);
});
