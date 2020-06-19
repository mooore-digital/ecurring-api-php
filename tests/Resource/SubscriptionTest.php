<?php


namespace Mooore\eCurring\Resource;

use Mooore\eCurring\eCurringHttpClient;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * @param string $status the status
     * @param string $function the name of the function to invoke on the subscription
     * @param boolean $expected_boolean the expected result
     *
     * @dataProvider subscriptionStatusDataProvider
     */
    public function testSubscriptionStatus($status, $function, $expected_boolean)
    {
        $subscription = new Subscription($this->createMock(eCurringHttpClient::class));
        $subscription->status = $status;

        $this->assertEquals($expected_boolean, $subscription->{$function}());
    }

    public function subscriptionStatusDataProvider()
    {
        return [
            [Subscription::STATUS_ACTIVE, 'isActive', true],
            [Subscription::STATUS_ACTIVE, 'isPaused', false],
            [Subscription::STATUS_ACTIVE, 'isCancelled', false],
            [Subscription::STATUS_ACTIVE, 'isUnverified', false],

            [Subscription::STATUS_PAUSED, 'isActive', false],
            [Subscription::STATUS_PAUSED, 'isPaused', true],
            [Subscription::STATUS_PAUSED, 'isCancelled', false],
            [Subscription::STATUS_PAUSED, 'isUnverified', false],

            [Subscription::STATUS_CANCELLED, 'isActive', false],
            [Subscription::STATUS_CANCELLED, 'isPaused', false],
            [Subscription::STATUS_CANCELLED, 'isCancelled', true],
            [Subscription::STATUS_CANCELLED, 'isUnverified', false],

            [Subscription::STATUS_UNVERIFIED, 'isActive', false],
            [Subscription::STATUS_UNVERIFIED, 'isPaused', false],
            [Subscription::STATUS_UNVERIFIED, 'isCancelled', false],
            [Subscription::STATUS_UNVERIFIED, 'isUnverified', true],
        ];
    }

}