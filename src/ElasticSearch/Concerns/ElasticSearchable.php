<?php

namespace Jalameta\Support\ElasticSearch\Concerns;

use Illuminate\Support\Carbon;
use Jalameta\Support\ElasticSearch\IndexAnItem;
use Jalameta\Support\ElasticSearch\Contracts\ShouldBeIndexed;

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
            if (env('APP_ENV') !== 'testing' && env('APP_ENV') !== 'test' && config('services.elasticsearch.enable', false)) {
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
            return $this->resolveIndexName($this->index_name);
        }

        return empty(config('services.elasticsearch.prefix'))
            ? $this->resolveIndexName($this->table)
            : config('services.elasticsearch.prefix').'_'.$this->resolveIndexName($this->table);
    }

    /**
     * Resolve name with granularity/interval.
     * 
     * @param $name
     *
     * @return string
     */
    public function resolveIndexName($name)
    {
        switch ($this->getInterval()) {
            case ShouldBeIndexed::INTERVAL_MONTHLY;
                $name = $name.'_'.Carbon::now()->format('Y-m');
                break;
            case ShouldBeIndexed::INTERVAL_WEEKLY;
                $name = $name.'_'.Carbon::now()->startOfWeek()->format('Y-m-d');
                break;
            case ShouldBeIndexed::INTERVAL_DAILY:
                $name = $name.'_'.Carbon::now()->format('Y-m-d');
                break;
            case ShouldBeIndexed::INTERVAL_NONE:
            default:
                // do nothing
                break;
        }

        return $name;
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

    /**
     * Default interval (granularity).
     *
     * @return int
     */
    public function getInterval()
    {
        return ShouldBeIndexed::INTERVAL_NONE;
    }
}
