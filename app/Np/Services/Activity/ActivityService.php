<?php namespace App\Np\Services\Activity;

use App\Np\Contracts\NpDocumentLinkRepositoryInterface;
use App\Np\Contracts\NpActivityRepositoryInterface;
use App\Np\Contracts\ActivityLocationRepositoryInterface;
use App\Np\Services\Data\Traits\TransformsData;
use App\Np\Services\ExchangeRate\ExchangeRateService;
use App\Np\Services\Settings\SettingsService;
use App\Np\Services\Traits\ProvidesLoggerContext;
use App\Models\ActivityPublished;
use App\Models\Activity\ActivityLocation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ActivityService
 * @package App\Np\Services\Activity
 */
class ActivityService
{
    use ProvidesLoggerContext, TransformsData;

    /**
     * @var ActivityRepositoryInterface
     */
    protected $activityRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ExchangeRateService
     */
    protected $exchangeRateService;

    /**
     * @var ActivityPublished
     */
    protected $activityPublished;

    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var DocumentLinkRepositoryInterface
     */
    protected $documentLinkRepository;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * ActivityService constructor.
     * @param ActivityRepositoryInterface     $activityRepository
     * @param SettingsService                 $settingsService
     * @param ExchangeRateService             $exchangeRateService
     * @param ActivityPublished               $activityPublished
     * @param DocumentLinkRepositoryInterface $documentLinkRepository
     * @param LoggerInterface                 $logger
     * @param DatabaseManager                 $databaseManager
     * @internal param DatabaseManager $databaseManager
     */
    public function __construct(
        NpActivityRepositoryInterface $activityRepository,
        ActivityLocationRepositoryInterface $activityLocationRepository,
        SettingsService $settingsService,
        ExchangeRateService $exchangeRateService,
        ActivityPublished $activityPublished,
        ActivityLocation $activityLocation,
        NpDocumentLinkRepositoryInterface $documentLinkRepository,
        LoggerInterface $logger,
        DatabaseManager $databaseManager
    ) {
        $this->activityRepository     = $activityRepository;
        $this->activityLocationRepository = $activityLocationRepository;
        $this->logger                 = $logger;
        $this->exchangeRateService    = $exchangeRateService;
        $this->activityPublished      = $activityPublished;
        $this->activityLocation       = $activityLocation;
        $this->settingsService        = $settingsService;
        $this->databaseManager        = $databaseManager;
        $this->documentLinkRepository = $documentLinkRepository;
    }

    /**
     * Get all Activities for the current Organization.
     *
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function all()
    {
        try {
            return $this->activityRepository->all(session('org_id'));
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                $this->getContext($exception)
            );

            return [];
        }
    }

    /**
     * Get all Activities for the current Organization.
     *
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function listAll()
    {
        try {
            return $this->activityRepository->listAll();
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                $this->getContext($exception)
            );

            return [];
        }
    }

    /**
     * Store the Activity data.
     *
     * @param array $rawData
     * @param       $version
     * @return \App\Models\Activity\Activity|null
     */
    public function store(array $rawData, $version)
    {
        try {
            $activityMappedData = $this->transform($this->getMapping($rawData, 'Activity', $version));
            $documentLinkData   = $this->transform($this->getMapping($rawData, 'DocumentLink', $version));
            $settings           = $this->settingsService->find(session('org_id'))->toArray();

            $this->databaseManager->beginTransaction();
            (!($defaultFieldValues = getVal((array) $settings, [0, 'default_field_values'], []))) ?: $activityMappedData['default_field_values'] = $defaultFieldValues;

            $activity = $this->activityRepository->save($activityMappedData);
            if ($documentLinkData) {
                $this->documentLinkRepository->save($documentLinkData, $activity->id);
            }
            $this->databaseManager->commit();

            $this->logger->info('Activity successfully saved . ', $this->getContext());

            return $activity;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(sprintf('Error due to % s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     *  Find a Specific Activity.
     *
     * @param $activityId
     * @return \App\Models\Activity\Activity
     */
    public function find($activityId)
    {
        try {
            return $this->activityRepository->find($activityId);
        } catch (Exception $exception) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Delete a activity.
     *
     * @param $activityId
     * @return mixed|null
     */
    public function delete($activityId)
    {
        try {
            $this->databaseManager->beginTransaction();
            $activity = $this->activityRepository->delete($activityId);
            $this->databaseManager->commit();
            $this->logger->info('Activity successfully deleted . ', $this->getContext());

            return $activity;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(sprintf('Error due to % s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Returns reversely mapped activity data to edit.
     *
     * @param $activityId
     * @param $version
     * @return array
     */
    public function edit($activityId, $version)
    {
        $activity           = $this->find($activityId)->toArray();
        $documentLink       = $this->documentLinkRepository->all($activity['id'])->toArray();
        $activityMappedData = $this->transformReverse($this->getMapping($activity, 'Activity', $version));

        if ($documentLink) {
            $documentLinkData   = $this->transformReverse($this->getMapping($documentLink, 'DocumentLink', $version));
            $activityMappedData = array_merge($activityMappedData, $documentLinkData);
        }

        return $activityMappedData;
    }

    /**
     * Update the activity data.
     *
     * @param $activityId
     * @param $rawData
     * @param $version
     * @return mixed|null
     */
    public function update($activityId, $rawData, $version)
    {
        try {
            $documentLinkData = $this->transform($this->getMapping($rawData, 'DocumentLink', $version));

            $this->databaseManager->beginTransaction();
            $this->activityRepository->update($activityId, $this->transform($this->getMapping($rawData, 'Activity', $version)));
            if ($documentLinkData) {
                $this->documentLinkRepository->update($documentLinkData, $activityId);
            }
            $this->databaseManager->commit();
            $this->logger->info('Activity successfully updated . ', $this->getContext());

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(sprintf('Error due to % s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Returns the status of the activity.
     *
     * @return array
     */
    public function getActivityStats()
    {
        $stats        = ['draft' => 0, 'completed' => 0, 'verified' => 0, 'published' => 0];
        $activities   = $this->all();
        $statsMapping = [0 => 'draft', 1 => 'completed', 2 => 'verified', 3 => 'published'];

        foreach ($activities as $activity) {
            $stats[$statsMapping[$activity->activity_workflow]] = $stats[$statsMapping[$activity->activity_workflow]] + 1;
        }

        return $stats;
    }

    /**
     * Returns budget details of all activities.
     *
     * @return array
     */
    public function getBudgetDetails()
    {
        $activities    = $this->all();
        $budgetDetails = $this->exchangeRateService->budgetDetails($activities);

        return $budgetDetails;
    }

    /**
     * Returns the number of activities published in IATI Registry.
     *
     * @param $orgId
     * @return int
     */
    public function getNumberOfPublishedActivities($orgId)
    {
        $publishedInRegistry = $this->getPublishedActivities($orgId);
        $publishedActivities = getVal($publishedInRegistry, [0, 'published_activities']);

        return ($publishedActivities == "" || is_null($publishedActivities)) ? 0 : count($publishedActivities);
    }

    /**
     * Returns the last published date of the activity.
     *
     * @param $orgId
     * @return boolean|string
     */
    public function lastPublishedToIATI($orgId)
    {
        $publishedInRegistry = $this->getPublishedActivities($orgId);
        $lastUpdated         = getVal($publishedInRegistry, [0, 'updated_at']);

        return ($lastUpdated == "" || is_null($lastUpdated)) ? false : $lastUpdated;
    }

    /**
     * Returns the activities of organisation published in iati registry.
     *
     * @param $orgId
     * @return mixed
     */
    protected function getPublishedActivities($orgId)
    {
        $activityFilename    = $this->publishedFilename($orgId);
        $publishedInRegistry = $this->activityPublished->where('organization_id', $orgId)
                                                       ->where('filename', $activityFilename)
                                                       ->where('published_to_register', 1)
                                                       ->get()->toArray();

        return $publishedInRegistry;
    }


    /**
     * Returns the filename that will be used while publishing activities.
     *
     * @param $orgId
     * @return bool|string
     */
    protected function publishedFilename($orgId)
    {
        $settings    = $this->settingsService->find($orgId)->toArray();
        $publisherId = false;

        if (($registryInfo = getVal($settings, [0, 'registry_info'], []))) {
            $publisherId = (($id = getVal($registryInfo, [0, 'publisher_id'])) == "") ? $publisherId : $id;
        }

        if ($publisherId) {
            $publisherId = sprintf('%s-activities.xml', $publisherId);
        }

        return $publisherId;
    }

    /**
     * Returns Budget Model in view format
     * @param $activityId
     * @param $version
     * @return array
     * @internal param $budget
     */
    public function getBudgetModel($activityId, $version)
    {
        $model = json_decode($this->activityRepository->find($activityId), true);

        $filteredModel = $this->transformReverse($this->getMapping($model, 'Budget', $version));

        return $filteredModel;
    }

    /**
     * Adds new budgets to the current activity.
     *
     * @param $activityId
     * @param $rawData
     * @param $version
     * @return bool|null
     */
    public function addBudget($activityId, $rawData, $version)
    {
        try {
            $mappedBudget = $this->transform($this->getMapping($rawData, 'Budget', $version));
            $activity     = $this->activityRepository->find($activityId)->toArray();

            foreach (getVal($mappedBudget, ['budget'], []) as $index => $value) {
                $activity['budget'][] = $value;
            }

            $this->databaseManager->beginTransaction();
            $this->activityRepository->update($activityId, $activity);
            $this->databaseManager->commit();

            $this->logger->info('Budget successfully added . ', $this->getContext());

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(sprintf('Error due to % s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Deletes a budget from current activity.
     *
     * @param $activityId
     * @param $request
     * @return bool|null
     */
    public function deleteBudget($activityId, $request)
    {
        try {
            $this->databaseManager->beginTransaction();

            $this->activityRepository->deleteBudget($activityId, $request->get('index'));

            $this->databaseManager->commit();

            $this->logger->info('Budget transaction successfully deleted . ', $this->getContext());

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(sprintf('Error due to % s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Returns reversely mapped document links data.
     *g
     * @param $activityId
     * @param $version
     * @return array
     */
    public function documentLinks($activityId, $version)
    {
        $documentLinks = $this->documentLinkRepository->all($activityId)->toArray();

        return $this->transformReverse($this->getMapping($documentLinks, 'DocumentLink', $version));
    }

    /**
     * Updates budget of current activity
     *
     * @param $activityId
     * @param $rawData
     * @param $version
     * @return bool|null
     */
    public function updateBudget($activityId, $rawData, $version)
    {
        try {
            $mappedBudget = $this->transform($this->getMapping($rawData, 'Budget', $version));
            $activity     = $this->activityRepository->find($activityId)->toArray();

            $activity['budget'] = $mappedBudget['budget'];

            $this->databaseManager->beginTransaction();
            $this->activityRepository->update($activityId, $activity);
            $this->databaseManager->commit();

            $this->logger->info('Budget successfully updated . ', $this->getContext());

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error(sprintf('Error due to % s', $exception->getMessage()), $this->getContext($exception));

            return null;
        }
    }

    /**
     * Reverse transform the requested data.
     *
     * @param $rawData
     * @param $version
     * @return array
     */
    public function reverseTransform($rawData, $version)
    {
        return $this->transformReverse($this->getMapping($rawData, 'Activity', $version));
    }

    /**
     * @param $activity
     * @return array
     */
    public function location($activity)
    {
        $locations = getVal($activity, ['location'], []);
        $data      = [];

        foreach ($locations as $index => $location) {
            $administrative = getVal($location, ['administrative'], []);
            $region         = getVal($administrative, [0, 'code'], '');
            $district       = getVal($administrative, [1, 'code']);
            ($region == "") ?: $data[$index]['region'] = $region;
            ($district == "") ?: $data[$index]['district'] = $district;
        }

        return $data;
    }

    /**
     * Returns recipient countries of the activity.
     *
     * @param $countries
     * @return array
     */
    public function getRecipientCountry($countries)
    {
        $countryCode = [];

        if (is_array($countries)) {
            foreach ($countries as $index => $country) {
                $countryCode[] = getVal($country, ['country_code']);
            }
        }

        return $countryCode;
    }

    public function saveLocation(array $rawData, $activityId)
    {
        $arr = getVal($rawData, ['location'], []);
        $arr['activity_id'] = $activityId;
        $this->activityLocationRepository->save($arr);
    }
    public function checkError(array $rawData)
    {
        $arr= getVal($rawData, ['location']);
        foreach ($arr as $k => $val) {
            if ($val['municipality'] == "" || $val['municipality'] == null) {
                return false;
            }
        }
        return true;
    }
}
