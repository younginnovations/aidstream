<?php namespace App\Services\CsvImporter\Entities\Activity;

use App\Services\CsvImporter\Entities\Csv;
use Illuminate\Support\Facades\Log;

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

    private $version;

    /**
     * Activity constructor.
     * @param $rows
     * @param $organizationId
     * @param $userId
     * @param $activityIdentifiers
     */
    public function __construct($rows, $organizationId, $userId, $activityIdentifiers, $version)
    {
        $this->csvRows             = $rows;
        $this->organizationId      = $organizationId;
        $this->userId              = $userId;
        $this->rows                = $rows;
        $this->activityIdentifiers = $activityIdentifiers;
        $this->version             = $version;
    }

    /**
     * Process the Activity Csv.
     *
     * @return $this
     */
    public function process()
    {
        foreach ($this->rows() as $row) {
            $this->initialize($row, $this->activityIdentifiers, $this->version)
                 ->process()
                 ->validate()
                 ->validateUnique($this->csvRows)
                 ->checkExistence($row)
                 ->keep();
        }

        return $this;
    }
}
