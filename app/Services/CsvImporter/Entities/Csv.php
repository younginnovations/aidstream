<?php namespace App\Services\CsvImporter\Entities;

use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;

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
     *
     * @var
     */
    protected $organizationId;

    /**
     * Current User's id.
     *
     * @var
     */
    protected $userId;

    /**
     * Rows from the uploaded CSV file.
     *
     * @var array
     */
    protected $csvRows = [];

    /**
     * Initialize an ActivityRow object.
     *
     * @param $row
     * @return ActivityRow
     */
    protected function initialize($row)
    {
        return app()->make(ActivityRow::class, [$row, $this->organizationId, $this->userId]);
    }

    /**
     * Get the rows in the CSV.
     *
     * @return mixed
     */
    public function rows()
    {
        return $this->rows;
    }
}
