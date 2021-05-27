<?php

namespace Glare\Support\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;
// use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class OverrideConfiguration /*extends BaseServiceProvider*/
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
    public function run($oc = null, $ock = 'config-overrides')
    {
        if (is_null($oc)) {
            return;
        }

        $co = $this->app['config']->get("$oc.$ock");

        if (empty($co)) {
            return;
        }

        foreach ($co as $key => $cfg) {

            $c = $this->app['config']->get($key);

            $c = array_replace_recursive($c, $cfg);

            $this->app['config']->set($key, $c);

        }
    }
}
