<?php

namespace Jalameta\Support\ElasticSearch\Exceptions;

use Exception;

/**
 * ElasticSearch Host unreachable.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class ElasticSearchHostUnreachable extends Exception
{
    public $index;

    public $data;

    public function __construct($index, $data)
    {
        parent::$this->__construct('Cannot established to elasticsearch host: '.config('services.elasticsearch.host', 'unknown').':'.config('services.elasticsearch.port'));

        $this->index = $index;
        $this->data = $data;
    }
}
