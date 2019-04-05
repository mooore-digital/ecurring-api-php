<?php

declare(strict_types=1);

namespace Marissen\eCurring\Resource;

class SubscriptionPlanCollection extends CursorCollection
{
    /**
     * @return AbstractResource
     */
    protected function createResourceObject()
    {
        return new SubscriptionPlan($this->client);
    }
}
