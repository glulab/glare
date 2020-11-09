<?php

namespace Glare\Listeners;

use Logger;
use Glare\Events\PageHasBeenSaved;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSitemap implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(PageHasBeenSaved $event)
    {
        // $event->page->title;
        Logger::setPrefix('CreateSitemap Listener: ');
        Logger::logStart('CreateSitemap');
        Artisan::call('glare:sitemap');
        Logger::logEnd('CreateSitemap');
    }
}
