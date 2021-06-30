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
    protected $signature = 'glare:install {--force} {--overwrite} {--skip-env-init}';

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

        $this->publishPath = __DIR__ . '/../../../publish';
        $this->envFilePath = base_path('.env');
        $this->gitignoreFilePath = base_path('.gitignore');
        $this->packageJsonFilePath = base_path('package.json');
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
        $this->overwrite = $this->option('overwrite');
        $this->skipEnvInit = $this->option('skip-env-init');

        $this->currentDateTime = date("YmdHis");

        // $this->cleanGitignore();

        $this->banner();

        $this->backupFile($this->gitignoreFilePath);
        $this->appendToGitignore();

        $this->backupFile($this->envFilePath);
        $this->appendToEnv();
        $this->initEnv();

        $this->npmInstall();

        $this->cronInstall();
    }

    public function banner()
    {
        $this->info('');
        $this->info('####################################################################################################');
        $this->info('### GLARE INSTALLER                                                                              ###');
        $this->info('####################################################################################################');
        $this->info('');
    }

    public function appendToGitignore($force = false)
    {
        if ($this->force === false) {
            if (!$this->confirm('Append to .gitignore file?')) {
                return true;
            }
        }

        $this->info('');
        $this->info('====================================================================================================');
        $this->info('=== Append to .gitignore file!                                                                   ===');
        $this->info('====================================================================================================');

        $ccMarker = config('glare.custom-content.marker');
        $ccInfo = config('glare.custom-content.info');
        $ccWarning = config('glare.custom-content.warning');
        $comment = trim('# ' . $ccMarker . ' ' . $ccInfo . ' ' . $ccWarning . ' ' . $ccMarker);

        $fileContent = file_get_contents($this->gitignoreFilePath);

        if ($this->overwrite === false && strpos($fileContent, $ccMarker) !== false) {
            $this->info('File .gitignore has already been edited! Call --overwrite to overwrite!');
            return true;
        }

        $this->removeAppendFromFile($this->gitignoreFilePath);

        $fileContent = file_get_contents($this->gitignoreFilePath);

        try {
            $appendFileContent = file_get_contents($this->publishPath . '/.gitignore');
        } catch (\Exception $e) {
            $appendFileContent = '';
        }

        if (empty($appendFileContent)) {
            return false;
        }

        $fileContent .= $comment . "\n" . $appendFileContent. "\n";
        file_put_contents($this->gitignoreFilePath, $fileContent);
    }

    public function appendToEnv($force = false)
    {
        if ($this->force === false) {
            if (!$this->confirm('Append to .env file?')) {
                return true;
            }
        }

        $this->info('');
        $this->info('====================================================================================================');
        $this->info('=== Append to .env file!                                                                         ===');
        $this->info('====================================================================================================');

        $ccMarker = config('glare.custom-content.marker');
        $ccInfo = config('glare.custom-content.info');
        $ccWarning = config('glare.custom-content.warning');
        $comment = trim('# ' . $ccMarker . ' ' . $ccInfo . ' ' . $ccWarning . ' ' . $ccMarker);

        $fileContent = file_get_contents($this->envFilePath);

        if ($this->overwrite === false && strpos($fileContent, $ccMarker) !== false) {
            $this->info('File .env has already been edited! Call --overwrite to overwrite!');
            return true;
        }

        $this->removeAppendFromFile($this->envFilePath);

        $fileContent = file_get_contents($this->envFilePath);

        try {
            $appendFileContent = file_get_contents($this->publishPath . '/.env');
        } catch (\Exception $e) {
            $appendFileContent = '';
        }

        if (empty($appendFileContent)) {
            return false;
        }

        $fileContent .= $comment . "\n\n" . $appendFileContent. "\n";
        file_put_contents($this->envFilePath, $fileContent);
    }

    public function initEnv()
    {
        if ($this->skipEnvInit === true) {
            return true;
        }

        if ($this->force === false) {
            if (!$this->confirm('Set .env file with initial values?')) {
                return true;
            }
        }

        $fillable = [
            'APP_NAME' => '',
            'APP_DEBUG' => 'true: development | false: production',
            'APP_URL' => '',
            'LOG_CHANNEL' => 'daily',
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '3306',
            'DB_DATABASE' => '',
            'DB_USERNAME' => '',
            'DB_PASSWORD' => '',
            'QUEUE_CONNECTION' => 'database',
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => '',
            'MAIL_PORT' => '',
            'MAIL_USERNAME' => '',
            'MAIL_PASSWORD' => '',
            'MAIL_ENCRYPTION' => '',
            'MAIL_FROM_ADDRESS' => '',
            'DEBUGBAR_ENABLED' => '',
            'SITE_REMOTE_FOOTER_ENABLED' => 'true',
            'SITE_REMOTE_FOOTER_API' => 'https://dogo.1do1.pl/api.php',
        ];

        $this->info('');
        $this->info('====================================================================================================');
        $this->info('=== Init .env file!                                                                              ===');
        $this->info('====================================================================================================');

        $file = file($this->envFilePath);
        $fileArray = [];
        foreach ($file as $number => $line) {
            if (strpos($line, '=') !== false) {
                $exploded = explode('=', $line, 2);
                if (!empty($exploded[1])) {
                    $exploded[1] = trim($exploded[1]);
                }
                $fileArray[$number] = $exploded;
            } else {
                $fileArray[$number] = $line;
            }
        }

        foreach ($fileArray as $number => $entry) {
            if (is_array($entry)) {

                if (array_key_exists($entry[0], $fillable)) {
                    $suggestion = !empty($fillable[$entry[0]]) ? ' (Suggestions: ' . $fillable[$entry[0]] . ')' : '';
                    $answer = $this->ask($entry[0] . $suggestion, $entry[1]);
                    $fileArray[$number][1] = $answer;
                }

                $fileArray[$number] = implode('=', $fileArray[$number]) . "\n";
            }
        }

        file_put_contents($this->envFilePath, implode('', $fileArray));
    }

    public function npmInstall($force = false)
    {
        $this->info('');
        $this->info('====================================================================================================');
        $this->info('=== npm packages to install npm install or yarn add                                              ===');
        $this->info('====================================================================================================');
        $this->info('');
        $this->info(config('glare.npm-install'));
        $this->info('');
    }

    public function cronInstall()
    {
        $this->info('');
        $this->info('====================================================================================================');
        $this->info('=== cron                                                                                         ===');
        $this->info('====================================================================================================');
        $this->info('');
        $cmd1 = '* * * * * php ' . str_replace('\\', '/', getcwd()) . '/artisan schedule:run';
        $cmd2 = '* * * * * php ' . str_replace('\\', '/', getcwd()) . '/artisan schedule:run >> /dev/null 2>&1';
        $this->info($cmd1);
        $this->info('');
        $this->info($cmd2);
        $this->info('');
    }

    public function backupFile($filePath)
    {
        $fresh = false;
        if (!is_file($filePath)) {
            touch($filePath);
            $fresh = true;
        }

        $pathinfo = pathinfo($filePath);
        $backupPath = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'] . '.' . $pathinfo['extension'] . '.' . $this->currentDateTime. '.bak';

        if (!$fresh) {
            copy($filePath, $backupPath);
        }
    }

    public function removeAppendFromFile($filePath)
    {
        $sliceKey = null;
        $deleteMarker = config('glare.custom-content.marker');

        $file = file($filePath);
        foreach ($file as $key => $line) {
            if (strpos($line, $deleteMarker) !== false) {
                $sliceKey = !is_null($sliceKey) ? $sliceKey : $key;
                dump($sliceKey);
            }
        }
        if (!is_null($sliceKey)) {
            $file = array_slice($file, 0, $sliceKey);
        }
        dump($file);
        file_put_contents($filePath, implode('', $file));
    }

    public function getJsonFile($path)
    {
        $jsonFile = file_get_contents($path);
        return json_decode($jsonFile);
    }

    public function putJsonFile($path, $data)
    {
        $jsonEncoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return file_put_contents($path, $jsonEncoded);
    }
}
