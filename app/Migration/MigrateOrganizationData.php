<?php namespace App\Migration;

use App\Migration\Elements\OrganizationData\Name;
use Illuminate\Database\DatabaseManager;
use App\Migration\MigrateHelper;

class MigrateOrganizationData
{
    protected $mysqlConn;
    protected $migrateHelper;
    protected $data;
    protected $name;

    function __construct(MigrateHelper $migrateHelper, Name $name)
    {
        $this->data          = [];
        $this->migrateHelper = $migrateHelper;
        $this->name          = $name;
    }

    public function OrganizationDataFetch($orgId, $accountId)
    {
        $this->initDBConnection('mysql');

        $this->data = [];

        $this->fetchName($orgId, $accountId)
             ->fetchStatus($orgId, $accountId);

        return $this->data;
    }

    public function fetchName($orgId, $accountId)
    {
        $narrative = null;
        $language           = "";
        $dataName           = null;
        $fetchNameInstances = $this->mysqlConn->table('iati_organisation/name')
                                              ->select('*')
                                              ->where('organisation_id', '=', $orgId)
                                              ->get();

        foreach ($fetchNameInstances as $eachName) {
            $id             = $eachName->id;
            $nameNarratives = $this->migrateHelper->fetchNarratives($id, 'iati_organisation/name/narrative', 'name_id');
            $Narrative      = [];

            foreach ($nameNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;
                if ($eachNarrative->xml_lang != "") {
                    $language = $this->migrateHelper->FetchLangCode($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }

            $narrative[] = $this->name->format($Narrative, $nameNarratives);
        }
        if (!is_null($fetchNameInstances)) {
            $this->data[$orgId]['name'] = $narrative;
        }
        
        $this->data[$orgId]['organization_id'] = (int) $accountId;

        return $this;
    }

    public function fetchStatus($orgId, $accountId)
    {
        $status       = 0;
        $fetchStateId = $this->migrateHelper->fetchAnyField('state_id', 'iati_organisation', 'account_id', $accountId)->first();

        if (!is_null($fetchStateId)) {
            $state_id = $fetchStateId->state_id;
            $status   = $state_id - 1;
        }

        $this->data[$orgId]['status'] = $status;

        return $this->data;
    }

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }
}