<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
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
    private $database;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param Logger          $logger
     * @param DatabaseManager $database
     */
    public function __construct(Version $version, Guard $auth, Logger $logger, DatabaseManager $database)
    {
        $this->auth            = $auth;
        $this->logger          = $logger;
        $this->version         = $version;
        $this->activityElement = $version->getActivityElement();
        $this->activityRepo    = $this->activityElement->getRepository();
        $this->transactionRepo = $this->activityElement->getTransactionRepository();
        $this->resultRepo      = $this->activityElement->getResultRepository();
        $this->database        = $database;
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
            $this->logger->error(
                sprintf('Activity identifier couldn\'t be added due to %s', $exception->getMessage()),
                [
                    'ActivityIdentifier' => $input,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
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
     */
    public function updateStatus(array $input, Activity $activityData)
    {
        $result = $this->activityRepo->updateStatus($input, $activityData);
        if ($result) {
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

        return $result;
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
        return $this->activityRepo->deletePublishedFile($id);
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
            $this->logger->error(
                sprintf('Activity couldn\'t be duplicated due to %s', $exception->getMessage()),
                [
                    'ActivityIdentifier' => $activityData,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $activityData
     * @return bool
     */
    public function destroy($activityData)
    {
        try {
            $this->database->beginTransaction();
            $activityData->delete();
            $this->database->commit();
            $this->logger->info(
                'Activity has been Deleted.',
                ['for ' => $activityData->id]
            );
            $this->logger->activity(
                "activity.activity_deleted",
                [
                    'activity_id'     => $activityData->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity couldn\'t be deleted due to %s', $exception->getMessage()),
                [
                    'ActivityIdentifier' => $activityData,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
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
    protected function convertIntoJson($transaction, $activityStatus, $recipientRegion, $recipientCountry, $sector, $title, $identifier)
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
}
