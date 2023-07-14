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
