<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\Collection;

abstract class AbstractCollectionEndpoint extends AbstractEndpoint
{

    /**
     * Get the collection object that is used by this API endpoint. Every API endpoint uses one type of
     * collection object.
     *
     * @param int $count
     * @param object $links
     *
     * @return Collection
     */
    abstract protected function getResourceCollectionObject(int $count, object $links);

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param array $filters
     * @return array|Collection
     * @throws ApiException
     */
    protected function rest_list(int $pageNumber = 1, int $pageSize = 10, array $filters = [])
    {
        $filters = array_merge(['page[number]' => $pageNumber, 'page[size]' => $pageSize], $filters);

        $apiPath = $this->getApiPath($filters);

        $result = $this->client->performHttpCall('GET', $apiPath);

        $collection = $this->getResourceCollectionObject($result->meta->total, $result->links);

        foreach ($result->data as $data) {
            $collection[] = $this->resourceFactory->createFromApiResult($data, $this->getResourceObject());
        }

        return $collection;
    }
}