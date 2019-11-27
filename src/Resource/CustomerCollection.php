<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

class CustomerCollection extends CursorCollection
{
    /**
     * @return AbstractResource
     */
    protected function createResourceObject()
    {
        return new Customer($this->client);
    }
}
