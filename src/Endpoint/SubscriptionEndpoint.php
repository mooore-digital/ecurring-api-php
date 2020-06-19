<?php

declare(strict_types=1);

namespace Mooore\eCurring\Endpoint;

use Mooore\eCurring\Exception\ApiException;
use Mooore\eCurring\Resource\AbstractResource;
use Mooore\eCurring\Resource\Collection;
use Mooore\eCurring\Resource\Subscription;
use Mooore\eCurring\Resource\SubscriptionCollection;

class SubscriptionEndpoint extends AbstractCollectionEndpoint
{
    /**
     * @var string
     */
    protected $resourcePath = 'subscriptions';

    protected $resourceType = 'subscription';

    /**
     * @return AbstractResource
     */
    protected function getResourceObject()
    {
        return new Subscription($this->client);
    }

    /**
     * @param int $count
     * @param object $links
     * @return Collection
     */
    protected function getResourceCollectionObject(int $count, object $links)
    {
        return new SubscriptionCollection($this->client, $this->resourceFactory, $count, $links);
    }

    /**
     * @param int $customerId
     * @param int $subscriptionPlanId
     * @param array $attributes
     * @return AbstractResource|Subscription
     * @throws ApiException
     */
    public function create(int $customerId, int $subscriptionPlanId, array $attributes = [])
    {
        return $this->rest_create(
            $this->createPayloadFromAttributes(array_merge([
                'customer_id' => $customerId,
                'subscription_plan_id' => $subscriptionPlanId
            ], $attributes))
        );
    }

    /**
     * @param int $subscriptionId
     * @return AbstractResource|Subscription
     * @throws ApiException
     */
    public function get(int $subscriptionId)
    {
        return $this->rest_read($subscriptionId, []);
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @return Collection|Subscription[]|SubscriptionCollection
     * @throws ApiException
     */
    public function page(int $pageNumber = 1, int $pageSize = 10)
    {
        return $this->rest_list($pageNumber, $pageSize);
    }

    /**
     * @param int $subscriptionId
     * @param array $attributes
     * @return AbstractResource|Subscription
     * @throws ApiException
     */
    public function update(int $subscriptionId, array $attributes)
    {
        return $this->rest_update(
            $subscriptionId,
            $this->createPayloadFromAttributes($attributes, $subscriptionId)
        );
    }
}
