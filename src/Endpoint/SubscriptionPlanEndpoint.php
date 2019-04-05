<?php

declare(strict_types=1);

namespace Marissen\eCurring\Endpoint;

use Marissen\eCurring\Exception\ApiException;
use Marissen\eCurring\Resource\AbstractResource;
use Marissen\eCurring\Resource\Collection;
use Marissen\eCurring\Resource\SubscriptionPlan;
use Marissen\eCurring\Resource\SubscriptionPlanCollection;

class SubscriptionPlanEndpoint extends AbstractEndpoint
{
    protected $resourcePath = 'subscription-plans';

    /**
     * @return AbstractResource
     */
    protected function getResourceObject()
    {
        return new SubscriptionPlan($this->client);
    }

    /**
     * @param int $count
     * @param object $links
     * @return mixed
     */
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
