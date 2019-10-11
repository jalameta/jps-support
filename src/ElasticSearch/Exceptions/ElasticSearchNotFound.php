<?php

namespace Jalameta\Support\ElasticSearch\Exceptions;

use Exception;
use Throwable;

/**
 * ElasticSearch search not found.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class ElasticSearchNotFound extends Exception
{
    public function __construct($message = 'ElasticSearch client not found, install the library manually!', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
