<?php namespace App\Migration\Migrator\Data;


use App\Migration\ActivityData;
use App\Migration\Elements\Document;

class DocumentQuery extends Query
{
    protected $activityData;

    /**
     * @var Document
     */
    protected $document;

    public function __construct(ActivityData $activityData, Document $document)
    {
        $this->activityData = $activityData;
        $this->document     = $document;
    }

    public function executeFor(array $accountIds)
    {
        $this->initDBConnection();
        $data = [];

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    public function getData($organizationId, $accountId)
    {
        $formattedData = [];
        $document      = [];
        $activities    = $this->activityData->getActivitiesFor($organizationId);  // for 1 org

        foreach ($activities as $key => $value) {
            $temp        = [];
            $activity_id = $value->id;
            //fetch document link
            $docData = $this->connection->table('iati_document_link')
                                        ->select('@url as url', 'activity_id', 'id')
                                        ->where('activity_id', '=', $activity_id)
                                        ->get();

            foreach ($docData as $data) {
                $temp[$activity_id] = getActivityIdentifier($activity_id)->activity_identifier;

                $url            = $data->url;
                $res            = explode("/", $url);
                $filename       = end($res);
                $document[$url] = array(
                    'filename'   => $filename,
                    'url'        => $url,
                    'org_id'     => $accountId,
                    'activities' => $temp
                );
            }
        }
        $formattedData[] = $this->document->format($document);

        return $formattedData;
    }
}
