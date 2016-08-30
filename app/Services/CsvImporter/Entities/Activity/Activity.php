<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;
use App\Services\CsvImporter\Entities\Csv;
use Exception;

/**
 * Class Activity
 * @package App\Services\CsvImporter\Entities\Activity
 */
class Activity extends Csv
{
    /**
     * Activity constructor.
     * @param $rows
     * @param $organizationId
     * @param $userId
     */
    public function __construct($rows, $organizationId, $userId)
    {
        try {
            $this->csvRows        = $rows;
            $this->organizationId = $organizationId;
            $this->userId         = $userId;
            $this->make($rows, ActivityRow::class);
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * Process the Activity Csv.
     * @return $this
     */
    public function process()
    {
        foreach ($this->rows() as $row) {
            $row->process()->validate()->validateUnique($this->csvRows)->keep();
        }

        return $this;
    }
}
