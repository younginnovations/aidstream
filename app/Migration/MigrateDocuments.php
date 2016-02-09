<?php namespace App\Migration;

use App\Migration\Elements\Document;
use App\Models\Document as DocumentModel;
use Illuminate\Database\DatabaseManager;

class MigrateDocuments
{
    protected $DocumentModel;
    protected $migrateHelper;
    protected $mysqlConn;
    protected $activityData;
    protected $document;

    function __construct(DocumentModel $DocumentModel, MigrateHelper $migrateHelper, ActivityData $activityData, Document $document)
    {
        $this->migrateHelper = $migrateHelper;
        $this->DocumentModel = $DocumentModel;
        $this->activityData  = $activityData;
        $this->document      = $document;
    }

    public function docDataFetch($orgId)
    {
        $this->initDBConnection('mysql');

        $formattedData = [];
        $document      = [];
        $activities    = $this->activityData->getActivitiesFor($orgId);  // for 1 org

        foreach ($activities as $key => $value) {
            $temp        = [];
            $activity_id = $value->id;
            //fetch document link
            $docData = $this->mysqlConn->table('iati_document_link')
                                       ->select('@url as url', 'activity_id', 'id')
                                       ->where('activity_id', '=', $activity_id)
                                       ->get();

            foreach ($docData as $data) {
                $temp[$activity_id] = $this->migrateHelper->ActivityIdentifier($activity_id)->activity_identifier;
                $url                = $data->url;
                $res                = explode("/", $url);
                $filename           = end($res);
                $document[$url]     = array(
                    'filename'   => $filename,
                    'url'        => $url,
                    'org_id'     => $orgId,
                    'activities' => $temp
                );
            }
        }
        $formattedData[] = $this->document->format($document);

        return $formattedData;
    }

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }
}
