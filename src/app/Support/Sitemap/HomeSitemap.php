<?php

namespace Glare\Support\Sitemap;

use Illuminate\Support\Carbon;

class HomeSitemap {

    public function __construct()
    {
        //
    }

    public function generate()
    {
        $urls = [];

        $home = [
            'loc' => url('/'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ];
        $urls[] = $home;

        return $urls;
    }

}
