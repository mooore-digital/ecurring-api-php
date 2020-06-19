<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\Collection;
use Mooore\eCurring\Resource\SubscriptionPlan;
use Mooore\eCurring\Resource\SubscriptionPlanCollection;

class SubscriptionPlanEndpoint extends AbstractCollectionEndpoint
{
    protected $resourcePath = 'subscription-plans';

    protected $resourceType = 'subscription-plan';

    protected function getResourceObject()
    {
        return new SubscriptionPlan($this->client);
    }

    protected function getResourceCollectionObject(int $count, object $links)
    {
        return new SubscriptionPlanCollection($this->client, $this->resourceFactory, $count, $links);
    }

    /**
     * @param int $subscriptionPlanId
     * @param array $parameters
     * @return AbstractResource|SubscriptionPlan
     * @throws ApiException
     */
    public function get(int $subscriptionPlanId, array $parameters = [])
    {
        return $this->rest_read($subscriptionPlanId, $parameters);
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param array $parameters
     * @return Collection|SubscriptionPlan[]|SubscriptionPlanCollection
     * @throws ApiException
     */
    public function page(int $pageNumber = 1, int $pageSize = 10, array $parameters = [])
    {
        return $this->rest_list($pageNumber, $pageSize, $parameters);
    }
}
