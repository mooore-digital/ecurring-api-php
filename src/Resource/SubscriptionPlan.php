<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

class SubscriptionPlan extends AbstractResource
{
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
     * @var array
     */
    protected $exportProperties = [
        'name',
        'description',
        'status',
        'mandate_authentication_method',
        'send_invoice',
        'storno_retries',
        'terms'
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
}
