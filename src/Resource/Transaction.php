<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\Exception\ApiException;

/**
 * Class Transaction
 * @package Mooore\eCurring\Resource
 *
 * @see https://docs.ecurring.com/transactions/get
 */
class Transaction extends AbstractResource
{
    // The status queued is not listed in the get transaction service, the queued status is always returned in the
    // create transaction response
    public const STATUS_QUEUED = 'queued';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FULFILLED = 'fulfilled';
    public const STATUS_CHARGED_BACK = 'charged_back';
    public const STATUS_PAYMENT_FAILED = 'payment_failed';
    public const STATUS_RESCHEDULED = 'rescheduled';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_PAYMENT_REMINDER_SCHEDULED = 'payment_reminder_scheduled';
    public const STATUS_PAYMENT_REMINDER_SENT = 'payment_reminder_sent';
    public const STATUS_PAYMENT_REMINDER_OVERDUE = 'payment_reminder_overdue';

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var \DateTime
     */
    public $scheduled_on;

    /**
     * @var \DateTime
     */
    public $due_date;

    /**
     * @var float The amount of the transaction, in Euro.
     */
    public $amount;

    /**
     * @var \DateTime
     */
    public $cancelled_on;

    /**
     * @var string
     */
    public $webhook_url;

    /**
     * @var string
     */
    public $payment_method;

    /**
     * @var \stdClass[]
     */
    public $history;

    /**
     * @var array
     */
    protected $exportProperties = [
        'status',
        'scheduled_on',
        'due_date',
        'amount',
        'cancelled_on',
        'webhook_url',
    ];

    /**
     * @throws ApiException
     */
    public function delete(): void
    {
        $this->client->transactions->delete($this->id);
    }

    public function isQueued(): bool
    {
        return $this->status === 'queued';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    public function isFulfilled(): bool
    {
        return $this->status === 'fulfilled';
    }

    public function isChargedBack(): bool
    {
        return $this->status === 'charged_back';
    }

    public function isPaymentFailed(): bool
    {
        return $this->status === 'payment_failed';
    }

    public function isRescheduled(): bool
    {
        return $this->status === 'rescheduled';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isPaymentReminderScheduled(): bool
    {
        return $this->status === 'payment_reminder_scheduled';
    }

    public function isPaymentReminderSent(): bool
    {
        return $this->status === 'payment_reminder_sent';
    }

    public function isPaymentReminderOverdue(): bool
    {
        return $this->status === 'payment_reminder_overdue';
    }
}
