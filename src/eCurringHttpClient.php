<?php

declare(strict_types=1);

namespace Marissen\eCurring;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Marissen\eCurring\Endpoint\CustomerEndpoint;
use Marissen\eCurring\Endpoint\SubscriptionEndpoint;
use Marissen\eCurring\Endpoint\SubscriptionPlanEndpoint;
use Marissen\eCurring\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class eCurringHttpClient
{
    /**
     * Base url of remote API.
     */
    private const API_BASE_URL = 'https://api.ecurring.com';
    /**
     * Default response timeout (in seconds).
     */
    private const TIMEOUT = 10;
    /**
     * eCurring HTTP Client Version.
     */
    private const VERSION = '0.1.0';

    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $apiBaseUrl = self::API_BASE_URL;
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var SubscriptionPlanEndpoint
     */
    public $subscriptionPlans;
    /**
     * @var CustomerEndpoint
     */
    public $customers;
    /**
     * @var SubscriptionEndpoint
     */
    public $subscriptions;

    public function __construct(ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client([
            RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
            RequestOptions::TIMEOUT => self::TIMEOUT
        ]);
        $this->subscriptionPlans = new SubscriptionPlanEndpoint($this);
        $this->customers = new CustomerEndpoint($this);
        $this->subscriptions = new SubscriptionEndpoint($this);
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * @param string $httpMethod
     * @param string $apiMethod
     * @param string|resource|StreamInterface|null $httpBody
     * @return object
     * @throws ApiException
     */
    public function performHttpCall(string $httpMethod, string $apiMethod, $httpBody = null)
    {
        $url = sprintf('%s/%s', $this->apiBaseUrl, $apiMethod);

        return $this->performHttpCallToUrl($httpMethod, $url, $httpBody);
    }

    /**
     * @param string $httpMethod
     * @param string $url
     * @param string|resource|StreamInterface|null $httpBody
     * @return object
     * @throws ApiException
     */
    public function performHttpCallToUrl(string $httpMethod, string $url, $httpBody = null)
    {
        $this->assertApiKeyIsConfigured();

        $headers = [
            'Accept' => 'application/json, application/vnd.api+json',
            'X-Authorization' => $this->apiKey,
            'User-Agent' => sprintf('eCurring PHP API Client (version %s)', $this->getVersion())
        ];

        $request = new Request($httpMethod, $url, $headers, $httpBody);

        try {
            $response = $this->httpClient->send($request, ['http_errors' => false]);
        } catch (GuzzleException $e) {
            throw ApiException::createFromGuzzleException($e);
        }

        if (empty($response)) {
            throw new ApiException('No API response received.');
        }

        return $this->parseResponseBody($response);
    }

    /**
     * @param ResponseInterface $response
     * @return object
     * @throws ApiException
     */
    private function parseResponseBody(ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        if (empty($body)) {
            throw new ApiException('Empty response body.');
        }

        $object = @json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(sprintf('Failed to decode JSON response body: "%s"', $body));
        }

        if ($response->getStatusCode() >= 400) {
            throw ApiException::createFromResponse($response);
        }

        return $object;
    }

    /**
     * @return void
     * @throws ApiException
     */
    private function assertApiKeyIsConfigured()
    {
        if (empty($this->apiKey)) {
            throw new ApiException('API key is not configured yet. Please use eCurringHttpClient::setApiKey().');
        }
    }
}
