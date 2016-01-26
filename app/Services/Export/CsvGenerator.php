<?php namespace App\Services\Export;

use Maatwebsite\Excel\Excel as Generator;

/**
 * Class CsvGenerator
 * @package App\Services\Export
 */
class CsvGenerator
{
    /**
     * @var Generator
     */
    protected $generator;

    /**
     * Constant for default output format extension.
     */
    const CSV = 'csv';

    /**
     * @var Default output Format.
     */
    protected $defaultOutputFormat;

    /**
     * CsvGenerator constructor.
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator           = $generator;
        $this->defaultOutputFormat = self::CSV;
    }

    /**
     * Generate CSV from an array.
     * @param       $filename
     * @param $data
     */
    public function generate($filename, $data)
    {
        return $this->generator->create(
            $filename,
            function ($excel) use ($data) {
                $excel->sheet(
                    'aidStream',
                    function ($sheet) use ($data) {
                        $sheet->fromArray($data);
                    }
                );
            }
        )->export($this->defaultOutputFormat);
    }

    /**
     * Generate CSV with headers form an array
     * @param       $filename
     * @param array $data
     * @param array $headers
     */
    public function generateWithHeaders($filename, array $data, array $headers)
    {
        return $this->generator->create(
            $filename,
            function ($excel) use ($data, $headers) {
                $excel->sheet(
                    'aidStream',
                    function ($sheet) use ($data, $headers) {
                        $sheet->fromArray($data, '', '', '', $headers);
                    }
                );
            }
        )->export($this->defaultOutputFormat);
    }

    /**
     * Set output format.
     * @param $format
     * @return $this
     */
    public function setOutputFormat($format)
    {
        $this->defaultOutputFormat = $format;

        return $this;
    }
}
