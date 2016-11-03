<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\ActivityDocumentLink;
use Illuminate\Support\Collection;

/**
 * Class DocumentLink
 * @package App\Core\V201\Repositories\Activity
 */
class DocumentLink
{
    /**
     * @var ActivityDocumentLink
     */
    protected $activityDocumentLink;

    /**
     * @param ActivityDocumentLink $activityDocumentLink
     */
    function __construct(ActivityDocumentLink $activityDocumentLink)
    {
        $this->activityDocumentLink = $activityDocumentLink;
    }

    /**
     * Store activity document link
     * @param array                $documentLink
     * @param ActivityDocumentLink $activityDocumentLink
     * @return bool
     */
    public function update(array $documentLink, ActivityDocumentLink $activityDocumentLink)
    {
        $activityDocumentLink->document_link = $documentLink[0];

        return $activityDocumentLink->save();
    }

    /**
     * Return specific document link
     * @param $documentLinkId
     * @param $activityId
     * @return ActivityDocumentLink
     */
    public function getDocumentLink($documentLinkId, $activityId)
    {
        return $this->activityDocumentLink->firstOrNew(['id' => $documentLinkId, 'activity_id' => $activityId]);
    }


    /**
     * @param $activityId
     * @return mixed
     */
    public function getDocumentLinks($activityId)
    {
        return $this->activityDocumentLink->where('activity_id', $activityId)->get();
    }

    /**
     * Delete specific activity document link
     * @param ActivityDocumentLink $activityDocumentLink
     * @return bool|null
     */
    public function delete(ActivityDocumentLink $activityDocumentLink)
    {
        return $activityDocumentLink->delete();
    }

    public function xmlDocumentLink($documentLink, $activityId)
    {
        $this->activityDocumentLink->create(['document_link' => $documentLink['document_link'], 'activity_id' => $activityId]);
    }
}
