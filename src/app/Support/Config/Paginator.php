<?php

namespace Glare\Support\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;

class Paginator
{
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
        \Illuminate\Pagination\Paginator::useBootstrap();
    }
}
