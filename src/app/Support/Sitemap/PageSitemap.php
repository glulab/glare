<?php

namespace Glare\Support\Sitemap;

use Illuminate\Support\Carbon;

class PageSitemap {

    public function __construct()
    {
        //
    }

    public function generate()
    {
        $urls = [];

        $pages = \App\Models\Page::whereActive(true)->whereSitemap(true)->orderBy('type', 'asc')->select(['id', 'title', 'type', 'slug', 'sitemap', 'sitemap_changefreq', 'sitemap_priority', 'updated_at'])->get();

        foreach ($pages as $page) :
            $url = [];
            $url['loc'] = $page->urlByType ?? '';
            $url['lastmod'] = optional($page->updated_at)->toAtomString() ?? '';
            $url['changefreq'] = $page->sitemap_changefreq ?? '';
            $url['priority'] = $page->sitemap_priority ?? '';
            $urls[] = $url;
        endforeach;

        return $urls;
    }

}
