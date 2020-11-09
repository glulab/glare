<?php

namespace Glare\Support\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;
// use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class OverrideGeneralConfiguration /*extends BaseServiceProvider*/
{
    // /**
    //  * Register.
    //  *
    //  * @return void
    //  */
    // public function register()
    // {
    //     $this->overrideGeneralConfiguration();
    // }

    // /**
    //  * Boot.
    //  *
    //  * @return void
    //  */
    // public function boot()
    // {
    //     //
    // }
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * [overrideGeneralConfiguration description]
     *
     * vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/LoadConfiguration.php bootstrap()
     *
     * @return [type] [description]
     */
    public function run()
    {
        date_default_timezone_set($this->app['config']->get('app.timezone', 'UTC'));

        mb_internal_encoding('UTF-8');
    }
}
