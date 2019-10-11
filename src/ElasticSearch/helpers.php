<?php

if (! function_exists('es')) {
    /**
     * ElasticSearch client.
     *
     * @return \Elasticsearch\Client
     */
    function es()
    {
        return app('jps.elasticsearch');
    }
}
