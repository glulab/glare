<?php

namespace Glare\Console\Commands;

use Illuminate\Console\Command;

class GlareSwitchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glare:switch {mode} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Glare Switch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->robotsFilePath = public_path('robots.txt');
        $this->composerFilePath = base_path('composer.json');
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

        $mode = $this->argument('mode');
        $force = $this->option('force');

        // $method = 'mode';
        // if (!method_exists($this, $method)) {
        //     $this->info('There is no method: ' . $method . '!');
        //     return false;
        // }
        // $res = call_user_func_array([$this, $method], ['mode' => $mode]);

        $res = '';

        $res .= $this->switchRobots($mode);
        $res .= $this->switchComposer($mode);

        $this->info($res);
    }

    public function switchRobots($mode)
    {
        $lines = [];
        $lines[0] = 'User-agent: *';
        $lines[1] = 'Disallow:';
        $lines[2] = '';
        $lines[3] = 'Sitemap: ' . config('app.url') . '/sitemap.xml';
        $lines[4] = '';
        $lines[5] = '';

        if ($mode === 'dev' || $mode === 'vcs') {
            $lines[1] = 'Disallow: /';
        }

        file_put_contents($this->robotsFilePath, implode(PHP_EOL, $lines));
    }

    public function getComposerFile()
    {
        $this->composerFileJson = file_get_contents($this->composerFilePath);
        $this->composerFile = json_decode($this->composerFileJson);
        return true;
    }

    public function putComposerFile()
    {
        $this->composerFileJson = json_encode($this->composerFile, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return file_put_contents($this->composerFilePath, $this->composerFileJson);
    }

    public function switchComposer($mode)
    {
        // dev prod vcs

        $this->getComposerFile();

        $repoName = config('glare.dev.name');
        $repoUrl = config('glare.dev.url');
        $repoDevSuffix = config('glare.dev.suffix');

        $repoType = "path";
        if ($mode === 'vcs') {
            $repoType = "vcs";
        }

        // REQUIRE
        if (!empty($this->composerFile->require->$repoName)) {
            if ($mode === 'prod' || $mode === 'vcs') {
                // remove suffix from require
                $this->composerFile->require->$repoName = str_replace($repoDevSuffix, '', $this->composerFile->require->$repoName);
            } else {
                // add suffix to require
                if (strpos($this->composerFile->require->$repoName, $repoDevSuffix) === false) {
                    $this->composerFile->require->$repoName .= $repoDevSuffix;
                }
            }
        }

        // REPOSITORIES
        if ($mode === 'prod') {

            // if no repositories key return
            if (!isset($this->composerFile->repositories)) {
                return $this->putComposerFile();
            }

            // remove repo entry if exists
            foreach ($this->composerFile->repositories as $key => $r) {
                if (strpos($r->url, $repoName) !== false) {
                    unset($this->composerFile->repositories[$key]);
                }
            }
            // reindex array
            $this->composerFile->repositories = array_values($this->composerFile->repositories);

            // if empty remove
            if (is_array($this->composerFile->repositories) && count($this->composerFile->repositories) === 0) {
                unset($this->composerFile->repositories);
            }

            return $this->putComposerFile();
        }

        // other modes
        // if repositiories not exists
        if (!isset($this->composerFile->repositories)) {
            $this->composerFile->repositories = [];
        }

        // if repositories key has glare repo
        $presentInRepositories = false;
        foreach ($this->composerFile->repositories as &$r) {
            if (strpos($r->url, $repoName) !== false) {
                $r->type = $repoType;
                $r->url = $repoUrl;
                $presentInRepositories = true;
            }
        }

        if ($presentInRepositories === false) {
            // repo
            $repo =  new \stdClass();
            $repo->type = $repoType;
            $repo->url = $repoUrl;
            $this->composerFile->repositories[] = $repo;
        }

        return $this->putComposerFile();
    }
}
