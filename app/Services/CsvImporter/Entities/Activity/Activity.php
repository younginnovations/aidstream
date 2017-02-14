<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Csv;

/**
 * Class Activity
 * @package App\Services\CsvImporter\Entities\Activity
 */
class Activity extends Csv
{
    /**
     * @var
     */
    protected $activityIdentifiers;

    /**
     * Activity constructor.
     * @param $rows
     * @param $organizationId
     * @param $userId
     * @param $activityIdentifiers
     */
    public function __construct($rows, $organizationId, $userId, $activityIdentifiers)
    {
        $this->csvRows             = $rows;
        $this->organizationId      = $organizationId;
        $this->userId              = $userId;
        $this->rows                = $rows;
        $this->activityIdentifiers = $activityIdentifiers;
    }

    /**
     * Process the Activity Csv.
     *
     * @return $this
     */
    public function process()
    {
        foreach ($this->rows() as $row) {
            $this->initialize($row, $this->activityIdentifiers)
                 ->process()
                 ->validate()
                 ->validateUnique($this->csvRows)
                 ->checkExistence($row)
                 ->keep();
        }

        return $this;
    }
}
