<?php

namespace Jalameta\Support\ElasticSearch\Contracts;

/**
 * Should Be Indexed Contract.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
interface ShouldBeIndexed
{
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
}
