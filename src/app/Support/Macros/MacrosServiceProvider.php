<?php

namespace Glare\Support\Macros;

use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    /**
     * Macros.
     *
     * @var array
     */
    protected $macros = [
        //
    ];

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMacros();
    }

    /**
     * Register macros.
     *
     * @return void
     */
    public function registerMacros()
    {
        foreach ($this->macros as $macro) {
            $instance = $this->app()->make($macro);

            if (method_exists($instance, 'register')) {
                $instance->register();
            }
        }
    }
}
