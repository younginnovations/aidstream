<?php namespace App\Core\V201\Element;

use App\Core\V201\Formatter\CompleteCsvDataFormatter;
use App\Core\V201\Formatter\SimpleCsvDataFormatter;

/**
 * Class DownloadCsv
 * @package app\Core\V201\Element
 */
class DownloadCsv
{
    /**
     * @return DownloadCsv repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\DownloadCsv');
    }

    /**
     * Get CompleteCsvDataFormatter instance.
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getCompleteCsvDataFormatter()
    {
        return app(CompleteCsvDataFormatter::class);
    }

    /**
     * @return simpleCsvDataFormatter instance
     */
    public function getSimpleCsvDataFormatter()
    {
        return App('App\Core\V201\Formatter\SimpleCsvDataFormatter');
    }
}
