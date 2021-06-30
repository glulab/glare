<?php

namespace Glare\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GlareMakeDeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glare:make-deploy {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Glare Make Deploy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // $this->publishPath = __DIR__ . '/../../../publish';
        // $this->envFilePath = base_path('.env');
        // $this->gitignoreFilePath = base_path('.gitignore');
        // $this->packageJsonFilePath = base_path('package.json');
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

        $this->force = $this->option('force');
        // $this->overwrite = $this->option('overwrite');
        // $this->skipEnvInit = $this->option('skip-env-init');

        // $this->currentDateTime = date("YmdHis");

        // $this->cleanGitignore();

        $this->banner();

        // $this->backupFile($this->gitignoreFilePath);
        // $this->appendToGitignore();

        // $this->backupFile($this->envFilePath);
        // $this->appendToEnv();
        // $this->initEnv();

        // $this->npmInstall();

        $this->cleanup();

        $this->makeDeploy();
    }

    public function banner()
    {
        $this->info('');
        $this->info('####################################################################################################');
        $this->info('### GLARE MAKE DEPLOY                                                                            ###');
        $this->info('####################################################################################################');
        $this->info('');
    }

    public function cleanup()
    {
        $this->call('glare:cleanup', [
            '--force' => true,
        ]);
    }

    public function makeDeploy($force = false)
    {
        $exclude = [
            '.git',
            'node_modules',
        ];

        $devName = config('glare.dev.name');

        $rootPath = base_path();
        $zipFile = base_path('../www.zip');
        $zipFileRealPath = realpath($zipFile);
        if (is_file($zipFileRealPath) && file_exists($zipFileRealPath)) {
            @unlink($zipFileRealPath);
        }

        $zip = new \ZipArchive();
        $zip->open(base_path('../www.zip'), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($rootPath), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $name => $file)
        {
            foreach ($exclude as $e) {


                if($file->isDir() && basename($file->getRealPath()) === $e) {
                    $this->info('Exclude: ' . $file->getRealPath());
                    continue 2;
                }

                if (stripos($file->getRealPath(), $e . DIRECTORY_SEPARATOR) !== false) {
                    $this->info('Exclude: ' . $file->getRealPath());
                    continue 2;
                }
            }

            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // if we meet symlinked vendor/glulab/glare
                $filePathFixedSlashes = str_replace('\\', '/', $filePath);
                if (stripos($filePathFixedSlashes, $devName) !== false) {
                    $devPath = substr($filePathFixedSlashes, stripos($filePathFixedSlashes, $devName));
                    $relativePath = 'vendor/' . $devPath;
                }

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
                $this->info('Add: ' . $relativePath);

            } else {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // if we meet symlinked vendor/glulab/glare
                $filePathFixedSlashes = str_replace('\\', '/', $filePath);
                if (stripos($filePathFixedSlashes, $devName) !== false) {
                    $devPath = substr($filePathFixedSlashes, stripos($filePathFixedSlashes, $devName));
                    $relativePath = 'vendor/' . $devPath;
                }

                $zip->addEmptyDir($relativePath);
                $this->info('Add empty dir: ' . $relativePath);
            }
        }

        $zip->close();
    }
}
