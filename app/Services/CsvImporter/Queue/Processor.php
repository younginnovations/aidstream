<?php namespace App\Services\CsvImporter\Queue;

use Maatwebsite\Excel\Excel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Services\CsvImporter\Queue\Jobs\ImportActivity;

/**
 * Class Processor
 * @package App\Services\CsvImporter\Queue
 */
class Processor
{
    use DispatchesJobs;

    /**
     * @var ImportActivity
     */
    protected $importActivity;

    /**
     * @var Excel
     */
    protected $csvReader;

    /**
     * Total no. of header present in basic csv.
     */
    const BASIC_CSV_HEADERS_COUNT = 22;

    /**
     * Total no. of header present in basic with transaction csv.
     */
    const TRANSACTION_CSV_HEADERS_COUNT = 40;

    /**
     * Processor constructor.
     * @param Excel $csvReader
     */
    public function __construct(Excel $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    /**
     * Push a CSV file's data for processing into Queue.
     * @param $file
     * @param $filename
     * @param $activityIdentifiers
     */
    public function pushIntoQueue($file, $filename, $activityIdentifiers)
    {
        $csv = $this->csvReader->load($file)->toArray();

        $this->dispatch(
            new ImportActivity(new CsvProcessor($csv), $filename, $activityIdentifiers)
        );
    }
}
