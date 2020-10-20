<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\Exception\ApiException;

/**
 * Class Subscription
 * @package Mooore\eCurring\Resource
 * @see https://docs.ecurring.com/subscriptions/get/
 */
class Subscription extends AbstractResource
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_UNVERIFIED = 'unverified';

    /**
     * @var string
     */
    public $mandate_code;
    /**
     * @var bool
     */
    public $mandate_accepted;
    /**
     * @var \DateTime
     */
    public $mandate_accepted_date;
    /**
     * @var \DateTime
     */
    public $start_date;
    /**
     * @var string
     */
    public $status;
    /**
     * @var \DateTime
     */
    public $cancel_date;
    /**
     * @var \DateTime
     */
    public $resume_date;
    /**
     * @var string
     */
    public $confirmation_page;
    /**
     * @var bool
     */
    public $confirmation_sent;
    /**
     * @var string
     */
    public $subscription_webhook_url;
    /**
     * @var string
     */
    public $transaction_webhook_url;
    /**
     * @var string
     */
    public $success_redirect_url;
    /**
     * @var mixed
     */
    public $metadata;
    /**
     * @var mixed
     */
    public $relationships;
    /**
     * @var array
     */
    protected $exportProperties = [
        'mandate_code',
        'mandate_accepted',
        'mandate_accepted_date',
        'start_date',
        'status',
        'cancel_date',
        'resume_date',
        'confirmation_page',
        'confirmation_sent',
        'subscription_webhook_url',
        'transaction_webhook_url',
        'success_redirect_url',
        'metadata',
        'relationships'
    ];

    /**
     * @param array $data
     * @return Subscription
     * @throws ApiException
     */
    public function update(array $data = []): Subscription
    {
        $data = !empty($data) ? $data : $this->toArray();

        $updated = $this->client->subscriptions->update($this->id, $data)->toArray();

        foreach ($updated as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * @return Subscription
     * @throws ApiException
     */
    public function activate(): Subscription
    {
        return $this->update([
            'status' => self::STATUS_ACTIVE,
            'mandate_accepted' => true,
            'mandate_accepted_date' => (new \DateTime())->format(\DateTime::ATOM)
        ]);
    }

    /**
     * @param \DateTime|null $resumeDate
     * @return Subscription
     * @throws ApiException
     */
    public function pause(\DateTime $resumeDate = null): Subscription
    {
        return $this->update([
            'status' => self::STATUS_PAUSED,
            'resume_date' => $resumeDate !== null ? $resumeDate->format(\DateTime::ATOM) : null
        ]);
    }

    /**
     * @return Subscription
     * @throws ApiException
     */
    public function resume(): Subscription
    {
        return $this->update(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param \DateTime|null $cancelDate
     * @return Subscription
     * @throws ApiException
     */
    public function cancel(\DateTime $cancelDate = null): Subscription
    {
        if ($cancelDate !== null) {
            return $this->update(['cancel_date' => $cancelDate->format(\DateTime::ATOM)]);
        }

        return $this->update(['status' => self::STATUS_CANCELLED]);
    }

    public function createInvoice(array $attributes = []): Invoice
    {
        return $this->client->invoices->createForSubscription($this->id, $attributes);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPaused(): bool
    {
        return $this->status === self::STATUS_PAUSED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isUnverified(): bool
    {
        return $this->status === self::STATUS_UNVERIFIED;
    }
}
