<?php namespace App\Services\CsvImporter\Queue;

use App\Services\CsvImporter\CsvReader\CsvReader;
use App\Services\CsvImporter\CsvResultProcessor;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Services\CsvImporter\Queue\Jobs\ImportResult;

/**
 * Class Processor
 * @package App\Services\CsvImporter\Queue
 */
class ResultProcessor
{
    use DispatchesJobs;

    /**
     * @var ImportResult
     */
    protected $importResult;

    /**
     * @var CsvReader
     */
    protected $csvReader;

    /**
     * Total no. of header present in basic csv.
     */
    const CSV_HEADERS_COUNT = 33;

    /**
     * Processor constructor.
     * @param CsvReader $csvReader
     */
    public function __construct(CsvReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * Push a CSV file's data for processing into Queue.
     * @param $file
     * @param $filename
     */
    public function pushIntoQueue($file, $filename, $version)
    {
        $csv = $this->csvReader->load($file)->toArray();
        $this->dispatch(
            new ImportResult(new CsvResultProcessor($csv), $filename, $version)   
        );
            // dd($csv);
        // $importResult = new ImportResult(new CsvResultProcessor($csv), $filename, $version);
        // $importResult->handle();
    }
}
