<?php

namespace Glare\Support\View;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;

class ShareViews
{

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        $sharedViewValues = [];
        $sharedViewValues['layout'] = $this->shareLayoutViews();

        \Illuminate\Support\Facades\View::share($sharedViewValues);

        // bind app('site')
        // $this->app->singleton('site', function ($app) use ($sharedViewValues) {
        //     return $sharedViewValues;
        // });
    }

    public function shareLayoutViews()
    {
        $l = [];
        $l['body_classes'] = $this->bodyClasses();
        $l['is_home'] = strpos(optional(Request::route())->getName(), 'home') !== false ? true : false;
        return $l;
    }

    public function bodyClasses()
    {
        $classFromPath = request()->path() === '/' ? 'index' :  'no-index ' . str_replace(['/', '.html'], ['-', ''], request()->path());
        $classFromRoute = str_replace('.', '-', optional(request()->route())->getName() ?? 'no-route');

        return $classFromPath . ' ' . $classFromRoute;
    }
}
