<?php namespace App\Core\V202\Element;

use App\Core\V201\Element\DownloadCsv as V201DownloadCsv;
use App\Core\V202\Formatter\CompleteCsvDataFormatter;
use App\Core\V202\Formatter\SimpleCsvDataFormatter;

class DownloadCsv extends V201DownloadCsv
{
    /**
     * Get CompleteCsvDataFormatter instance.
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getCompleteCsvDataFormatter()
    {
        return app(CompleteCsvDataFormatter::class);
    }

    /**
     * @return SimpleCsvDataFormatter instance
     */
    public function getSimpleCsvDataFormatter()
    {
        return App(SimpleCsvDataFormatter::class);
    }
}
