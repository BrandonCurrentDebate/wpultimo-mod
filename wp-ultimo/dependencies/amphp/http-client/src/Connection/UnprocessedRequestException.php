<?php

namespace WP_Ultimo\Dependencies\Amp\Http\Client\Connection;

use WP_Ultimo\Dependencies\Amp\Http\Client\HttpException;
final class UnprocessedRequestException extends HttpException
{
    public function __construct(HttpException $previous)
    {
        parent::__construct("The request was not processed and can be safely retried", 0, $previous);
    }
}
