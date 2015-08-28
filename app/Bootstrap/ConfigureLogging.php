<?php namespace App\Bootstrap;

use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Monolog\Handler\LogEntriesHandler;

class ConfigureLogging extends BaseConfigureLogging
{

    /**
     * Custom Monolog handler .
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Log\Writer $log
     * @return void
     */
    public function configureCustomHandler(Application $app, Writer $log)
    {
        $handler = new LogEntriesHandler(getenv('LOGENTRY_TOKEN'));
        $log->getMonolog()->pushHandler($handler);

// Also Log to Daily files too.
        $log->useDailyFiles($app->storagePath() . '/logs/laravel.log', 5);
    }
}