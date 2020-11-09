<?php

namespace Glare\Litstack\View;

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
        // settngs, site
        $sharedViewValues = [];
        $sharedViewValues['settings'] = \Ignite\Support\Facades\Form::load('settings', 'settings');
        $sharedViewValues['context'] = \Ignite\Support\Facades\Form::load('settings', 'context');
        $sharedViewValues['site'] = \Ignite\Support\Facades\Form::load('settings', 'site');
        // TODO: Share app views
        \Illuminate\Support\Facades\View::share($sharedViewValues);

        // bind app('lit-shared')
        $this->app->singleton('lit-shared', function ($app) use ($sharedViewValues) {
            return $sharedViewValues;
        });
    }
}
