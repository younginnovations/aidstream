<?php namespace App\Core\V201\Formatter;

use App\Core\V201\Formatter\Factory\Traits\StringConcatenator;
use App\Core\V201\Formatter\Factory\Traits\TransactionDataMapper;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TransactionCsvDataFormatter
 * @package App\Core\V201\Formatter
 */
class TransactionCsvDataFormatter
{
    use StringConcatenator, TransactionDataMapper;

    /**
     * @var array
     */
    protected $headers;
    /**
     * @var GetCodeName
     */
    protected $codeNameHelper;
    /**
     * @var array
     */
    protected $csvData = [];
    /**
     * @var SimpleCsvDataFormatter
     */
    protected $csvDataFormatter;

    /**
     *
     */
    const TRANSACTION_HEADERS_BASE_PATH = 'Services/CsvImporter/Templates/Activity';
    /**
     *
     */
    const TRANSACTION_TEMPLATE_FILENAME = 'detailed_transaction.csv';

    /**
     * TransactionCsvDataFormatter Constructor
     * @param SimpleCsvDataFormatter $csvDataFormatter
     */
    public function __construct(SimpleCsvDataFormatter $csvDataFormatter)
    {
        $this->csvDataFormatter = $csvDataFormatter;
        $this->headers          = $this->loadHeaders();
    }

    /**
     * Format data for transaction csv
     * @param Collection $activities
     * @return array
     */
    public function format(Collection $activities)
    {
        if ($activities->isEmpty()) {
            return false;
        }

        $this->csvData = ['headers' => $this->headers];
        $version       = session('version');

        foreach ($activities as $activity) {
            $this->csvData = array_merge($this->csvData, $this->formatVersionWise($activity, $version));
        }

        if (count($this->csvData) == 1) {
            return null;
        }

        return $this->csvData;
    }


    /**
     * Format Transaction Sector
     * @param $transactionSector
     * @return mixed
     */
    protected function formatSector($transactionSector)
    {
        if ($transactionSector['sector_vocabulary'] == 1) {
            $sector = $transactionSector['sector_code'];
        } elseif ($transactionSector['sector_vocabulary'] == 2) {
            $sector = $transactionSector['sector_category_code'];
        } else {
            $sector = $transactionSector['sector_text'];
        }

        return $sector;
    }

    /**
     * Load template for transaction.
     *
     * @return array
     */
    protected function loadHeaders()
    {
        $path = sprintf('%s/%s/%s/%s', app_path(), self::TRANSACTION_HEADERS_BASE_PATH, session('version'), self::TRANSACTION_TEMPLATE_FILENAME);

        return explode(',', file_get_contents($path));
    }
}

