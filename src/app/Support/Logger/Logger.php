<?php

namespace Glare\Support\Logger;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Logger
{
    static $PREFIX = '';

    static $LAST_LOG = null;

    public static function setPrefix($prefix)
    {
        static::$PREFIX = $prefix;
    }

    public function log($message)
    {
        $this->dumpIfInConsole($message);
        $messageToSave = (!is_string($message) || is_numeric($message)) ? json_encode($message) : $message;
        $message = static::$PREFIX . $messageToSave;
        Log::info($message);
        static::$LAST_LOG = 'log';
    }

    public function logStart($message)
    {
        $this->dumpIfInConsole('======================================================================');
        $this->dumpIfInConsole($message);
        $this->dumpIfInConsole('----------------------------------------------------------------------');
        $messageToSave = (!is_string($message) || is_numeric($message)) ? json_encode($message) : $message;
        $message = static::$PREFIX . $messageToSave;
        Log::info('======================================================================');
        Log::info($message);
        Log::info('----------------------------------------------------------------------');

        static::$LAST_LOG = 'logStart';
    }

    public function logSeparator($message)
    {
        if (static::$LAST_LOG === 'logStart') {
            return $this->log($message);
        }
        if (static::$LAST_LOG === 'logEnd') {
            return $this->logStart($message);
        }
        $this->dumpIfInConsole('----------------------------------------------------------------------');
        $this->dumpIfInConsole($message);
        $this->dumpIfInConsole('----------------------------------------------------------------------');
        $messageToSave = (!is_string($message) || is_numeric($message)) ? json_encode($message) : $message;
        $message = static::$PREFIX . $messageToSave;
        Log::info('----------------------------------------------------------------------');
        Log::info($message);
        Log::info('----------------------------------------------------------------------');
        static::$LAST_LOG = 'logSeparator';
    }

    public function logEnd($message)
    {
        $this->dumpIfInConsole('----------------------------------------------------------------------');
        $this->dumpIfInConsole($message);
        $this->dumpIfInConsole('======================================================================');
        $messageToSave = (!is_string($message) || is_numeric($message)) ? json_encode($message) : $message;
        $message = static::$PREFIX . $messageToSave;
        Log::info('----------------------------------------------------------------------');
        Log::info($message);
        Log::info('======================================================================');
        static::$LAST_LOG = 'logEnd';
    }

    public function logMemory($real = false)
    {
        $m = memory_get_usage($real) / 1024 / 1024;
        $m = round($m);
        $this->log('Memory Usage: ' . $m . ' MB');
    }

    public function logTime()
    {
        $m = date("Y-m-d H:i:s");
        $this->log('Time: ' . $m);
    }

    public function dumpIfInConsole($message)
    {
        if (App::runningInConsole())
        {
            dump($message);
        }
    }
}
