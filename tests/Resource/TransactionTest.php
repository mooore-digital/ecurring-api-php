<?php

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\eCurringHttpClient;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @param string $status the status
     * @param string $function the name of the function to invoke on the transaction
     * @param boolean $expected_boolean the expected result
     *
     * @dataProvider transactionStatusDataProvider
     */
    public function testSubscriptionStatus($status, $function, $expected_boolean)
    {
        $subscription = new Transaction($this->createMock(eCurringHttpClient::class));
        $subscription->status = $status;

        $this->assertEquals($expected_boolean, $subscription->{$function}());
    }

    public function transactionStatusDataProvider()
    {
        return [
            [Transaction::STATUS_QUEUED, 'isQueued', true],
            [Transaction::STATUS_QUEUED, 'isScheduled', false],
            [Transaction::STATUS_QUEUED, 'isSucceeded', false],
            [Transaction::STATUS_QUEUED, 'isFulfilled', false],
            [Transaction::STATUS_QUEUED, 'isChargedBack', false],
            [Transaction::STATUS_QUEUED, 'isPaymentFailed', false],
            [Transaction::STATUS_QUEUED, 'isRescheduled', false],
            [Transaction::STATUS_QUEUED, 'isFailed', false],
            [Transaction::STATUS_QUEUED, 'isRefunded', false],
            [Transaction::STATUS_QUEUED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_QUEUED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_QUEUED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_SCHEDULED, 'isQueued', false],
            [Transaction::STATUS_SCHEDULED, 'isScheduled', true],
            [Transaction::STATUS_SCHEDULED, 'isSucceeded', false],
            [Transaction::STATUS_SCHEDULED, 'isFulfilled', false],
            [Transaction::STATUS_SCHEDULED, 'isChargedBack', false],
            [Transaction::STATUS_SCHEDULED, 'isPaymentFailed', false],
            [Transaction::STATUS_SCHEDULED, 'isRescheduled', false],
            [Transaction::STATUS_SCHEDULED, 'isFailed', false],
            [Transaction::STATUS_SCHEDULED, 'isRefunded', false],
            [Transaction::STATUS_SCHEDULED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_SCHEDULED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_SCHEDULED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_SUCCEEDED, 'isQueued', false],
            [Transaction::STATUS_SUCCEEDED, 'isScheduled', false],
            [Transaction::STATUS_SUCCEEDED, 'isSucceeded', true],
            [Transaction::STATUS_SUCCEEDED, 'isFulfilled', false],
            [Transaction::STATUS_SUCCEEDED, 'isChargedBack', false],
            [Transaction::STATUS_SUCCEEDED, 'isPaymentFailed', false],
            [Transaction::STATUS_SUCCEEDED, 'isRescheduled', false],
            [Transaction::STATUS_SUCCEEDED, 'isFailed', false],
            [Transaction::STATUS_SUCCEEDED, 'isRefunded', false],
            [Transaction::STATUS_SUCCEEDED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_SUCCEEDED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_SUCCEEDED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_FULFILLED, 'isQueued', false],
            [Transaction::STATUS_FULFILLED, 'isScheduled', false],
            [Transaction::STATUS_FULFILLED, 'isSucceeded', false],
            [Transaction::STATUS_FULFILLED, 'isFulfilled', true],
            [Transaction::STATUS_FULFILLED, 'isChargedBack', false],
            [Transaction::STATUS_FULFILLED, 'isPaymentFailed', false],
            [Transaction::STATUS_FULFILLED, 'isRescheduled', false],
            [Transaction::STATUS_FULFILLED, 'isFailed', false],
            [Transaction::STATUS_FULFILLED, 'isRefunded', false],
            [Transaction::STATUS_FULFILLED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_FULFILLED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_FULFILLED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_CHARGED_BACK, 'isQueued', false],
            [Transaction::STATUS_CHARGED_BACK, 'isScheduled', false],
            [Transaction::STATUS_CHARGED_BACK, 'isSucceeded', false],
            [Transaction::STATUS_CHARGED_BACK, 'isFulfilled', false],
            [Transaction::STATUS_CHARGED_BACK, 'isChargedBack', true],
            [Transaction::STATUS_CHARGED_BACK, 'isPaymentFailed', false],
            [Transaction::STATUS_CHARGED_BACK, 'isRescheduled', false],
            [Transaction::STATUS_CHARGED_BACK, 'isFailed', false],
            [Transaction::STATUS_CHARGED_BACK, 'isRefunded', false],
            [Transaction::STATUS_CHARGED_BACK, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_CHARGED_BACK, 'isPaymentReminderSent', false],
            [Transaction::STATUS_CHARGED_BACK, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_PAYMENT_FAILED, 'isQueued', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isScheduled', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isSucceeded', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isFulfilled', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isChargedBack', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isPaymentFailed', true],
            [Transaction::STATUS_PAYMENT_FAILED, 'isRescheduled', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isFailed', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isRefunded', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_PAYMENT_FAILED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_RESCHEDULED, 'isQueued', false],
            [Transaction::STATUS_RESCHEDULED, 'isScheduled', false],
            [Transaction::STATUS_RESCHEDULED, 'isSucceeded', false],
            [Transaction::STATUS_RESCHEDULED, 'isFulfilled', false],
            [Transaction::STATUS_RESCHEDULED, 'isChargedBack', false],
            [Transaction::STATUS_RESCHEDULED, 'isPaymentFailed', false],
            [Transaction::STATUS_RESCHEDULED, 'isRescheduled', true],
            [Transaction::STATUS_RESCHEDULED, 'isFailed', false],
            [Transaction::STATUS_RESCHEDULED, 'isRefunded', false],
            [Transaction::STATUS_RESCHEDULED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_RESCHEDULED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_RESCHEDULED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_FAILED, 'isQueued', false],
            [Transaction::STATUS_FAILED, 'isScheduled', false],
            [Transaction::STATUS_FAILED, 'isSucceeded', false],
            [Transaction::STATUS_FAILED, 'isFulfilled', false],
            [Transaction::STATUS_FAILED, 'isChargedBack', false],
            [Transaction::STATUS_FAILED, 'isPaymentFailed', false],
            [Transaction::STATUS_FAILED, 'isRescheduled', false],
            [Transaction::STATUS_FAILED, 'isFailed', true],
            [Transaction::STATUS_FAILED, 'isRefunded', false],
            [Transaction::STATUS_FAILED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_FAILED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_FAILED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_REFUNDED, 'isQueued', false],
            [Transaction::STATUS_REFUNDED, 'isScheduled', false],
            [Transaction::STATUS_REFUNDED, 'isSucceeded', false],
            [Transaction::STATUS_REFUNDED, 'isFulfilled', false],
            [Transaction::STATUS_REFUNDED, 'isChargedBack', false],
            [Transaction::STATUS_REFUNDED, 'isPaymentFailed', false],
            [Transaction::STATUS_REFUNDED, 'isRescheduled', false],
            [Transaction::STATUS_REFUNDED, 'isFailed', false],
            [Transaction::STATUS_REFUNDED, 'isRefunded', true],
            [Transaction::STATUS_REFUNDED, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_REFUNDED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_REFUNDED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isQueued', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isScheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isSucceeded', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isFulfilled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isChargedBack', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isPaymentFailed', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isRescheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isFailed', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isRefunded', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isPaymentReminderScheduled', true],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isPaymentReminderSent', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SCHEDULED, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isQueued', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isScheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isSucceeded', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isFulfilled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isChargedBack', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isPaymentFailed', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isRescheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isFailed', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isRefunded', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isPaymentReminderSent', true],
            [Transaction::STATUS_PAYMENT_REMINDER_SENT, 'isPaymentReminderOverdue', false],

            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isQueued', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isScheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isSucceeded', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isFulfilled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isChargedBack', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isPaymentFailed', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isRescheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isFailed', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isRefunded', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isPaymentReminderScheduled', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isPaymentReminderSent', false],
            [Transaction::STATUS_PAYMENT_REMINDER_OVERDUE, 'isPaymentReminderOverdue', true],
        ];
    }
}
