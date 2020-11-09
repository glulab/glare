<?php

namespace Glare\Console\Commands;

use Illuminate\Console\Command;

/**
 * GlareSchedulerTest
 * Copy to app/Console?kernel.php
 * $schedule->command('glare:scheduler-test --log --mail')->everyMinute(); // ->everyMinute() // ->dailyAt('01:31');
 */
class GlareSchedulerTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glare:scheduler-test {--log} {--mail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler Test';

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
        $this->log = $this->option('log');
        $this->mail = $this->option('mail');

        $this->datetime = date("Y-m-d H:i:s");

        $this->test();
    }

    /**
     * [test description]
     *
     * @return [type] [description]
     */
    public function test()
    {
        if ($this->log) {
            $this->log('scheduler test');
        }

        if ($this->mail) {
            $to      = 'developerdogo@gmail.com';
            $subject = config('app.name') . ' | ' . config('app.url') . ' | scheduler test | ' . $this->datetime;
            $message = config('app.name') . ' | ' . config('app.url') . ' | scheduler test | ' . $this->datetime;
            $headers =
                'From: '. config('mail.from.name') . ' <' . config('mail.from.address') . '>' . "\r\n" .
                'Reply-To: '. config('mail.from.name') . ' <' . config('mail.from.address') . '>' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $parameters = '-f' . config('mail.from.address');

            $r = mail($to, $subject, $message, $headers, $parameters);
            if (!$r) {
                $this->log('scheduler test : mail ' . ($r ? 'sent' : 'error'));
            }
        }
    }

    protected function log($line)
    {
        $d = getcwd();
        $f = $d . '/_scheduler-test.log';
        $fp = fopen($f, 'a+');
        flock($fp, LOCK_EX);
        fwrite($fp,  $this->datetime . ' : ' . $line ."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
