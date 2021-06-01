<?php

namespace Glare\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GlareInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glare:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Glare Install';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->gitignoreFilePath = base_path('.gitignore');
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

        $this->cleanGitignore();
    }

    public function cleanGitignore()
    {
        $sliceKey = null;
        $deleteMarker = config('glare.delete-marker');
        $file = file($this->gitignoreFilePath);
        foreach ($file as $key => $line) {
            if (strpos($line, $deleteMarker) !== false) {
                $sliceKey = $key;
            }
        }
        if (!is_null($sliceKey)) {
            $file = array_slice($file, 0, $sliceKey);
        }
        file_put_contents($this->gitignoreFilePath, implode('', $file));
    }
}
