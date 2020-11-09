<?php

namespace Glare\Support\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;
// use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class RemoveUnwantedSegmentsFromUrl /*extends BaseServiceProvider*/
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
        if (!isset($_SERVER['REQUEST_URI'])) {
            return;
        }

        if (Request::method() !== 'GET') {
            return;
        }

        $unwanted = [
            '/public/' . config('app.locale'),
            '/public/' . config('app.fallback_locale'),
            '/public',
            // '/' . config('apx.lang_default'),
        ];

        foreach ($unwanted as $u) {
            if (substr($_SERVER['REQUEST_URI'], 0, strlen($u)) === $u) {
                // dump(str_replace($u, '', Request::fullUrl()));
                // return redirect(str_replace($u, '', Request::fullUrl()));
                header('Location: ' . str_replace($u, '', Request::fullUrl()));
                exit;
            }
        }
    }
}
