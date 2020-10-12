<?php

namespace Jalameta\Support\ElasticSearch\Contracts;

/**
 * Should Be Indexed Contract.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
interface ShouldBeIndexed
{
    const INTERVAL_NONE = 0;
    const INTERVAL_DAILY = 1;
    const INTERVAL_WEEKLY = 2;
    const INTERVAL_MONTHLY = 3;

    /**
     * Transform Object to Indexable array.
     *
     * @return array
     */
    public function toIndexArray();

    /**
     * Get Index Mapping.
     *
     * @return array
     */
    public function getIndexMapping();

    /**
     * Get Index Name.
     *
     * @return string
     */
    public function getIndexName();

    /**
     * Get Index ID.
     *
     * @return mixed
     */
    public function getIndexId();

    /**
     * Get index interval (granularity).
     *
     * @return mixed
     */
    public function getInterval();
}
