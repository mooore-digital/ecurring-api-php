<?php

declare(strict_types=1);

namespace Marissen\eCurring\Resource;

use Marissen\eCurring\eCurringHttpClient;
use Marissen\eCurring\Exception\ApiException;

abstract class CursorCollection extends Collection
{
    /**
     * @var eCurringHttpClient
     */
    protected $client;
    /**
     * @var ResourceFactoryInterface
     */
    protected $resourceFactory;

    /**
     * @param eCurringHttpClient $client
     * @param ResourceFactoryInterface $resourceFactory
     * @param int $count
     * @param object $links
     */
    final public function __construct(
        eCurringHttpClient $client,
        ResourceFactoryInterface $resourceFactory,
        int $count,
        object $links
    ) {
        parent::__construct($count, $links);
        $this->client = $client;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @return AbstractResource
     */
    abstract protected function createResourceObject();

    /**
     * @return CursorCollection|null
     * @throws ApiException
     */
    final public function next()
    {
        if (!$this->hasNext()) {
            return null;
        }

        $result = $this->client->performHttpCallToUrl('GET', $this->links->next);
        $collection = new static($this->client, $this->resourceFactory, $result->meta->total, $result->links);

        foreach ($result->data as $data) {
            $collection[] = $this->resourceFactory->createFromApiResult($data, $this->createResourceObject());
        }

        return $collection;
    }

    /**
     * @return CursorCollection|null
     * @throws ApiException
     */
    final public function previous()
    {
        if (!$this->hasPrevious()) {
            return null;
        }

        $result = $this->client->performHttpCallToUrl('GET', $this->links->prev);
        $collection = new static($this->client, $this->resourceFactory, $result->meta->total, $result->links);

        foreach ($result->data as $data) {
            $collection[] = $this->resourceFactory->createFromApiResult($data, $this->createResourceObject());
        }

        return $collection;
    }

    public function hasNext(): bool
    {
        return isset($this->links->next);
    }

    public function hasPrevious(): bool
    {
        return isset($this->links->prev);
    }
}
