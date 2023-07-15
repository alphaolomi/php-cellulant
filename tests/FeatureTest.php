<?php

use Alphaolomi\Cellulant\CellulantService;

// load envs from ../.env
beforeAll(function () {
    $envFile = dirname(__DIR__, 1) . '/.env';
    if (file_exists($envFile)) {
        $envVars = parse_ini_file($envFile);

        foreach ($envVars as $key => $value) {
            putenv("$key=$value");
        }
    }
});

it('can authenticate', function () {
    $cellulant = new CellulantService([
        'clientId' => getenv('TNG_CLIENT_ID'),
        'clientSecret' => getenv('TNG_CLIENT_SECRET'),
        'apiKey' => getenv('TNG_API_KEY'),
        'serviceCode' => getenv('TNG_SERVICE_CODE'),
        'callbackUrl' => getenv('TNG_CALLBACK_URL'),
        'env' => 'sandbox', // or 'production'
    ]);

    $authResponse = $cellulant->authenticate();

    expect($authResponse)->toHaveKeys(['access_token', 'expires_in', 'token_type', 'refresh_token']);
})->skip(fn () => getenv('TNG_CLIENT_ID') === false, 'TNG_CLIENT_ID is not set');


it('can create checkout', function () {
    $cellulant = new CellulantService([
        'clientId' => getenv('TNG_CLIENT_ID'),
        'clientSecret' => getenv('TNG_CLIENT_SECRET'),
        'apiKey' => getenv('TNG_API_KEY'),
        'serviceCode' => getenv('TNG_SERVICE_CODE'),
        'callbackUrl' => getenv('TNG_CALLBACK_URL'),
        'env' => 'sandbox', // or 'production'
    ]);

    $cellulant->authenticate();

    $data = [
        'msisdn' => '0747991498',
        'account_number' => 'test_123_456',
        'callback_url' => getenv('TNG_CALLBACK_URL'),
        'country_code' => 'TZA',
        'currency_code' => 'TZS',
        'customer_email' => 'alphaolomi@gmail.com',
        'customer_first_name' => 'Alpha',
        'customer_last_name' => 'Olomi',
        // 2023-07-02 20:00:00
        'due_date' => date('Y-m-d H:i:s', strtotime('+2 days')),

        'fail_redirect_url' => getenv('TNG_CALLBACK_URL'),
        'invoice_number' => 'INV-123-456',
        'merchant_transaction_id' => 'test_php_cellulant_' . time(),
        'request_amount' => 1000,
        'request_description' => 'Test PHP Cellulant',
        'service_code' => getenv('TNG_SERVICE_CODE'),
        'success_redirect_url' => getenv('TNG_CALLBACK_URL'),
    ];

    $res = $cellulant->checkoutRequest($data);

    // expect($res)->toBeArray();
    // expect($res['results']['merchant_transaction_id'])->dd();

    $data2 = [
        "charge_msisdn" => $data['msisdn'],
        "charge_amount" => $data['request_amount'],
        "country_code" => $data['country_code'],
        "currency_code" => $data['currency_code'],
        "merchant_transaction_id" => $res['results']['merchant_transaction_id'],
        "service_code" => $data['service_code'],
        "payment_mode_code" => "STK_PUSH",
        "payment_option_code" => "VODACOMTZ",
    ];

    $response = $cellulant->chargeRequest($data2);

    expect($response)->toBeArray();

    $resStatus = $cellulant->queryCheckoutStatus($data2['merchant_transaction_id']);

    expect($resStatus)->toBeArray();

}) ->skip(fn () => getenv('TNG_CLIENT_ID') === false, 'TNG_CLIENT_ID is not set');
