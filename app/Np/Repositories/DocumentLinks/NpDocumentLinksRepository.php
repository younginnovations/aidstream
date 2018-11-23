<?php namespace App\Np\Repositories\DocumentLinks;


use App\Np\Contracts\NpDocumentLinkRepositoryInterface;
use App\Models\Activity\ActivityDocumentLink;

/**
 * Class DocumentLinksRepository
 * @package App\Np\Repositories\DocumentLinks
 */
class NpDocumentLinksRepository implements NpDocumentLinkRepositoryInterface
{
    /**
     * @var ActivityDocumentLink
     */
    private $documentLink;

    /**
     * DocumentLinksRepository constructor.
     * @param ActivityDocumentLink $documentLink
     */
    public function __construct(ActivityDocumentLink $documentLink)
    {
        $this->documentLink = $documentLink;
    }

    /**
     * Returns all documentLinks of provided activity.
     *
     * @param $activityId
     * @return mixed
     */
    public function all($activityId)
    {
        return $this->documentLink->where('activity_id', $activityId)->get();
    }

    /**
     * Saves the document link data into database.
     *
     * @param array $data
     * @param       $activityId
     * @return boolean
     */
    public function save(array $data, $activityId)
    {
        foreach (getVal($data, ['document_link'], []) as $index => $documentLink) {
            $this->documentLink->create(['activity_id' => $activityId, 'document_link' => $documentLink]);
        }

        return true;
    }

    /**
     * Find the details of the provided id of document link.
     *
     * @param $documentLinkId
     * @return mixed
     */
    public function find($documentLinkId)
    {
        return $this->documentLink->findOrFail($documentLinkId);
    }

    /**
     * Update the details of the document link
     *
     * @param array $data
     * @param       $activityId
     * @return boolean
     */
    public function update(array $data, $activityId)
    {
        foreach (getVal($data, ['document_link'], []) as $dataIndex => $documentLink) {
            $id = getVal($documentLink, ['id']);
            if ($id) {
                $documentLinkModel = $this->find($id);
                unset($documentLink['id']);

                $documentLinkModel->update(['document_link' => $documentLink]);
            } else {
                $this->documentLink->create(['activity_id' => $activityId, 'document_link' => $documentLink]);
            }
        }

        return true;
    }
}

