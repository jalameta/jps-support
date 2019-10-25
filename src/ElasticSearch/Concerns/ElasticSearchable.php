<?php

namespace Jalameta\Support\ElasticSearch\Concerns;

use Jalameta\Support\ElasticSearch\IndexAnItem;

/**
 * Should be indexed trait, should be used on Eloquent Object.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
trait ElasticSearchable
{
    /**
     * Boot trait.
     */
    public static function bootElasticSearchable()
    {
        static::saved(function ($item) {
            if (env('APP_ENV') !== 'testing' and env('APP_ENV') !== 'test') {
                dispatch(new IndexAnItem($item->getIndexName(), $item->getIndexId(), $item->toIndexArray(), $item->getIndexMapping()));
            }
        });
    }

    /**
     * Get Index Name.
     *
     * @return string
     */
    public function getIndexName()
    {
        if (property_exists($this, 'index_name')) {
            return $this->index_name;
        }

        return empty(config('services.elasticsearch.prefix'))
            ? $this->table
            : config('services.elasticsearch.prefix').'_'.$this->table;
    }

    /**
     * Transform Object to Indexable array.
     *
     * @return array
     */
    public function toIndexArray()
    {
        return $this->toArray();
    }

    /**
     * Get Index Mapping.
     *
     * @return array
     */
    public function getIndexMapping()
    {
        return [];
    }

    /**
     * Get Index ID.
     *
     * @return mixed
     */
    public function getIndexId()
    {
        return $this->{$this->getKeyName()};
    }
}
