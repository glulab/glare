<?php

namespace Glare\Litstack\Routes;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
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
    
    public function boot()
    {
        $this->app->make(RegisterRoutesForLitstackRouteField::class)->run();
    }
}
