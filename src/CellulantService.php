<?php

namespace Alphaolomi\Cellulant;

use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * Class CellulantService
 * @author Alpha Olomi <alphaolomi@gmail.com>
 * @version 0.1.0
 * @see https://dev-portal.tingg.africa/checkout-custom-apis
 *
 * @package Alphaolomi\Cellulant
 */
class CellulantService
{
    public const SANDBOX_HOST = "https://api-dev.tingg.africa";
    public const PROD_HOST = "https://api.tingg.africa";

    private $baseUrl;
    private $options;
    private $accessToken;
    private $client;

    public function __construct($options = [])
    {

        if (! isset($options['clientId'])) {
            throw new InvalidArgumentException('clientId is required');
        }

        if (! isset($options['clientSecret'])) {
            throw new InvalidArgumentException('clientSecret is required');
        }

        if (! isset($options['apiKey'])) {
            throw new InvalidArgumentException('apiKey is required');
        }

        if (! isset($options['serviceCode'])) {
            throw new InvalidArgumentException('serviceCode is required');
        }

        if (! isset($options['callbackUrl'])) {
            throw new InvalidArgumentException('callbackUrl is required');
        }

        if (! isset($options['env'])) {
            $options['env'] = 'sandbox';
        }


        $this->baseUrl = $options['env'] === 'sandbox' ? self::SANDBOX_HOST : self::PROD_HOST;

        $this->options = $options;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apiKey' => $this->options['apiKey'],
            ],
            'http_errors' => false,
        ]);
    }

    /**
     * @see https://dev-portal.tingg.africa/checkout-authentication
     */
    public function authenticate()
    {
        // Retrieve an authentication token
        // Make a request to the authentication API
        // Store the authentication token for future requests
        // /v1/oauth/token/request

        $response = $this->client->post('/v1/oauth/token/request', [
            'json' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->options['clientId'],
                'client_secret' => $this->options['clientSecret'],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        $this->accessToken = $data['access_token'];

        return $data;
    }

    /**
     * Initiates a checkout request with details
     * required to collect the payment.
     *
     * @see https://dev-portal.tingg.africa/checkout

     */
    public function checkoutRequest($data = [])
    {
        // Initiate a checkout request
        // Make a request to the checkout API

        // /v3/checkout-api/checkout/request

        $response = $this->client->post('/v3/checkout-api/checkout/request', [
            'json' => [
                'msisdn' => $data['msisdn'],
                'account_number' => $data['account_number'],
                'callback_url' => $data['callback_url'],
                'country_code' => $data['country_code'],
                'currency_code' => $data['currency_code'],
                'customer_email' => $data['customer_email'],
                'customer_first_name' => $data['customer_first_name'],
                'customer_last_name' => $data['customer_last_name'],
                'due_date' => $data['due_date'],
                'fail_redirect_url' => $data['fail_redirect_url'],
                'invoice_number' => $data['invoice_number'],
                'merchant_transaction_id' => $data['merchant_transaction_id'],
                'request_amount' => $data['request_amount'],
                'request_description' => $data['request_description'],
                'service_code' => $data['service_code'],
                'success_redirect_url' => $data['success_redirect_url'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    /**
     * Post a charge request
     * i.e. request to debit amount from customer for
     * the checkout request that was posted earlier
     * in the request/initiate function.
     *
     * @see https://dev-portal.tingg.africa/charge
     *
     * {
            "charge_msisdn": "255782150337",
            "charge_amount": "7000000",
            "country_code": "TZA",
            "currency_code": "TZS",
            "merchant_transaction_id": "SW2300045",
            "service_code": "SAFARIWALLET",
            "payment_mode_code": "STK_PUSH",
            "payment_option_code": "AIRTEL_TZ"
        }
     */
    public function chargeRequest($data = [])
    {
        // Post a charge request
        // Make a request to the charge API

        // /v3/checkout-api/charge/request

        $response = $this->client->post('/v3/checkout-api/charge/request', [
            'json' => [
                'charge_msisdn' => $data['charge_msisdn'],
                'charge_amount' => $data['charge_amount'],
                'country_code' => $data['country_code'],
                'currency_code' => $data['currency_code'],
                'merchant_transaction_id' => $data['merchant_transaction_id'],
                'service_code' => $data['service_code'],
                'payment_mode_code' => $data['payment_mode_code'],
                'payment_option_code' => $data['payment_option_code'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Check and charge a customer
     * @see https://dev-portal.tingg.africa/checkout-charge
     *
     * {
            "msisdn": 255700789667,
            "account_number": "ACCNO01108",
            "callback_url": "https://online.dev.tingg.africa/development/configuration-service/v3/merchantcallbackurlforsuccess",
            "country_code": "KEN",
            "currency_code": "KES",
            "customer_email": "johndoe@mail.com",
            "customer_first_name": "John",
            "customer_last_name": "Doe",
            "due_date": "2022-10-30 21:40:00",
            "fail_redirect_url": "https://jsonplaceholder.typicode.com/todos/1",
            "invoice_number": "",
            "merchant_transaction_id": "MTI0011212",
            "request_amount": 40000,
            "request_description": "Bag",
            "service_code": "JOHNDOEONLINESERVICE",
            "success_redirect_url": "https://jsonplaceholder.typicode.com/todos/1",
            "is_offline":true,
            "extra_data":"{test of extra data}",
            "payment_option_code": "SAFKE"
        }

     */
    public function initiateCombinedCheckoutCharge($data = [])
    {
        // Initiate a combined checkout-charge request
        // Make a request to the combined checkout-charge API

        // v3/checkout-api/checkout-charge

        $response = $this->client->post('/v3/checkout-api/checkout-charge', [
            'json' => [
                'msisdn' => $data['msisdn'],
                'account_number' => $data['account_number'],
                'callback_url' => $data['callback_url'],
                'country_code' => $data['country_code'],
                'currency_code' => $data['currency_code'],
                'customer_email' => $data['customer_email'],
                'customer_first_name' => $data['customer_first_name'],
                'customer_last_name' => $data['customer_last_name'],
                'due_date' => $data['due_date'],
                'fail_redirect_url' => $data['fail_redirect_url'],
                'invoice_number' => $data['invoice_number'],
                'merchant_transaction_id' => $data['merchant_transaction_id'],
                'request_amount' => $data['request_amount'],
                'request_description' => $data['request_description'],
                'service_code' => $data['service_code'],
                'success_redirect_url' => $data['success_redirect_url'],
                'is_offline' => $data['is_offline'],
                'extra_data' => $data['extra_data'],
                'payment_option_code' => $data['payment_option_code'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Acknowledge a transaction
     *
     * @see https://dev-portal.tingg.africa/acknowledgement
     * {
            "acknowledgement_amount": 30000,
            "acknowledgement_type": "full",
            "acknowledgement_narration": "Test acknowledgement",
            "acknowledgment_reference": "ACK230003",
            "merchant_transaction_id": "SW230007",
            "service_code": "SAFARIWALLET",
            "status_code": "183",
            "currency_code": "TZS"
        }
     */
    public function acknowledgeTransaction($data = [])
    {
        // Acknowledge a transaction
        // Make a request to the acknowledgement API

        // /v3/checkout-api/acknowledgement/request

        $response = $this->client->post('/v3/checkout-api/acknowledgement/request', [
            'json' => [
                'acknowledgement_amount' => $data['acknowledgement_amount'],
                'acknowledgement_type' => $data['acknowledgement_type'],
                'acknowledgement_narration' => $data['acknowledgement_narration'],
                'acknowledgment_reference' => $data['acknowledgment_reference'],
                'merchant_transaction_id' => $data['merchant_transaction_id'],
                'service_code' => $data['service_code'],
                'status_code' => $data['status_code'],
                'currency_code' => $data['currency_code'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Refund a payment
     *
     * @see https://dev-portal.tingg.africa/refund
     * {
            "merchant_transaction_id": "6793725835601",
            "refund_type": "full",
            "refund_narration": "User refunded from portal",
            "refund_reference": "6793725835601",
            "service_code": "JOEDOE22ONLINE",
            "payment_id":"860"
        }
     */
    public function refundPayment($data = [])
    {
        // Refund a payment
        // Make a request to the refund API

        // /v3/checkout-api/refund/request

        $response = $this->client->post('/v3/checkout-api/refund/request', [
            'json' => [
                'merchant_transaction_id' => $data['merchant_transaction_id'],
                'refund_type' => $data['refund_type'],
                'refund_narration' => $data['refund_narration'],
                'refund_reference' => $data['refund_reference'],
                'service_code' => $data['service_code'],
                'payment_id' => $data['payment_id'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Query the status of a checkout request
     *
     * @see https://dev-portal.tingg.africa/query-status
     *
     */
    public function queryCheckoutStatus($merchantTransactionId)
    {
        // Query the status of a checkout request
        // Make a request to the query status API

        // /v3/checkout-api/query/SAFARIWALLET/SW230012
        $url = sprintf('/v3/checkout-api/query/%s/%s', $this->options["service_code"], $merchantTransactionId);

        $response = $this->client->get($url, [
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Initiate OTP (One-Time Password) for a charge request
     *
     * https://dev-portal.tingg.africa/otp-validation
     * {
            "msisdn" : "233700000000",
            "checkout_request_id":"123456"
        }
     */
    public function initiateOTP($data = [])
    {
        // Initiate OTP (One-Time Password)
        // Make a request to the OTP initiation API

        // v3/checkout-api/initiate-otp

        $response = $this->client->post('/v3/checkout-api/initiate-otp', [
            'json' => [
                'msisdn' => $data['msisdn'],
                'checkout_request_id' => $data['checkout_request_id'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Validate OTP (One-Time Password) for a charge request
     *
     * @see https://dev-portal.tingg.africa/otp-validation
        {
            "msisdn" : "233700000000",
            "checkout_request_id":"123456",
            "token":"47242111"
        }
     */
    public function validateOTP($data = [])
    {
        // Validate OTP (One-Time Password)
        // Make a request to the OTP validation API
        // v3/checkout-api/validate-otp

        $response = $this->client->post('/v3/checkout-api/validate-otp', [
            'json' => [
                'msisdn' => $data['msisdn'],
                'checkout_request_id' => $data['checkout_request_id'],
                'token' => $data['token'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
    }
}
