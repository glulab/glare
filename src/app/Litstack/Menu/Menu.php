<?php

namespace Glare\Litstack\Menu;

use Facades\Glare\Support\Locator\Locator;

class Menu
{
    /**
     * [href description]
     *
     * @param [type] $item        [description]
     * @param [type] $defaultLang if null current lang is used, if false default lang is used
     *
     * @return [type] [description]
     */
    public function href($item, $defaultLang = null)
    {
        try {
            $link = Locator::url(!empty($item->url) ? $item->url : $item->route->trimmed() ?? '', $item->lang ?? $defaultLang);
        } catch(\Throwable $e) {
            $link = '/';
        }

        return $link;
    }
}
