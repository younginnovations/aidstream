<?php namespace App\Services\CsvImporter;

use App\Services\CsvImporter\Queue\Exceptions\HeaderMismatchException;
use Exception;
use Maatwebsite\Excel\Excel;

/**
 * Class CsvProcessor
 * @package App\Services\CsvImporter
 */
class CsvProcessor
{
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
    public $model;

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
    const ACTIVITY_TRANSACTION_OTHERS_HEADER_COUNT = 53;

    /**
     * Number of headers for the Activity Csv with Other Fields.
     */
    const ACTIVITY_OTHERS_HEADER_COUNT = 35;


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
        try {
            if ($this->isCorrectCsv()) {
                $this->groupValues();

                $this->make('App\Services\CsvImporter\Entities\Activity\Activity', ['organization_id' => $organizationId, 'user_id' => $userId]);

                $this->model->process();
            } else {
                $filepath = storage_path('csvImporter/tmp/' . $organizationId . '/' . $userId);
                $filename = 'header_mismatch.json';

                if (!file_exists($filepath)) {
                    mkdir($filepath, 0777, true);
                }

                file_put_contents($filepath . '/' . $filename, json_encode(['mismatch' => true]));
//                $this->fixStagingPermission($filepath . '/'. $filename);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * Fix file permission while on staging environment
     * @param $path
     */
    protected function fixStagingPermission($path)
    {
        // TODO: Remove this.
        shell_exec(sprintf('chmod 777 -R %s', $path));
    }

    /**
     * Make objects for the provided class.
     * @param       $class
     * @param array $options
     */
    protected function make($class, array $options = [])
    {
        try {
            if (class_exists($class)) {
                $this->model = app()->make($class, [$this->data, getVal($options, ['organization_id']), getVal($options, ['user_id'])]);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
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
     * @throws HeaderMismatchException
     */
    protected function isCorrectCsv()
    {
        if (!$this->csv) {
            return false;
        }

        $csvHeaders = array_keys($this->csv[0]);
        if (count($csvHeaders) == self::BASIC_CSV_HEADERS_COUNT) {
            $templateHeaders = $this->loadCsv('V201', 'basic_headers');
            $templateHeaders = array_keys($templateHeaders[0]);
            $diffHeaders     = array_diff($csvHeaders, $templateHeaders);

            return $this->isSameCsvHeader($diffHeaders);
        }

        if (count($csvHeaders) == self::TRANSACTION_CSV_HEADERS_COUNT) {
            $templateHeaders = $this->loadCsv('V201', 'transaction_headers');
            $templateHeaders = array_keys($templateHeaders[0]);
            $diffHeaders     = array_diff($csvHeaders, $templateHeaders);

            return $this->isSameCsvHeader($diffHeaders);
        }

        if (count($csvHeaders) == self::ACTIVITY_OTHERS_HEADER_COUNT) {
            $templateHeaders = $this->loadCsv('V201', 'other_fields_headers');
            $templateHeaders = array_keys($templateHeaders[0]);
            $diffHeaders     = array_diff($csvHeaders, $templateHeaders);

            return $this->isSameCsvHeader($diffHeaders);
        }

        if (count($csvHeaders) == self::ACTIVITY_TRANSACTION_OTHERS_HEADER_COUNT) {
            $templateHeaders = $this->loadCsv('V201', 'other_fields_transaction_headers');
            $templateHeaders = array_keys($templateHeaders[0]);
            $diffHeaders     = array_diff($csvHeaders, $templateHeaders);

            return $this->isSameCsvHeader($diffHeaders);
        }

        return false;
    }

    /**
     * Load Csv template
     * @param $version
     * @param $filename
     * @return array
     */
    protected function loadCsv($version, $filename)
    {
        $excel = app()->make(Excel::class);

        $file = $excel->load(app_path(sprintf('Services/CsvImporter/Templates/Activity/%s/%s.csv', $version, $filename)));

        return $file->toArray();
    }

    /**
     * Check if the difference of the csv headers is empty.
     * @param array $diffHeaders
     * @return bool
     */
    protected function isSameCsvHeader(array $diffHeaders)
    {
        if (empty($diffHeaders)) {
            return true;
        }

        return false;
    }
}
