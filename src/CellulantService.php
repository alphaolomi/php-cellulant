<?php

namespace Alphaolomi\Cellulant;

use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * Class CellulantService
 *
 * @author Alpha Olomi <alphaolomi@gmail.com>
 * @since 1.0.0
 * @api
 *
 * @see https://dev-portal.tingg.africa
 *
 * @package Alphaolomi\Cellulant
 */
class CellulantService
{
    public const SANDBOX_HOST = "https://api-dev.tingg.africa";
    public const PROD_HOST = "https://api.tingg.africa";

    /**
     * The base URL for the API
     *
     * @var string
     */
    private string $baseUrl;

    /**
     * The options for the Service
     *
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * The access token
     * Can be used to make subsequent requests
     *
     * @var string
     */
    private string $accessToken;

    /**
     * The GuzzleHttp Client
     *
     * @var Client
     */
    private Client $client;

    /**
     * CellulantService constructor.
     *
     * @param array $options<clientId, clientSecret, apiKey, serviceCode, callbackUrl, env, debug>
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $options = [])
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

        // Set the environment
        if (! isset($options['env'])) {
            $options['env'] = 'sandbox';
        }

        // Set Debug mode
        if (! isset($options['debug'])) {
            $options['debug'] = false;
        }


        // Set the base URL
        // Sandbox or Production based on the env
        $this->baseUrl = $options['env'] === 'sandbox' ? self::SANDBOX_HOST : self::PROD_HOST;

        // Set the options
        $this->options = $options;

        // Setup the GuzzleHttp Client
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apiKey' => $this->options['apiKey'],
            ],
            // Enable the http_errors option to throw exceptions on 4xx and 5xx responses
            // See https://docs.guzzlephp.org/en/stable/request-options.html#http-errors
            'http_errors' => true,

            // Debug enabled
            // See https://docs.guzzlephp.org/en/stable/request-options.html#debug
            'debug' => $this->options['debug'],
        ]);
    }

    /**
     * Retrieve an authentication token.
     * Authenticates the client and returns an access token
     * that will be used to make subsequent requests.
     * The access token is valid for 1 hour.
     * The access token should be stored and reused
     *
     *
     * @see https://dev-portal.tingg.africa/checkout-authentication
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticate()
    {
        $payload = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->options['clientId'],
            'client_secret' => $this->options['clientSecret'],
        ];
        $response = $this->client->post('/v1/oauth/token/request', [
            'json' => $payload,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        // Store the access token
        $this->accessToken = $data['access_token'];

        return $data;
    }

    /**
     * Make a request to the checkout API
     * Initiates a checkout request with details
     *
     * @see https://dev-portal.tingg.africa/checkout
     *
     * @param array $data
     * @return array
     */
    public function checkoutRequest($data)
    {
        $payload = [
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
        ];

        $response = $this->client->post('/v3/checkout-api/checkout/request', [
            'json' => $payload,
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    /**
     * Post a charge request
     * Make a request to the charge API
     * i.e. request to debit amount from customer for
     * the checkout request that was posted earlier
     * in the request/initiate function.
     *
     * @see https://dev-portal.tingg.africa/charge
     *
     * [
     *      "charge_msisdn" => "255782150337",
     *      "charge_amount" => "7000000",
     *      "country_code" => "TZA",
     *      "currency_code" => "TZS",
     *      "merchant_transaction_id" => "SW2300045",
     *      "service_code" => "SAFARIWALLET",
     *      "payment_mode_code" => "STK_PUSH",
     *      "payment_option_code" => "AIRTEL_TZ"
     *  ]
     *
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function chargeRequest($data = [])
    {
        $payload = [
            'charge_msisdn' => $data['charge_msisdn'],
            'charge_amount' => $data['charge_amount'],
            'country_code' => $data['country_code'],
            'currency_code' => $data['currency_code'],
            'merchant_transaction_id' => $data['merchant_transaction_id'],
            'service_code' => $data['service_code'],
            'payment_mode_code' => $data['payment_mode_code'],
            'payment_option_code' => $data['payment_option_code'],
        ];

        $response = $this->client->post('/v3/checkout-api/charge/request', [
            'json' => $payload,
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    /**
     * Make a request to the combined checkout-charge API
     * to initiate a checkout request and post a charge request.
     *
     * @see https://dev-portal.tingg.africa/checkout-charge
     * Data format
     * [
     *      "msisdn" => 255700789667,
     *      "account_number" => "ACCNO01108",
     *      "callback_url" => "https://online.dev.tingg.africa/development/configuration-service/v3/merchantcallbackurlforsuccess",
     *      "country_code" => "KEN",
     *      "currency_code" => "KES",
     *      "customer_email" => "johndoe@mail.com",
     *      "customer_first_name" => "John",
     *      "customer_last_name" => "Doe",
     *      "due_date" => "2022-10-30 21:40:00",
     *      "fail_redirect_url" => "https://jsonplaceholder.typicode.com/todos/1",
     *      "invoice_number" => "",
     *      "merchant_transaction_id" => "MTI0011212",
     *      "request_amount" => 40000,
     *      "request_description" => "Bag",
     *      "service_code" => "JOHNDOEONLINESERVICE",
     *      "success_redirect_url" => "https://jsonplaceholder.typicode.com/todos/1",
     *      "is_offline":true,
     *      "extra_data":"{test of extra data}",
     *      "payment_option_code" => "VODACOMTZ"
     *  ]
     *
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function initiateCombinedCheckoutCharge($data = [])
    {
        $payload = [
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
        ];

        $response = $this->client->post('/v3/checkout-api/checkout-charge', [
            'json' => $payload,
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    /**
     * Make a request to the acknowledgement API
     * Acknowledge a transaction
     *
     * @see https://dev-portal.tingg.africa/acknowledgement
     * [
     *      "acknowledgement_amount" => 30000,
     *      "acknowledgement_type" => "full",
     *      "acknowledgement_narration" => "Test acknowledgement",
     *      "acknowledgment_reference" => "ACK230003",
     *      "merchant_transaction_id" => "SW230007",
     *      "service_code" => "SAFARIWALLET",
     *      "status_code" => "183",
     *      "currency_code" => "TZS"
     *  ]
     *
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function acknowledgeTransaction($data = [])
    {
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

        return $data;
    }

    /**
     * Make a request to the refund API.
     * Refund a payment.
     *
     * @see https://dev-portal.tingg.africa/refund
     * [
     *   "merchant_transaction_id" => "6793725835601",
     *   "refund_type" => "full",
     *   "refund_narration" => "User refunded from portal",
     *   "refund_reference" => "6793725835601",
     *   "service_code" => "JOEDOE22ONLINE",
     *   "payment_id":"860"
     * ]
     *
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refundPayment($data = [])
    {
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

        return $data;
    }

    /**
     * Make a request to the query status API
     * Query the status of a checkout request
     *
     * @see https://dev-portal.tingg.africa/query-status
     *
     * @param string $merchantTransactionId
     * @return array
     * @throws \Exception
     */
    public function queryCheckoutStatus(string $merchantTransactionId)
    {
        if (empty($merchantTransactionId)) {
            throw new \Exception("Merchant transaction ID is required");
        }

        $url = sprintf('/v3/checkout-api/query/%s/%s', $this->options["serviceCode"], $merchantTransactionId);

        $response = $this->client->get($url, [
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    /**
     * Initiate OTP (One-Time Password) for a charge request
     *
     * @see https://dev-portal.tingg.africa/otp-validation
     * Data format
     * [
     *     "msisdn" => "233700000000",
     *     "checkout_request_id" => "123456"
     * ]
     *
     * @param array $data
     * @return array
     * @throws \Exception
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

        return $data;
    }

    /**
     * Make a request to the OTP validation API
     * Validate OTP (One-Time Password) for a charge request
     *
     * @see https://dev-portal.tingg.africa/otp-validation
     * Data format
     * [
     *     "msisdn"  => "233700000000",
     *     "checkout_request_id":"123456",
     *     "token":"47242111"
     * ]
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function validateOTP($data = [])
    {
        $response = $this->client->post('/v3/checkout-api/validate-otp', [
            'json' => [
                'msisdn' => $data['msisdn'],
                'checkout_request_id' => $data['checkout_request_id'],
                'token' => $data['token'],
            ],
            "headers" => ['Authorization' => 'Bearer ' . $this->accessToken],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }
}
