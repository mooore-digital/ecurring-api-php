<?php

namespace Marissen\eCurring\Exception;

use \Exception;
use \Throwable;

class PhpVersionException extends Exception
{
    public function __construct(string $requiredVersion, $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Unsupported PHP version: %s, required: %s', phpversion(), $requiredVersion), 
            $code, 
            $previous
        );
    }
}
