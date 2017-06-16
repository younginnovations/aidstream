<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInRegistry;
use App\Models\ActivityPublished;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;

/**
 * Class ActivityRepository
 * @package app\Core\V201\Repositories\Activity
 */
class ActivityRepository
{
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param Activity           $activity
     * @param ActivityPublished  $activityPublished
     * @param ActivityInRegistry $activityInRegistry
     * @param Settings           $settings
     * @internal param SettingsManager $settingsManager
     */
    public function __construct(Activity $activity, ActivityPublished $activityPublished, ActivityInRegistry $activityInRegistry, Settings $settings)
    {
        $this->activity           = $activity;
        $this->activityPublished  = $activityPublished;
        $this->activityInRegistry = $activityInRegistry;
        $this->settings           = $settings;
    }

    /**
     * insert activity data to database
     * @param array $input
     * @param       $organizationId
     * @param array $defaultFieldValues
     * @return modal
     */
    public function store(array $input, $organizationId, array $defaultFieldValues)
    {
        unset($input['_token']);

        return $this->activity->create(
            [
                'identifier'           => $input,
                'organization_id'      => $organizationId,
                'default_field_values' => $defaultFieldValues
            ]
        );
    }

    /**
     * @param $organizationId
     * @return modal
     */
    public function getActivities($organizationId)
    {
        return $this->activity->where('organization_id', $organizationId)->orderBy('updated_at', 'desc')->get();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityData($activityId)
    {
        return $this->activity->findorFail($activityId);
    }

    /**
     * @param array    $input
     * @param Activity $activityData
     * @return bool
     */
    public function updateStatus(array $input, Activity $activityData)
    {
        $activityData->activity_workflow = $input['activity_workflow'];

        return $activityData->save();
    }

    /**
     * @param $activity_id
     */
    public function resetActivityWorkflow($activity_id)
    {
        $this->activity->whereId($activity_id)->update(['activity_workflow' => 0]);
    }

    /**
     * @param $org_id
     * @return mixed
     */
    public function getActivityPublishedFiles($org_id)
    {
        return $this->activityPublished->whereOrganizationId($org_id)->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePublishedFile($id)
    {
        $result = $this->activityPublished->find($id);
        if ($result) {
            $file   = public_path('uploads/files/activity/' . $result->filename);
            $result = $result->delete();
            if ($result && file_exists($file)) {
                unlink($file);
            }
        }

        return $result;
    }

    /**
     * @param $publishedId
     * @return mixed
     */
    public function updatePublishToRegister($publishedId)
    {
        $activityPublished = $this->activityPublished->find($publishedId);
        $activityPublished->update(['published_to_register' => 1]);

        $this->updateActivityData($activityPublished->published_activities);
    }

    /**
     * @param       $activityId
     * @param array $defaultFieldValues
     * @return mixed
     */
    public function saveDefaultValues($activityId, array $defaultFieldValues)
    {
        $activity                       = $this->activity->find($activityId);
        $activity->collaboration_type   = $defaultFieldValues[0]['default_collaboration_type'];
        $activity->default_flow_type    = $defaultFieldValues[0]['default_flow_type'];
        $activity->default_finance_type = $defaultFieldValues[0]['default_finance_type'];
        $activity->default_aid_type     = $defaultFieldValues[0]['default_aid_type'];
        $activity->default_tied_status  = $defaultFieldValues[0]['default_tied_status'];

        return $activity->save();
    }

    /**
     * @param Activity $activityData
     * @return bool
     */
    public function makePublished(Activity $activityData)
    {
        $activityData->published_to_registry = 1;

        return $activityData->save();
    }

    /**
     * @param $activityData
     * @param $jsonData
     * @return bool
     */
    public function saveActivityRegistryData($activityData, $jsonData)
    {
        $activityInRegistry                  = $this->activityInRegistry->firstOrNew(['activity_id' => $activityData->id]);
        $activityInRegistry->organization_id = session('org_id');
        $activityInRegistry->activity_id     = $activityData->id;
        $activityInRegistry->activity_data   = $jsonData;
        if ($activityInRegistry->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param $organizationId
     * @return mixed
     */
    public function getDataForOrganization($organizationId)
    {
        return $this->activityInRegistry->whereOrganizationId($organizationId)->get();
    }

    /**
     * @param $filename
     * @param $orgId
     * @return mixed
     */
    public function getActivityPublished($filename, $orgId)
    {
        return $this->activityPublished->where('filename', '=', $filename)
                                       ->whereOrganizationId($orgId)
                                       ->first();
    }

    /**
     * @param $activityId
     * @param $jsonData
     * @return bool
     */
    public function saveBulkPublishDataInActivityRegistry($activityId, $jsonData)
    {
        $activityInRegistry                  = $this->activityInRegistry->firstOrNew(['activity_id' => $activityId]);
        $activityInRegistry->organization_id = session('org_id');
        $activityInRegistry->activity_id     = $activityId;
        $activityInRegistry->activity_data   = $jsonData;

        return $activityInRegistry->save();
    }

    /**
     * @param Activity $activity
     * @param          $element
     * @return bool
     */
    public function deleteElement(Activity $activity, $element)
    {
        if ($element === 'result') {
            $activity->results()->delete();

            return $activity->save();
        }

        $activity->$element = null;

        return $activity->save();
    }

    /**
     * Update published_to_registry field for ActivityData table.
     * @param $publishedActivities
     */
    protected function updateActivityData($publishedActivities)
    {
        foreach ($publishedActivities as $id => $activityFile) {
            $activity = $this->activity->findOrFail($id);

            $activity->update(['published_to_registry' => 1]);
        }
    }

    /**
     * Remove sector details from the activity.
     * @param $activityId
     */
    public function removeActivitySector($activityId)
    {
        $activity         = $this->getActivityData($activityId);
        $activity->sector = null;
        $activity->save();
    }

    /**
     * Remove all the sector details from every transactions of the activity.
     * @param $transactions
     * @internal param $activityId
     */
    public function removeTransactionSector($transactions)
    {
        foreach ($transactions as $transaction) {
            $transaction_info           = $transaction->transaction;
            $transactionSectors         = $transaction_info['sector'];
            $transaction_info['sector'] = $this->removeSector($transactionSectors);
            $transaction->transaction   = $transaction_info;
            $transaction->save();
        }
    }

    /**
     * Remove sector information of the transaction.
     * @param $transactionSectors
     * @return array
     */
    public function removeSector($transactionSectors)
    {
        $sectors = [];
        foreach ($transactionSectors as $key => $sector) {
            $sectors[0]['sector_vocabulary']    = '';
            $sectors[0]['sector_code']          = '';
            $sectors[0]['sector_category_code'] = '';
            $sectors[0]['sector_text']          = '';
            if (session('version') != 'V201') {
                $sectors[0]['vocabulary_uri'] = '';
            }
            $sectors[0]['narrative'] = $this->removeTransactionSectorNarrative($sector);
        }

        return $sectors;
    }

    /**
     * Remove narrative of the sector of the transaction.
     * @param $sectorNarratives
     * @return array
     */
    public function removeTransactionSectorNarrative($sectorNarratives)
    {
        $narratives            = [];
        $transactionNarratives = $sectorNarratives['narrative'];

        foreach ($transactionNarratives as $narrative) {
            $narratives[0]['narrative'] = '';
            $narratives[0]['language']  = '';
        }

        return $narratives;
    }

    /**
     * Create Activity from the csv data.
     * @param $activityData
     * @return Activity
     */
    public function createActivity($activityData)
    {
        $defaultFieldValues = $this->setDefaultFieldValues($activityData['default_field_values'], $activityData['organization_id']);

                return $this->activity->create(
            [
                'identifier'                 => $activityData['identifier'],
                'title'                      => array_values($activityData['title']),
                'description'                => array_values($activityData['description']),
                'activity_status'            => $activityData['activity_status'],
                'activity_date'              => array_values($activityData['activity_date']),
                'participating_organization' => array_values($activityData['participating_organization']),
                'recipient_country'          => array_values($activityData['recipient_country']),
                'recipient_region'           => array_values($activityData['recipient_region']),
                'sector'                     => array_values($activityData['sector']),
                'organization_id'            => $activityData['organization_id'],
                'policy_marker'              => array_key_exists('policy_marker', $activityData) ? array_values($activityData['policy_marker']) : null,
                'budget'                     => array_key_exists('budget', $activityData) ? array_values($activityData['budget']) : null,
                'activity_scope'             => array_key_exists('activity_scope', $activityData) ? array_get($activityData, 'activity_scope') : null,
                'default_field_values'       => $defaultFieldValues,
                'collaboration_type'         => getVal(array_values($defaultFieldValues), [0, 'default_collaboration_type'], null),
                'default_flow_type'          => getVal(array_values($defaultFieldValues), [0, 'default_flow_type'], null),
                'default_finance_type'       => getVal(array_values($defaultFieldValues), [0, 'default_finance_type'], null),
                'default_aid_type'           => getVal(array_values($defaultFieldValues), [0, 'default_aid_type'], null),
                'default_tied_status'        => getVal(array_values($defaultFieldValues), [0, 'default_tied_status'], null),
                'contact_info'               => getVal(array_values($activityData), ['contact_info'], null),
                'related_activity'           => getVal(array_values($activityData), ['related_activity'], null)
            ]
        );
    }

    /**
     * Set Default values for the imported csv activities.
     * @param $defaultFieldValues
     * @param $organizationId
     * @return mixed
     */
    protected function setDefaultFieldValues($defaultFieldValues, $organizationId)
    {
        $settings                   = $this->settings->where('organization_id', $organizationId)->first();
        $settingsDefaultFieldValues = $settings->default_field_values;

        foreach ($defaultFieldValues as $index => $value) {
            $settingsDefaultFieldValues[0]['default_currency'] = (($currency = getVal((array) $defaultFieldValues, [$index, 'default_currency'])) == '')
                ? getVal((array) $settingsDefaultFieldValues, [0, 'default_currency']) : $currency;
            $settingsDefaultFieldValues[0]['default_language'] = (($language = getVal((array) $defaultFieldValues, [$index, 'default_language'])) == '')
                ? getVal((array) $settingsDefaultFieldValues, [0, 'default_language']) : $language;
            $settingsDefaultFieldValues[0]['humanitarian']     = (($humanitarian = getVal((array) $defaultFieldValues, [$index, 'humanitarian'])) == '')
                ? getVal((array) $settingsDefaultFieldValues, [0, 'humanitarian']) : $humanitarian;
        }

        return $settingsDefaultFieldValues;
    }

    /**
     * @param array $mappedActivity
     * @param       $organizationId
     * @return static
     */
    public function importXmlActivities(array $mappedActivity, $organizationId)
    {
        $mappedActivity['default_field_values'] = $this->setDefaultFieldValues($mappedActivity['default_field_values'], $organizationId);
        unset($mappedActivity['document_link']);

        return $this->activity->create($mappedActivity);
    }

    /**
     * Provides activity identifiers
     *
     * @param $orgId
     * @return mixed
     */
    public function getActivityIdentifiers($orgId)
    {
        return $this->activity->selectRaw("identifier ->> 'activity_identifier'")->where('organization_id', $orgId)->get();
    }

    /**
     * Updates activity with Identifier
     *
     * @param       $oldActivity
     * @param array $value
     * @return mixed
     */
    public function updateActivityWithIdentifier($oldActivity, array $value)
    {
        $activity = $this->emptyExisting($oldActivity);
        $activity = $this->fillActivity($activity, $value);

        $activity->activity_workflow = 0;
        $activity->update();

        return $activity;
    }

    /**
     * Empty all values for an Activity.
     * @param $activity
     * @return mixed
     */
    protected function emptyExisting($activity)
    {
        foreach ($activity->getAttributes() as $column => $data) {
            if ($this->isNullable($column)) {
                $activity->{$column} = null;
            }
        }

        return $activity;
    }

    /**
     * Fill in default activity attributes
     *
     * @param $activity
     * @param $activityData
     * @return array
     */
    protected function fillActivity($activity, $activityData)
    {
        $defaultFieldValues = $this->setDefaultFieldValues($activityData['default_field_values'], $activityData['organization_id']);

        $activity->identifier                 = $activityData['identifier'];
        $activity->title                      = array_values($activityData['title']);
        $activity->description                = array_values($activityData['description']);
        $activity->activity_status            = $activityData['activity_status'];
        $activity->activity_date              = array_values($activityData['activity_date']);
        $activity->participating_organization = array_values($activityData['participating_organization']);
        $activity->recipient_country          = array_values($activityData['recipient_country']);
        $activity->recipient_region           = array_values($activityData['recipient_region']);
        $activity->sector                     = array_values($activityData['sector']);
        $activity->organization_id            = $activityData['organization_id'];
        $activity->policy_marker              = array_key_exists('policy_marker', $activityData) ? array_values($activityData['policy_marker']) : null;
        $activity->budget                     = array_key_exists('budget', $activityData) ? array_values($activityData['budget']) : null;
        $activity->activity_scope             = array_key_exists('activity_scope', $activityData) ? array_values($activityData['activity_scope']) : null;
        $activity->default_field_values       = $defaultFieldValues;
        $activity->collaboration_type         = getVal(array_values($defaultFieldValues), [0, 'default_collaboration_type'], null);
        $activity->default_flow_type          = getVal(array_values($defaultFieldValues), [0, 'default_flow_type'], null);
        $activity->default_finance_type       = getVal(array_values($defaultFieldValues), [0, 'default_finance_type'], null);
        $activity->default_aid_type           = getVal(array_values($defaultFieldValues), [0, 'default_aid_type'], null);
        $activity->default_tied_status        = getVal(array_values($defaultFieldValues), [0, 'default_tied_status'], null);
        $activity->contact_info               = getVal(array_values($activityData), ['contact_info'], null);
        $activity->related_activity           = getVal(array_values($activityData), ['related_activity'], null);

        return $activity;
    }

    /**
     * Check if the column is nullabe.
     *
     * @param $column
     * @return bool
     */
    protected function isNullable($column)
    {
        $defaultValues = ['created_at', 'default_field_values', 'activity_workflow', 'published_to_registry', 'id', 'organization_id'];

        return !array_key_exists($column, array_flip($defaultValues));
    }

    /**
     * Provides Activity from its Identifier
     *
     * @param $identifier
     * @return mixed
     */
    public function getActivityFromIdentifier($identifier, $organizationId)
    {
        return $this->activity->whereRaw(sprintf("identifier #>> '{activity_identifier}' = '%s'", $identifier))
                              ->where('organization_id', '=', $organizationId)
                              ->first();
    }
}
