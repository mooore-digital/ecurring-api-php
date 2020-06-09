<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use InvalidArgumentException;
use Mooore\eCurring\eCurringHttpClient;
use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\ResourceFactory;
use Mooore\eCurring\Resource\ResourceFactoryInterface;

abstract class AbstractEndpoint
{
    /**
     * @var eCurringHttpClient
     */
    protected $client;
    /**
     * @var ResourceFactoryInterface
     */
    protected $resourceFactory;
    /**
     * @var string
     */
    protected $resourcePath;

    /**
     * @var string the resource type of the resource object for the endpoint
     */
    protected $resourceType;

    /**
     * @var string|null
     */
    protected $parentId;

    public function __construct(eCurringHttpClient $client, ResourceFactoryInterface $resourceFactory = null)
    {
        $this->client = $client;
        $this->resourceFactory = $resourceFactory ?: new ResourceFactory();
    }

    /**
     * @param array $body
     * @param array $filters
     * @return AbstractResource
     * @throws ApiException
     */
    protected function rest_create(array $body, array $filters = [])
    {
        $result = $this->client->performHttpCall(
            'POST',
            $this->getApiPath($filters),
            $this->parseRequestBody($body)
        );

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * @param int|string $id
     * @param array $filters
     * @return AbstractResource
     * @throws ApiException
     */
    protected function rest_read($id, array $filters)
    {
        $result = $this->client->performHttpCall(
            'GET',
            sprintf('%s/%s', $this->getResourcePath(), $id) . $this->buildQueryString($filters)
        );

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * @param int $id
     * @param array $data
     * @return AbstractResource
     * @throws ApiException
     */
    protected function rest_update(int $id, array $data)
    {
        $result = $this->client->performHttpCall(
            'PATCH',
            sprintf('%s/%s', $this->getResourcePath(), $id),
            $this->parseRequestBody($data)
        );

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * @param int|string $id
     * @param array $body
     * @return AbstractResource|null
     * @throws ApiException
     */
    protected function rest_delete($id, array $body = [])
    {
        $result = $this->client->performHttpCall(
            'DELETE',
            sprintf('%s/%s', $this->getResourcePath(), $id),
            $this->parseRequestBody($body)
        );

        if ($result === null) {
            return null;
        }

        return $this->resourceFactory->createFromApiResult($result->data, $this->getResourceObject());
    }

    /**
     * Create the data payload for the resource object with the supplied attributes and optionally sets the id.
     *
     * @param array $attributes the attributes of the resource to set.
     * @param int $id (optional) the identifier of the resource to set the attributes for, default 0.
     * @return array[] containing the data payload.
     */
    protected function createPayloadFromAttributes(array $attributes, int $id = 0)
    {
        $payload = [
            'data' => [
                'type' => $this->getResourceType(),
                'attributes' => $attributes
            ]
        ];

        if ($id !== 0) {
            $payload['data']['id'] = (string) $id;
        }

        return $payload;
    }

    private function getResourcePath(): string
    {
        return $this->resourcePath;
    }

    private function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @param array $filters
     * @return string
     */
    protected function getApiPath(array $filters = [])
    {
        return $this->getResourcePath() . $this->buildQueryString($filters);
    }

    private function buildQueryString(array $filters): string
    {
        if (count($filters) === 0) {
            return '';
        }

        foreach ($filters as $key => $value) {
            if ($value === true) {
                $filters[$key] = 'true';
            } elseif ($value === false) {
                $filters[$key] = 'false';
            }
        }

        return '?' . http_build_query($filters, '', '&');
    }

    /**
     * @param array $body
     * @return string|null
     * @throws ApiException
     */
    private function parseRequestBody(array $body)
    {
        if (!count($body)) {
            return null;
        }

        try {
            $encoded = \GuzzleHttp\json_encode($body);
        } catch (InvalidArgumentException $e) {
            throw new ApiException(sprintf('Failed to encode body to JSON: "%s".', $e->getMessage()));
        }

        return $encoded;
    }

    /**
     * Get the object that is used by this API endpoint. Every API endpoint uses one type of object.
     *
     * @return AbstractResource
     */
    abstract protected function getResourceObject();
}
