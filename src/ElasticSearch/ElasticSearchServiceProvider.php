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
        require_once __DIR__.DIRECTORY_SEPARATOR.'helpers.php';

        try {
            $this->app->singleton('jps.elasticsearch', function ($app) {
                $host = env('ELASTICSERVER_HOST', config('services.elasticsearch.host')).':'.env('ELASTICSERVER_PORT', config('services.elasticsearch.port'));

                $client = ClientBuilder::create()
                    ->setHosts([$host])
                    ->build();

                return $client;
            });

            $this->app->alias('jps.elasticsearch', Client::class);
        } catch (\Exception $e) {
            throw new ElasticSearchNotFound();
        }
    }
}
