<?php

declare(strict_types=1);

namespace Marissen\eCurring\Resource;

use ArrayObject;

abstract class Collection extends ArrayObject
{
    /**
     * @var int
     */
    public $count;
    /**
     * @var object
     */
    protected $links;

    /**
     * AbstractCollection constructor.
     * @param int $count
     * @param object $links
     */
    public function __construct(int $count, object $links)
    {
        parent::__construct();
        $this->count = $count;
        $this->links = $links;
    }
}
