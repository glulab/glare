<?php

namespace Glare\Litstack\View;

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
        // menu-main
        \Illuminate\Support\Facades\View::composer(['*menu-main'], function ($view) {
            $menu_main = \Ignite\Support\Facades\Form::load('menu', 'menu_main');
            $view->with('menu_main', $menu_main);
        });

        // menu-lang
        \Illuminate\Support\Facades\View::composer(['*menu-lang'], function ($view) {
            $menu_lang = \Ignite\Support\Facades\Form::load('menu', 'menu_lang');
            $view->with('menu_lang', $menu_lang);
        });

        // menu-offer
        \Illuminate\Support\Facades\View::composer(['*menu-offer'], function ($view) {
            $menu_offer = \Ignite\Support\Facades\Form::load('menu', 'menu_offer');
            $view->with('menu_offer', $menu_offer);
        });

        // menu-footer
        \Illuminate\Support\Facades\View::composer(['*menu-footer'], function ($view) {
            $menu_footer = \Ignite\Support\Facades\Form::load('menu', 'menu_footer');
            $view->with('menu_footer', $menu_footer);
        });

        // menu-system
        \Illuminate\Support\Facades\View::composer(['*menu-system'], function ($view) {
            $menu_system = \Ignite\Support\Facades\Form::load('menu', 'menu_system');
            $view->with('menu_system', $menu_system);
        });
    }
}
