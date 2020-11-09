<?php

namespace Glare\Support\Config;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Providers.
     *
     * @var array
     */
    // protected $providers = [
    //     RemoveUnwantedSegmentsFromUrl::class,
    //     OverrideGeneralConfiguration::class,
    // ];

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make(RemoveUnwantedSegmentsFromUrl::class)->run();
        $this->app->make(OverrideGeneralConfiguration::class)->run();
        $this->app->make(OverrideConfiguration::class)->run('site');
        $this->app->make(Mysql56::class)->run();
        // foreach ($this->providers as $provider) {
        //     $this->app->register($provider);
        // }
    }

    public function boot()
    {
        $this->app->make(CarbonSerialize::class)->run();
        $this->app->make(Paginator::class)->run();
    }
}
