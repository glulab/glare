<?php

namespace Glare\Providers;

use Illuminate\Support\ServiceProvider;

class GlareServiceProvider extends ServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->providers();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register providers.
     *
     * @return void
     */
    protected function providers()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}
