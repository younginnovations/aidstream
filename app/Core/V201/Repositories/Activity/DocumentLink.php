<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class DocumentLink
 * @package App\Core\V201\Repositories\Activity
 */
class DocumentLink
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @param Activity $activity
     */
    function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * update Document Link
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->document_link = $activityDetails['document_link'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getDocumentLinkData($activityId)
    {
        return $this->activity->findOrFail($activityId)->document_link;
    }
}
