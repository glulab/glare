<?php

namespace Glare\Support\Config;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;

class CarbonSerialize
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
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('Y-m-d H:i:s');
        });
    }
}
