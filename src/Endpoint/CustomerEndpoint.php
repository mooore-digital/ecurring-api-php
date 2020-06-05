<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\Collection;
use Mooore\eCurring\Resource\Customer;
use Mooore\eCurring\Resource\CustomerCollection;

class CustomerEndpoint extends AbstractEndpoint
{
    protected $resourcePath = 'customers';

    protected $resourceType = 'customer';

    /**
     * @return AbstractResource
     */
    protected function getResourceObject()
    {
        return new Customer($this->client);
    }

    /**
     * @param int $count
     * @param object $links
     * @return Collection
     */
    protected function getResourceCollectionObject(int $count, object $links)
    {
        return new CustomerCollection($this->client, $this->resourceFactory, $count, $links);
    }

    /**
     * @param array $attributes
     * @param array $filters
     * @return AbstractResource|Customer
     * @throws ApiException
     */
    public function create(array $attributes, array $filters = [])
    {
        return $this->rest_create(
            $this->createPayloadFromAttributes($attributes),
            $filters
        );
    }

    /**
     * @param int $customerId
     * @param array $parameters
     * @return AbstractResource|Customer
     * @throws ApiException
     */
    public function get(int $customerId, array $parameters = [])
    {
        return $this->rest_read($customerId, $parameters);
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param array $parameters
     * @return Collection|Customer[]|CustomerCollection
     * @throws ApiException
     */
    public function page(int $pageNumber = 1, int $pageSize = 10, array $parameters = [])
    {
        return $this->rest_list($pageNumber, $pageSize, $parameters);
    }

    /**
     * @param int $customerId
     * @param array $attributes
     * @return AbstractResource|Customer
     * @throws ApiException
     */
    public function update(int $customerId, array $attributes)
    {
        return $this->rest_update(
            $customerId,
            $this->createPayloadFromAttributes($attributes, $customerId)
        );
    }
}
