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
        $siteRoutes = config('site.litstack.site-routes');

        \Ignite\Crud\Fields\Route::register('site-routes', function($collection) use ($siteRoutes) {

            $collection->group('Strona Główna', 'home', function($group) {
                $group->route('Strona Główna', 'home', function() {
                    return route('home');
                });
            });

            foreach ($siteRoutes as $elementName => $elementRouteConfig) {
                $items = \DB::table($elementRouteConfig['db-table'])->select(['id', 'title', 'slug', 'type'])->get();
                $items = $items->groupBy('type');

                foreach ($elementRouteConfig['types'] as $type => $typeConfig) {
                    $collection->group(trans("{$elementRouteConfig['types-translations']}.types.$type"), $type, function($group) use ($type, $typeConfig, $items) {
                        if (!empty($items[$typeConfig['type']])) {
                            foreach($items[$typeConfig['type']] as $item) {
                                $group->route($item->title, $item->id, function() use ($item, $type, $typeConfig) {
                                    return route($typeConfig['route'], $item->slug); // route used in fronend
                                });
                            }
                        }
                    });
                }
            }
        });
    }
}
