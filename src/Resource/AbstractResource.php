<?php

declare(strict_types=1);

namespace Marissen\eCurring\Resource;

use Marissen\eCurring\eCurringHttpClient;

abstract class AbstractResource
{
    /**
     * @var eCurringHttpClient
     */
    protected $client;
    /**
     * @var array
     */
    protected $exportProperties = [];
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $type;
    /**
     * @var \DateTime
     */
    public $created_at;
    /**
     * @var \DateTime
     */
    public $updated_at;

    public function __construct(eCurringHttpClient $client)
    {
        $this->client = $client;
    }

    public function toArray(): array
    {
        return array_filter((array) $this, [$this, 'isPropertyExportable'], ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param mixed $value
     * @param mixed $property
     * @return bool
     */
    public function isPropertyExportable($value, $property): bool
    {
        return in_array($property, $this->exportProperties);
    }
}
