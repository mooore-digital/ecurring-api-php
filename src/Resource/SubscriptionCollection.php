<?php

declare(strict_types=1);

namespace Marissen\eCurring\Resource;

class SubscriptionCollection extends CursorCollection
{
    /**
     * @return AbstractResource
     */
    protected function createResourceObject()
    {
        return new Subscription($this->client);
    }
}
