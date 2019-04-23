<?php namespace App\Core\V203\Element;

use App\Core\V202\Element\DownloadCsv as V202DownloadCsv;
use App\Core\V203\Formatter\TransactionCsvDataFormatter;
class DownloadCsv extends V202DownloadCsv
{
    /**
     * Get TransactionCSvDataFormatter instance
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getTransactionCsvDataFormatter()
    {
        return app(TransactionCsvDataFormatter::class);
    }
}
