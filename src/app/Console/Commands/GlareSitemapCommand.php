<?php

namespace Glare\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GlareSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glare:sitemap {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Glare Sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $this->info('Display this on the screen');
        // $this->error('Something went wrong!');
        // $this->line('Display this on the screen');

        $force = $this->option('force');

        $sitemapPath = public_path('sitemap.xml');

        if ($force) {
            @unlink($sitemapPath);
        }

        $create = true;

        if (is_file($sitemapPath) && file_exists($sitemapPath)) {

            $filetimeOfCurrentSitemap = Carbon::createFromTimestamp(filemtime($sitemapPath));

            $now = Carbon::now();

            $diffInMinutes = $now->diffInMinutes($filetimeOfCurrentSitemap);

            if ($diffInMinutes < 30) {
                $create = false;
            }
        }

        if ($create === false) {
            return;
        }

        $urls = [];

        foreach ((array) config('site.sitemap-generators') as $sg) {
            $urls = array_merge($urls, app()->make($sg)->generate());
        }

        $v = view('glare::sitemap.sitemap')->with('urls', $urls)->render();

        @file_put_contents($sitemapPath, $v);
    }
}
