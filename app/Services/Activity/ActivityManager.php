<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use App\Services\PerfectViewer\PerfectViewerManager;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class ActivityManager
 * @package App\Services\Activity
 */
class ActivityManager
{
    protected $activityRepo;

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Version
     */
    protected $version;

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var PerfectViewerManager
     */
    protected $perfectViewerManager;

    /**
     * @param Version              $version
     * @param Guard                $auth
     * @param Logger               $logger
     * @param DatabaseManager      $database
     * @param PerfectViewerManager $perfectViewerManager
     */
    public function __construct(Version $version, Guard $auth, Logger $logger, DatabaseManager $database, PerfectViewerManager $perfectViewerManager)
    {
        $this->auth                 = $auth;
        $this->logger               = $logger;
        $this->version              = $version;
        $this->activityElement      = $version->getActivityElement();
        $this->activityRepo         = $this->activityElement->getRepository();
        $this->transactionRepo      = $this->activityElement->getTransactionRepository();
        $this->resultRepo           = $this->activityElement->getResultRepository();
        $this->database             = $database;
        $this->perfectViewerManager = $perfectViewerManager;
    }

    /**
     * insert activity identifier
     * @param array $input
     * @param       $organizationId
     * @param array $defaultFieldValues
     * @return bool
     */
    public function store(array $input, $organizationId, array $defaultFieldValues)
    {
        try {
            $this->database->beginTransaction();
            $result = $this->activityRepo->store($input, $organizationId, $defaultFieldValues);
            $this->activityRepo->saveDefaultValues($result->id, $defaultFieldValues);
            $this->database->commit();
            $this->logger->info(
                'Activity identifier added',
                ['for ' => $input['activity_identifier']]
            );
            $this->logger->activity(
                "activity.added",
                [
                    'identifier'      => $input['activity_identifier'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return $result;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['ActivityIdentifier' => $input]);
        }

        return false;
    }

    /**
     * @param $organizationId
     * @return Activity Model
     */
    public function getActivities($organizationId)
    {
        return $this->activityRepo->getActivities($organizationId);
    }

    /**
     * @param $activityId
     * @return Activity Model
     */
    public function getActivityData($activityId)
    {
        return $this->activityRepo->getActivityData($activityId);
    }

    /**
     * @param array    $input
     * @param Activity $activityData
     * @return bool
     */
    public function updateStatus(array $input, Activity $activityData)
    {
        try {
            if ($this->activityRepo->updateStatus($input, $activityData)) {
                $activityWorkflow = $input['activity_workflow'];
                $statusLabel      = ['Completed', 'Verified', 'Published'];
                $status           = $statusLabel[$activityWorkflow - 1];
                $this->logger->info(sprintf('Activity has been %s', $status));

                $this->logger->activity(
                    "activity.activity_status_changed",
                    [
                        'activity_id' => $activityData->id,
                        'status'      => $status,
                    ]
                );
            }

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * @param $activity_id
     * @return mixed
     */
    public function resetActivityWorkflow($activity_id)
    {
        return $this->activityRepo->resetActivityWorkflow($activity_id);
    }

    /**
     * @return mixed
     */
    public function getActivityElement()
    {
        return $this->activityElement;
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getTransactionData($activityId)
    {
        return $this->transactionRepo->getTransactionData($activityId);
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getResultData($activityId)
    {
        return $this->resultRepo->getResults($activityId);
    }

    /**
     * @param $org_id
     * @return mixed
     */
    public function getActivityPublishedFiles($org_id)
    {
        return $this->activityRepo->getActivityPublishedFiles($org_id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePublishedFile($id)
    {
        try {
            $deleted = $this->activityRepo->deletePublishedFile($id);

            $this->logger->info(
                'Activity Xml File successfully deleted.',
                [
                    'byUser'          => auth()->user()->getNameAttribute(),
                    'organization_id' => session('org_id')
                ]
            );

            return $deleted;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Activit Xml File could not be deleted due to %s', $exception->getMessage()),
                [
                    [
                        'byUser'          => auth()->user()->getNameAttribute(),
                        'organization_id' => session('org_id')
                    ]
                ]
            );

            return null;
        }
    }

    /**
     * @param $publishedId
     * @return mixed
     */
    public function updatePublishToRegister($publishedId)
    {
        return $this->activityRepo->updatePublishToRegister($publishedId);
    }

    /**
     * @param Activity $activityData
     * @return mixed
     */
    public function makePublished(Activity $activityData)
    {
        return $this->activityRepo->makePublished($activityData);
    }

    /**
     * duplicate activity
     * @param Activity $activityData
     * @return bool
     */
    public function duplicateActivityAction(Activity $activityData)
    {
        try {
            $this->database->beginTransaction();
            $activityData->save();
            $this->database->commit();
            $this->logger->info(
                'Activity has been Duplicated.',
                ['for ' => $activityData->id]
            );
            $this->logger->activity(
                "activity.activity_duplicated",
                [
                    'activity_id'     => $activityData->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['ActivityIdentifier' => $activityData]);
        }

        return false;
    }

    /**
     * @param $activityData
     * @return bool
     */
    public function destroy($activityData, $organization, &$organizationData)
    {
        try {
            $activityId   = $activityData->id;
            $organization = $activityData->organization;

            $this->database->beginTransaction();

            $activityData->transactions = $this->transactionRepo->getTransactionData($activityId);
            $activityData->results      = $this->resultRepo->getResults($activityId);
            $publishedFiles             = $organization->publishedFiles;

            $this->removeActivityAssociation($activityId, $publishedFiles);

            $activityData->delete();

            foreach ($organizationData as &$data) {
                $usedBy = array_flip($data->used_by);

                if (array_has($usedBy, $activityId)) {
                    unset($usedBy[$activityId]);
                    $data->used_by = array_flip($usedBy);

                    $data->save();
                }
            }

            $this->database->commit();

            $this->logger->info(
                'Activity has been Deleted.',
                ['for ' => $activityId]
            );

            $this->logger->activity(
                "activity.activity_deleted",
                [
                    'activity_id'     => $activityId,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ],
                $activityData->toArray()
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['ActivityIdentifier' => $activityData]);
        }

        return false;
    }

    /**
     * @param Activity $activityData
     */
    public function activityInRegistry(Activity $activityData)
    {
        $transaction      = $this->getTransaction($activityData);
        $activityStatus   = $this->getActivityStatus($activityData);
        $recipientRegion  = $this->getRecipientRegion($activityData);
        $recipientCountry = $this->getRecipientCountry($activityData);
        $sector           = $this->getSector($activityData);
        $title            = $activityData->title[0]['narrative'];
        $identifier       = $activityData->identifier['iati_identifier_text'];

        $jsonData = $this->convertIntoJson($transaction, $activityStatus, $recipientRegion, $recipientCountry, $sector, $title, $identifier);
        $this->saveActivityRegistryData($activityData, $jsonData);
    }

    /**
     * @param Activity $activityData
     * @return array
     */
    protected function getTransaction(Activity $activityData)
    {
        $arrays       = [];
        $transactions = $this->getTransactionData($activityData->id);
        foreach ($transactions as $transactionData) {
            $index = $transactionData->transaction['transaction_type'][0]['transaction_type_code'];
            $value = $transactionData->transaction['value'][0]['amount'];

            if (array_key_exists($index, $arrays)) {
                $arrays[$index] = $arrays[$index] + $value;
            } else {
                $arrays[$index] = $value;
            }

        }

        return $arrays;
    }

    /**
     * @param Activity $activityData
     * @return mixed
     */
    protected function getActivityStatus(Activity $activityData)
    {
        return $activityData->activity_status;
    }

    /**
     * @param Activity $activityData
     * @return array
     */
    protected function getRecipientRegion(Activity $activityData)
    {
        $arrays = [];
        if ($activityData->recipient_region) {
            foreach ($activityData->recipient_region as $recipientRegion) {
                $index = $recipientRegion['region_code'];

                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + 1;
                } else {
                    $arrays[$index] = 1;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param Activity $activityData
     * @return array
     */
    protected function getRecipientCountry(Activity $activityData)
    {
        $arrays = [];
        if ($activityData->recipient_country) {
            foreach ($activityData->recipient_country as $recipientCountry) {
                $index = $recipientCountry['country_code'];

                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + 1;
                } else {
                    $arrays[$index] = 1;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param Activity $activityData
     * @return array
     */
    protected function getSector(Activity $activityData)
    {
        $arrays = [];

        if ($activityData->sector) {
            foreach ($activityData->sector as $sectors) {
                foreach ($sectors['narrative'] as $narrative) {
                    $index = $narrative['narrative'];
                    if ($index != "") {
                        if (array_key_exists($index, $arrays)) {
                            $arrays[$index] = $arrays[$index] + 1;
                        } else {
                            $arrays[$index] = 1;
                        }
                    }
                }
            }
        }

        return $arrays;
    }

    /**
     * Convert the given values into a json(array).
     * @param $transaction
     * @param $activityStatus
     * @param $recipientRegion
     * @param $recipientCountry
     * @param $sector
     * @param $title
     * @param $identifier
     * @return string
     */
    public function convertIntoJson($transaction, $activityStatus, $recipientRegion, $recipientCountry, $sector, $title, $identifier)
    {
        return [
            'transaction'       => $transaction,
            'activity_status'   => $activityStatus,
            'recipient_region'  => $recipientRegion,
            'recipient_country' => $recipientCountry,
            'sector'            => $sector,
            'title'             => $title,
            'identifier'        => $identifier
        ];
    }

    /**
     * Save the Activity registry data.
     * @param $activityData
     * @param $jsonData
     */
    protected function saveActivityRegistryData($activityData, $jsonData)
    {
        return $this->activityRepo->saveActivityRegistryData($activityData, $jsonData);
    }

    /**
     * @param $organizationId
     * @return mixed
     */
    public function getDataForOrganization($organizationId)
    {
        return $this->activityRepo->getDataForOrganization($organizationId);
    }

    /**
     * @param $filename
     * @param $orgId
     * @return mixed
     */
    public function getActivityPublishedData($filename, $orgId)
    {
        return $this->activityRepo->getActivityPublished($filename, $orgId);
    }

    /**
     * @param $sector
     * @return array
     */
    public function getSectorForBulk($sector)
    {
        $arrays   = [];
        $multiple = false;
        $multiple = $this->isMultipleArray(array_keys($sector), $multiple);
        if (!$multiple) {
            $sector = [$sector];
        }

        foreach ($sector as $data) {
            if (isset($data['narrative']) && is_array($data['narrative'])) {
                foreach ($data['narrative'] as $datum) {
                    $index = $datum;
                    if (array_key_exists($index, $arrays)) {
                        $arrays[$index] = $arrays[$index] + 1;
                    } else {
                        $arrays[$index] = 1;
                    }
                }
            } else {
                if (array_key_exists('narrative', $data)) {
                    if (isset($data['narrative']) && array_key_exists($data['narrative'], $arrays)) {
                        $arrays[$data['narrative']] = $arrays[$data['narrative']] + 1;
                    } else {
                        $arrays[$data['narrative']] = 1;
                    }
                }
            }
        }

        return $arrays;
    }

    /**
     * @param $recipientCountry
     * @return array
     */
    public function getRecipientCountryForBulk($recipientCountry)
    {
        $arrays   = [];
        $multiple = false;
        $multiple = $this->isMultipleArray(array_keys($recipientCountry), $multiple);
        if ($multiple) {
            foreach ($recipientCountry as $data) {
                $index = $data['@attributes']['code'];
                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + 1;
                } else {
                    $arrays[$index] = 1;
                }
            }
        } else {
            $arrays[$recipientCountry['@attributes']['code']] = 1;
        }

        return $arrays;
    }

    /**
     * @param $transaction
     * @return array
     */
    public function getTransactionForBulk($transaction)
    {
        $arrays   = [];
        $multiple = false;
        $multiple = $this->isMultipleArray(array_keys($transaction), $multiple);
        if ($multiple) {
            foreach ($transaction as $data) {
                if (is_array($data['transaction-type'])) {
                    $index = $data['transaction-type']['@attributes']['code'];
                    $value = $data['value'];

                    if (array_key_exists($index, $arrays)) {
                        $arrays[$index] = $arrays[$index] + $value;
                    } else {
                        $arrays[$index] = $value;
                    }
                }
            }
        } else {
            $arrays[$transaction['transaction-type']['@attributes']['code']] = $transaction['value'];
        }

        return $arrays;
    }

    /**
     * @param $activityId
     * @param $jsonData
     * @return mixed
     */
    public function saveBulkPublishDataInActivityRegistry($activityId, $jsonData)
    {
        return $this->activityRepo->saveBulkPublishDataInActivityRegistry($activityId, $jsonData);
    }

    /**
     * @param Activity $activity
     * @param          $element
     * @return bool
     */
    public function deleteElement(Activity $activity, $element)
    {
        try {
            $this->database->beginTransaction();
            $this->activityRepo->deleteElement($activity, $element);
            $this->database->commit();
            $this->logger->info(
                sprintf('Activity element %s has been deleted.', $element),
                ['for ' => $activity->id]
            );
            $this->logger->activity(
                "activity.activity_element_deleted",
                [
                    'element'         => $element,
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception);
        }

        return false;
    }

    /**
     * write brief description
     * @param $keys
     * @param $multiple
     * @return mixed
     */
    public function isMultipleArray($keys, $multiple)
    {
        array_walk(
            $keys,
            function ($key, $index) use (&$multiple) {
                if (is_int($key)) {
                    $multiple = true;
                }
            }
        );

        return $multiple;
    }

    /**
     * Remove deleted Activities from published_activities column of ActivityPublished table.
     * @param $activityId
     * @param $publishedFiles
     */
    protected function removeActivityAssociation($activityId, $publishedFiles)
    {
        foreach ($publishedFiles as $publishedFile) {
            $containedActivities = $publishedFile->extractActivityId();

            foreach ($containedActivities as $id => $filename) {
                if ($id == $activityId) {
                    $containedActivities = array_except($containedActivities, $id);
                }

                $publishedFile->published_activities = $containedActivities;
                $publishedFile->save();
            }
        }
    }

    /**
     * Remove sector details from the activity.
     * @param $activityId
     * @return bool
     */
    public function removeActivitySector($activityId)
    {
        try {
            $this->activityRepo->removeActivitySector($activityId);
            $this->logger->info(
                'Sector has been removed from Activity level',
                ['for ' => $activityId]
            );
            $this->logger->activity(
                "activity.activity_sector_removed"
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception);
        }

        return false;
    }

    /**
     * Remove all the sector details from every transactions of the activity.
     * @param $activityId
     * @return bool
     */
    public function removeTransactionSector($activityId)
    {
        try {
            $transactions = $this->transactionRepo->getTransactionData($activityId);
            $this->activityRepo->removeTransactionSector($transactions);
            $this->logger->info(
                'Sector has been removed from Transaction level',
                ['for ' => $activityId]
            );
            $this->logger->activity(
                "activity.transaction_sector_removed"
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception);
        }

        return false;
    }

    public function setAsPublished($activityId)
    {
        try {
            $this->database->beginTransaction();
            $activity                        = $this->getActivityData($activityId);
            $activity->published_to_registry = 1;
            $activity->save();

            $this->activityInRegistry($activity);
            $this->perfectViewerManager->createSnapshot($activity);

            $this->database->commit();
        } catch (Exception $exception) {
            $this->database->rollback();
        }
    }
}
