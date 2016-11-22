<?php namespace App\Services\CsvImporter\Queue;

use App\Services\CsvImporter\Entities\Activity\Activity;
use App\Services\CsvImporter\Traits\ChecksCsvHeaders;

/**
 * Class CsvProcessor
 * @package App\Services\CsvImporter
 */
class CsvProcessor
{
    use ChecksCsvHeaders;

    /**
     * @var
     */
    protected $csv;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var
     */
    public $activity;

    /**
     * @var string
     */
    protected $csvIdentifier = 'activity_identifier';

    /**
     * Total no. of header present in basic csv.
     */
    const BASIC_CSV_HEADERS_COUNT = 25;

    /**
     * Total no. of header present in basic with transaction csv.
     */
    const TRANSACTION_CSV_HEADERS_COUNT = 43;

    /**
     * Number of headers for the Activity Csv with Transactions and Other Fields.
     */
    const ACTIVITY_TRANSACTION_OTHERS_HEADER_COUNT = 56;

    /**
     * Number of headers for the Activity Csv with Other Fields.
     */
    const ACTIVITY_OTHERS_HEADER_COUNT = 38;

    /**
     * CsvProcessor constructor.
     * @param $csv
     */
    public function __construct($csv)
    {
        $this->csv = $csv;
    }

    /**
     * Handle the import functionality.
     * @param $organizationId
     * @param $userId
     */
    public function handle($organizationId, $userId)
    {
        $this->filterHeader();
        if ($this->isCorrectCsv()) {
            $this->groupValues();

            $this->initActivity(['organization_id' => $organizationId, 'user_id' => $userId]);

            $this->activity->process();
        } else {
            $filepath = storage_path('csvImporter/tmp/' . $organizationId . '/' . $userId);
            $filename = 'header_mismatch.json';

            if (!file_exists($filepath)) {
                mkdir($filepath, 0777, true);
            }

            file_put_contents($filepath . '/' . $filename, json_encode(['mismatch' => true]));
        }
    }

    /**
     * Initialize an object for the Activity class with the provided options.
     *
     * @param array $options
     */
    protected function initActivity(array $options = [])
    {
        if (class_exists(Activity::class)) {
            $this->activity = app()->make(Activity::class, [$this->data, getVal($options, ['organization_id']), getVal($options, ['user_id'])]);
        }
    }

    /**
     * Group rows into single Activities.
     */
    protected function groupValues()
    {
        $index = - 1;

        foreach ($this->csv as $row) {
            if (!$this->isSameEntity($row)) {
                $index ++;
            }

            $this->group($row, $index);
        }
    }

    /**
     * Group the values of a row to a specific index.
     * @param $row
     * @param $index
     */
    protected function group($row, $index)
    {
        foreach ($row as $key => $value) {
            $this->setValue($index, $key, $value);
        }
    }

    /**
     * Set the provided value to the provided key/index.
     * @param $index
     * @param $key
     * @param $value
     */
    protected function setValue($index, $key, $value)
    {
        $this->data[$index][$key][] = $value;
    }

    /**
     * Check if the next row is new row or not.
     * @param $row
     * @return bool
     */
    protected function isSameEntity($row)
    {
        if (is_null($row[$this->csvIdentifier]) || $row[$this->csvIdentifier] == '') {
            return true;
        }

        return false;
    }

    /**
     * Check if the headers are correct according to the provided template.
     * @return bool
     */
    protected function isCorrectCsv()
    {
        if (!$this->csv) {
            return false;
        }

        return $this->hasCorrectHeaders();
    }

    /**
     * Filter unwanted keys generated while copying and pasting csv headers. For ex 0 index
     * @return mixed
     */
    protected function filterHeader()
    {
        foreach ($this->csv as $index => $csvHeaders) {
            foreach ($csvHeaders as $headerIndex => $header) {
                if ($headerIndex === 0) {
                    unset($this->csv[$index][$headerIndex]);
                }
            }
        }
    }
}
