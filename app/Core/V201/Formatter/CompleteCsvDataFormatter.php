<?php namespace App\Core\V201\Formatter;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class CompleteCsvDataFormatter
 * @package App\Core\V201\Formatter
 */
class CompleteCsvDataFormatter
{
    /**
     * Headers required by Complete Csv.
     * @var array
     */
    protected $headers = [];

    /**
     * Path for the Csv template file for V201.
     */
    CONST TEMPLATE_PATH = 'Core/V201/Template/Csv/complete.csv';

    /**
     * CompleteCsvDataFormatter constructor.
     */
    public function __construct()
    {
        $this->getHeaders();
    }

    /**
     * Format data for Complete Csv generation.
     * @param Collection $data
     */
    public function format(Collection $data)
    {

    }

    /**
     * Generate Headers for Complete Csv.
     */
    protected function getHeaders()
    {
        Excel::load(
            sprintf('%s/%s', app_path(), self::TEMPLATE_PATH),
            function ($reader) {
                foreach ($reader->first() as $key => $value) {
                    $this->headers['keys'][]        = $key;
                    $this->headers['headers'][$key] = ucfirst($key);
                }
            }
        );
    }
}
