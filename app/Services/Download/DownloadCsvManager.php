<?php namespace App\Services\Download;

use App\Core\V201\Formatter\CompleteCsvDataFormatter;
use App\Core\V201\Formatter\SimpleCsvDataFormatter;
use App\Core\V201\Repositories\DownloadCsv;
use App\Core\Version;

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
     * @return mixed
     */
    public function simpleCsvData()
    {
        $activities = $this->downloadCsvRepo->simpleCsvData();

        return $this->simpleCsvDataFormatter->format($activities);
    }

    /**
     * Get data for the Complete CSV to be generated.
     */
    public function completeCsvData()
    {
        $this->completeCsvDataFormatter->format($this->downloadCsvRepo->completeCsvData());
    }
}
