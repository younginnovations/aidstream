<?php namespace App\Services\CsvImporter\Entities;

use Exception;

/**
 * Class Csv
 * @package App\Services\CsvImporter\Entities
 */
abstract class Csv
{
    /**
     * @var
     */
    protected $rows;

    /**
     * Current Organization's id.
     * @var
     */
    protected $organizationId;

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

    /**
     * Rows from the uploaded CSV file.
     * @var array
     */
    protected $csvRows = [];

    /**
     * Initialize objects for the CSV class with the respective Row objects.
     * @param $rows
     * @param $class
     */
    protected function make($rows, $class)
    {
        array_walk(
            $rows,
            function ($row) use ($class) {
                if (class_exists($class)) {
                    try {
                        $this->rows[] = app()->make($class, [$row, $this->organizationId, $this->userId]);
                    } catch (Exception $exception) {
                        dd($exception->getMessage());
                    }

                }
            }
        );
    }

    /**
     * Get the rows in the CSV.
     * @return mixed
     */
    public function rows()
    {
        return $this->rows;
    }
}
