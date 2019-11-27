<?php

namespace Mooore\eCurring\Resource;

interface ResourceFactoryInterface
{
    public function createFromApiResult($apiResult, AbstractResource $resource);
}