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


// WIP - Work in progress
// TODO: Naively test each option separately

// dataset('invalid_options',[
//     "No Client Id" => [
//         // 'clientId' => 'clientId',
//         'clientSecret' => 'clientSecret',
//         'apiKey' => 'your api key',
//         'serviceCode' => 'your service code',
//         'callbackUrl' => 'your callback url',
//         'env' => 'sandbox', // or 'production'
//     ],
    // [
    //     'clientId' => 'clientId',
    //     // 'clientSecret' => 'clientSecret',
    //     'apiKey' => 'your api key',
    //     'serviceCode' => 'your service code',
    //     'callbackUrl' => 'your callback url',
    //     'env' => 'sandbox', // or 'production'
    // ],  [
    //     'clientId' => 'clientId',
    //     'clientSecret' => 'clientSecret',
    //     // 'apiKey' => 'your api key',
    //     'serviceCode' => 'your service code',
    //     'callbackUrl' => 'your callback url',
    //     'env' => 'sandbox', // or 'production'
    // ],  [
    //     'clientId' => 'clientId',
    //     'clientSecret' => 'clientSecret',
    //     'apiKey' => 'your api key',
    //     // 'serviceCode' => 'your service code',
    //     'callbackUrl' => 'your callback url',
    //     'env' => 'sandbox', // or 'production'
    // ],
    // [
    //     'clientId' => 'clientId',
    //     'clientSecret' => 'clientSecret',
    //     'apiKey' => 'your api key',
    //     'serviceCode' => 'your service code',
    //     // 'callbackUrl' => 'your callback url',
    //     'env' => 'sandbox', // or 'production'
    // ]
// ]);

// it('will throw an exception ', function (array $options) {
//     // expect(fn () => new CellulantService($options))->dd();
//     echo "options: ";
//     // $c = new CellulantService($options);
//     // expect($c)->toBeInstanceOf(CellulantService::class);
//     expect(true)->toBe(true);

//     // ->toThrow(\InvalidArgumentException::class);
// })->with('invalid_options');
