<?php

namespace Jalameta\Support\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Jalameta\Support\ElasticSearch\Exceptions\ElasticSearchNotFound;

/**
 * Elastic Search service provider.
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws \Jalameta\Support\ElasticSearch\Exceptions\ElasticSearchNotFound
     */
    public function boot()
    {
        require_once __DIR__.'helpers.php';

        try {
            $this->app->singleton('jps.elasticsearch', function ($app) {
                $client = ClientBuilder::create()
                    ->setHosts([env('ES_HOST', 'http://10.10.10.100:8081')])
                    ->build();

                return $client;
            });

            $this->app->alias('jps.elasticsearch', Client::class);
        } catch (\Exception $e) {
            throw new ElasticSearchNotFound();
        }
    }
}
