<?php

namespace Glare\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GlareCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glare:cleanup {--p|public} {--compile} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Glare Cleanup';

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

        $compile = $this->option('compile');
        $force = $this->option('force');

        $cleanupCommands = [
            'view:clear' => [],
            'cache:clear' => [],
            'route:clear' => [],
            'config:clear' => [],
            'clear-compiled' => [],
            'auth:clear-resets' => [],
            'debugbar:clear' => [],
            'media-library:clean' => ['--force' => $force],
            'logClear' => [],
            'emptyStorageDir' => ['media-library/temp'],
        ];

        $compileCommands = [
            'config:cache' => [],
            'route:cache' => [],
            'view:cache' => [],
            'event:cache' => [],
        ];

        foreach ($cleanupCommands as $cmd => $params) {
            $this->executeCommand($cmd, $params);
        }

        if (!$compile) {
            return;
        }

        foreach ($compileCommands as $cmd => $params) {
            $this->executeCommand($cmd, $params);
        }

        return;

        // if ($this->option('public')) {
        //     $this->cleanupPublic();
        // }
    }

    public function executeCommand($cmd, $params)
    {
        $this->separator('=');
        if (method_exists($this, $cmd)) {
            $this->info($cmd . ' method');
            $this->separator('-');
            try {
                $res = call_user_func_array([$this, $cmd], $params);
                $this->info($res);
            } catch (\Throwable $e) {
                $this->info($e);
            }
        } elseif (array_key_exists($cmd, \Artisan::all())) {
            $this->info($cmd . ' command');
            $this->separator('-');
            try {
                $this->call($cmd, $params);
            } catch (\Throwable $e) {
                $this->info($e);
            }
        } else {
            $this->info($cmd . ' - no command or method');
            $this->separator('-');
        }
        $this->separator('=');
        $this->separator();
    }

    public function separator($char = null)
    {
        if (is_null($char)) {
            return $this->info('');
        }
        $l = '';
        for ($i = 1; $i < 120; $i++) {
            $l = $l . $char;
        }
        return $this->info($l);
    }

    public function logClear()
    {
        $files = glob(storage_path('logs') . '/*.log');
        foreach ($files as $file) {
            @unlink($file);
        }
        return true;
    }

    public function emptyStorageDir($pathInStorage = '')
    {
        $storageToCleanPath = storage_path($pathInStorage);
        return \DiskHelper::deleteDirectory($storageToCleanPath, false);
        // $allFiles = glob($storageToCleanPath . '/**/**/*/*');
        // dd($allFiles);
        // $disk = Storage::disk('base');
        // $files = $disk->files("storage/$pathInStorage");
        // $files = array_where($files, function ($value, $key) {
        //     return !ends_with($value, '.gitignore');
        // });
        // $files = array_values($files);
        // $res = $disk->delete($files);
        // if ($res) {
        //     $this->info($pathInStorage . ' removed!');
        // } else {
        //     $this->error('Could not remove ' . $pathInStorage . '!');
        // }
    }

    public function cleanupPublic()
    {
        $disk = Storage::disk('base');

        if ($disk->deleteDirectory('public/assets')) {
            $this->info('public/assets' . ' removed!');
        }

        if ($disk->deleteDirectory('public/assets-admin')) {
            $this->info('public/assets-admin' . ' removed!');
        }

        if ($disk->deleteDirectory('public/dbimgs_')) {
            $this->info('public/dbimgs_' . ' removed!');
        }
    }
}
