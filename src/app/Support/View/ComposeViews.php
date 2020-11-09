<?php

namespace Glare\Support\View;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;

class ComposeViews
{

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        //
    }
}
