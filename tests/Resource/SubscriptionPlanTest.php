<?php

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\eCurringHttpClient;
use PHPUnit\Framework\TestCase;

class SubscriptionPlanTest extends TestCase
{

    /**
     * @param string $status the status
     * @param string $function the name of the function to invoke on the subscription plan
     * @param boolean $expected_boolean the expected result
     *
     * @dataProvider subscriptionPlanStatusDataProvider
     */
    public function testSubscriptionPlanStatus($status, $function, $expected_boolean)
    {
        $subscription = new SubscriptionPlan($this->createMock(eCurringHttpClient::class));
        $subscription->status = $status;

        $this->assertEquals($expected_boolean, $subscription->{$function}());
    }

    public function subscriptionPlanStatusDataProvider()
    {
        return [
            [SubscriptionPlan::STATUS_ACTIVE, 'isActive', true],
            [SubscriptionPlan::STATUS_ACTIVE, 'isInActive', false],

            [SubscriptionPlan::STATUS_INACTIVE, 'isActive', false],
            [SubscriptionPlan::STATUS_INACTIVE, 'isInActive', true],
        ];
    }
}
