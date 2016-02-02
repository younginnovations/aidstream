<?php
namespace App\Migration;
use App\Models\Document as DocumentModel;
use App\Migration\MigrateHelper;
use DB;

class DocumentLink {
    protected $documentModel;
    protected $mysqlConn;

    function __construct(DocumentModel $documentModel) {
        $this->mysqlConn = DB::connection('mysql');
        $this->documentModel = $documentModel;
    }

    public function fetchDocumentLink() {
        $accountId = '557';
        $commonClass = new MigrateHelper();
        $OrgId = $commonClass->fetchOrgId(557); // inserting account_id manually
        $activities_id = $commonClass->fetchActivity();
        foreach($activities_id as $key=>$value) {
            $activity_id = $value->id;
          //fetch document link
            $docData = $this->mysqlConn->table('iati_document_link')
                                   ->select('@url as url','activity_id','id')
                                   ->where('activity_id','=',$activity_id)
                                   ->get();

            foreach($docData as $key=>$value) {
                $doc = $this->documentModel->firstOrNew(['url' => $value->url]);
                $activities = (array) $doc->activities;
                $activities[$activity_id] = $commonClass->ActivityIdentifier($activity_id)->activity_identifier;
               // dd($activities);
                $doc->activities = $activities;
                $url = $value->url;
                $res = explode("/", $url);
                $rr = end($res);
                $doc->filename = $rr;
               // dd($doc);
                $doc->url = $url;
                $doc->org_id = $OrgId;
                $doc->created_at = time();
                $doc->updated_at = time();
                $doc->save();
                //dd($doc);
            }
        }
    }
}
?>