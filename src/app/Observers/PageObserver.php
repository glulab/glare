<?php

namespace Glare\Observers;

use App\Models\Page;
use Glare\Events\PageHasBeenSaved;

class PageObserver
{
    public function __construct()
    {
        //
    }

    /**
     * [saved description]
     *
     * @param Page $model [description]
     *
     * @return [type] [description]
     */
    public function saved(Page $page)
    {
        PageHasBeenSaved::dispatch($page);
    }
}
