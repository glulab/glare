<?php

namespace Glare\Support;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
     * Register aliases.
     *
     * @return void
     */
    protected function alias($overload = false, $search = 'Glare', $replace = 'App')
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->aliases as $alias => $class) {

            if ($overload) {
                $searchWithFacade = ["Facades\\$search" , $search];
                $classInAppNamespace = str_replace($searchWithFacade, $replace, $class);
                if (class_exists($classInAppNamespace)) {
                    $class = str_replace($search, $replace, $class);
                }
            }

            $loader->alias($alias, $class);
        }
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
