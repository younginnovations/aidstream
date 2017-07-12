<?php namespace App\Services\Activity;

use App\Core\V201\Parser\SimpleActivity;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Services\Organization\OrganizationManager;
use Exception;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;

/**
 * Class ImportActivity
 * @package App\Services\Activity
 */
class ImportActivity
{
    /**
     * @var SimpleActivity/false
     */
    protected $template;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var Log
     */
    protected $logger;
    /*
     * @var array
     * */
    protected $importedActivities;
    /**
     * @var OrganizationManager
     */
    private $orgManager;

    /**
     * @param Version             $version
     * @param Log                 $logger
     * @param OrganizationManager $orgManager
     */
    public function __construct(DatabaseManager $databaseManager, Version $version, Log $logger, OrganizationManager $orgManager)
    {
        $this->databaseManager          = $databaseManager;
        $this->simpleActivityParser     = $version->getActivityElement()->getSimpleActivityParser();
        $this->simpleActivityDemoParser = $version->getActivityElement()->getSimpleActivityDemoParser();
        $this->version                  = $version;
        $this->logger                   = $logger;
        $this->orgManager               = $orgManager;
    }

    /**
     * return activity rows from csv with errors from parser of respective template
     * @param $csvFile
     * @return array
     */
    public function getActivities($csvFile)
    {
        if (!isset($csvFile)) {
            return session('activities');
        }
        $csvData = $this->getCsvData($csvFile);

        if (!$csvData->get()->count()) {
            return [];
        }
        $firstData = $csvData->toArray()[0];
        $this->setTemplate($firstData);

        if ($this->template) {
            $activities = $this->template->getVerifiedActivities($csvData);
            session()->put('activities', $activities);

            return $activities;
        }

        return false;
    }

    /**
     * return csv data
     * @param $csvFile
     * @return \Maatwebsite\Excel\Readers\LaravelExcelReader
     */
    protected function getCsvData($csvFile)
    {
        return $this->version->getExcel()->load($csvFile);
    }

    /**
     * set respective template
     * @param array $firstData
     */
    protected function setTemplate(array $firstData)
    {
        $this->template ?: $this->template = $this->simpleActivityParser->getTemplate($firstData);
        $this->template ?: $this->template = $this->simpleActivityDemoParser->getTemplate($firstData);
    }

    /**
     * import selected activities
     * @param array $activities
     * @return bool
     */
    public function importActivities(array $activities)
    {
        try {
            $this->databaseManager->beginTransaction();
            $organization = $this->orgManager->getOrganization(session('org_id'));
            $this->template ?: $this->setTemplate(json_decode($activities[0], true));
            $this->importedActivities = $this->template->save($activities);
            $this->databaseManager->commit();

            $this->logger->activity(
                "activity.activity_uploaded",
                [
                    'organization'    => $organization->name,
                    'organization_id' => $organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error($exception, ['activities' => $activities]);
        }

        return false;
    }

    /**
     * return imported activity links
     * @return array|string
     */
    public function getImportedActivities()
    {
        $activityLinks = [];
        foreach ($this->importedActivities as $activity) {
            $activityLinks[] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                route('activity.show', [$activity->id]),
                $activity->title ? $activity->title[0]['narrative'] : $activity->identifier['iati_identifier_text']
            );
        }

        return $activityLinks;
    }
}
