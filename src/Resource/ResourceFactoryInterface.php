<?php

namespace Marissen\eCurring\Resource;

interface ResourceFactoryInterface
{
    public function createFromApiResult($apiResult, AbstractResource $resource);
}