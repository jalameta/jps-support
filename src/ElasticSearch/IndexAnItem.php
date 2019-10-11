<?php

namespace Jalameta\Support\ElasticSearch;

use Jalameta\Support\Bus\BaseJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jalameta\Support\ElasticSearch\Exceptions\ElasticSearchHostUnreachable;

/**
 * Index an Item.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class IndexAnItem extends BaseJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Index name.
     *
     * @var string
     */
    public $index;

    /**
     * Index ID.
     *
     * @var mixed
     */
    public $id;

    /**
     * Attributes mapping.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/indices-put-mapping.html
     * @var array
     */
    public $mapping;

    /**
     * Indexed data.
     *
     * @var array
     */
    public $data;

    /**
     * IndexAnItem constructor.
     *
     * @param       $index
     * @param null  $id
     * @param array $data
     * @param array $mapping
     */
    public function __construct($index, $id = null, array $data = [], array $mapping)
    {
        parent::__construct([]);
        $this->index = $index;
        $this->id = $id;
        $this->data = $data;
        $this->mapping = $mapping;
    }

    /**
     * Run the actual command process.
     *
     * @return bool
     * @throws \Jalameta\Support\ElasticSearch\Exceptions\ElasticSearchHostUnreachable
     */
    public function run(): bool
    {
        $data = [
            'name' => $this->name,
            'body' => array_merge($this->data, ['mapping' => $this->mapping]),
        ];

        if (! empty($this->id)) {
            $data['id'] = $this->id;
        }

        try {
            es()->index($data);
        } catch (\Exception $e) {
            throw new ElasticSearchHostUnreachable($this->index, $this->data);
        }

        return true;
    }
}
