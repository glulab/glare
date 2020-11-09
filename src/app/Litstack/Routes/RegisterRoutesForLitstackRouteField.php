<?php

namespace Glare\Litstack\Routes;

use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Foundation\Application;

class RegisterRoutesForLitstackRouteField
{

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        \Ignite\Crud\Fields\Route::register('routes-for-menu', function($collection) {

            $collection->group('Strona Główna', 'home', function($group) {
                $group->route('Strona Główna', 'home', function() {
                    return route('home');
                });
            });

            foreach (config('site.litstack.routes-for-menu') as $type => $typeConfig) {
                $collection->group(trans("model-page.types.$type"), $type, function($group) use ($type, $typeConfig) {
                    $items = \App\Models\Page::whereType($typeConfig['type'])->get();

                    foreach($items as $item) {
                        $group->route($item->title, $item->slug, function() use ($item, $type, $typeConfig) {
                            return route($typeConfig['route'], $item->slug);
                        });
                    }
                });
            }

        });

        // \Ignite\Crud\Fields\Route::register('app', function ($collection) {
        //     \FjordPages\Models\FjordPage::collection('page')->get()->addToRouteCollection('Page', $collection);
        // });
    }
}
