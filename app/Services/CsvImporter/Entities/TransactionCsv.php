<?php namespace App\Services\CsvImporter\Entities;


use App\Services\CsvImporter\Entities\Activity\Components\TransactionRow;

abstract class TransactionCsv
{
    /**
     * @var
     */
    protected $rows;

    /**
     * Current Activity id.
     *
     * @var
     */
    protected $activityId;

    /**
     * Current Version.
     *
     * @var
     */
    protected $version;

    /**
     * Rows from the uploaded CSV file.
     *
     * @var array
     */
    protected $csvRows = [];

    /**
     * Initialize an TransactionRow object.
     *
     * @param $row
     * @return TransactionRow
     */
    protected function initialize($rows)
    {
        return app()->make(TransactionRow::class, [$rows, $this->activityId, $this->version]);
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

