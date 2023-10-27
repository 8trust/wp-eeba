<?php

namespace Inavii\Instagram\Services\Instagram;

use Throwable;

class MessageNotProvidedException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}