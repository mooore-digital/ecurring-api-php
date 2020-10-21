<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

class SubscriptionPlan extends AbstractResource
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var \DateTime
     */
    public $start_date;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $mandate_authentication_method;
    /**
     * @var bool
     */
    public $send_invoice;
    /**
     * @var int
     */
    public $storno_retries;
    /**
     * @var string
     */
    public $terms;
    /**
     * @var \stdObject
     */
    public $relationships;
    /**
     * @var array
     */
    protected $exportProperties = [
        'name',
        'description',
        'status',
        'mandate_authentication_method',
        'send_invoice',
        'storno_retries',
        'terms',
        'relationships'
    ];

    /**
     * @param int $customerId
     * @param array $attributes
     * @return Subscription
     * @throws \Mooore\eCurring\Exception\ApiException
     */
    public function createSubscription(int $customerId, array $attributes = []): Subscription
    {
        return $this->client->subscriptions->create($customerId, $this->id, $attributes);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInActive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }
}
