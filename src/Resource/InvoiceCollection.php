<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

class InvoiceCollection extends CursorCollection
{
    /**
     * @return AbstractResource
     */
    protected function createResourceObject()
    {
        return new Invoice($this->client);
    }
}
