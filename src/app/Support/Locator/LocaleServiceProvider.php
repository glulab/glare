<?php

namespace Glare\Support\Locator;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Facades\Glare\Support\Locator\Locator;

class LocaleServiceProvider extends ServiceProvider
{
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
        /**
         * This is not needed anymore if using prefixed routes
         */
        if(Request::filled('set-lang')) {
            Locator::setLangOrDefault(Request::get('set-lang'));
        }
    }
}
