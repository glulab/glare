<?php

namespace Glare\Support\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;
// use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class Mysql56 /*extends BaseServiceProvider*/
{
    /**
     * Register.
     *
     * @return void
     */
    // public function register()
    // {
    //     $this->removeUnwantedSegmentsFromUrl();
    // }

    // *
    //  * Boot.
    //  *
    //  * @return void
     
    // public function boot()
    // {
    //     //
    // }

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * [removeUnwantedSegmentsFromUrl description]
     *
     * @return [type] [description]
     */
    public function run()
    {
        if ($this->app['config']['glare.mysql56'] === true) {
            \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        }
    }
}
