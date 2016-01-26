<?php namespace App\Services\Download;

use App\Core\V201\Formatter\CompleteCsvDataFormatter;
use App\Core\V201\Formatter\SimpleCsvDataFormatter;
use App\Core\V201\Repositories\DownloadCsv;
use App\Core\Version;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DownloadCsvManager
 * @package App\Services
 */
class DownloadCsvManager
{
    /**
     * @var Version
     */
    protected $version;

    /**
     * @var activityElement instance
     */
    protected $activityElement;

    /**
     * @var DownloadCSV repository instance
     */
    protected $downloadCsvRepo;

    /**
     * @var SimpleCsvDataFormatter instance
     */
    protected $simpleCsvDataFormatter;

    /**
     * @var CompleteCsvDataFormatter instance
     * @var
     */
    protected $completeCsvDataFormatter;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->activityElement          = $version->getActivityElement();
        $this->downloadCsvRepo          = $this->activityElement->getDownloadCsv()->getRepository();
        $this->simpleCsvDataFormatter   = $this->activityElement->getDownloadCsv()->getSimpleCsvDataFormatter();
        $this->completeCsvDataFormatter = $this->activityElement->getDownloadCsv()->getCompleteCsvDataFormatter();
    }

    /**
     * get all activities
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllActivities()
    {
        return $this->downloadCsvRepo->getAllActivities();
    }

    /**
     * get all transactions of an activity
     * @param $activityId
     * @return mixed
     */
    public function getActivityTransactions($activityId)
    {
        return $this->downloadCsvRepo->getActivityTransactions($activityId);
    }

    /**
     * get all simple csv data
     * @param $organizationId
     * @return mixed
     */
    public function simpleCsvData($organizationId)
    {
        $activities = $this->downloadCsvRepo->simpleCsvData($organizationId);

        return $this->simpleCsvDataFormatter->format($activities);
    }

    /**
     * Get data for the Complete CSV to be generated.
     * @param $organizationId
     * @return Collection
     */
    public function completeCsvData($organizationId)
    {
        $csvData = $this->completeCsvDataFormatter->format($this->downloadCsvRepo->completeCsvData($organizationId));

        return $csvData;
    }
}
