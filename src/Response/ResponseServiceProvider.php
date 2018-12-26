<?php

namespace Jalameta\Support\Response;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Response Service Provider
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class ResponseServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/responses.php' => base_path('config/responses.php'),
            ], 'jps-response-config');
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/responses.php', 'responses'
        );
    }
}
