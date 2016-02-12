<?php namespace App\Migration\Migrator\Data;


use App\Migration\Elements\OrganizationData\Name;

/**
 * Class OrganizationDataQuery
 * @package App\Migration\Migrator\Data
 */
class OrganizationDataQuery extends Query
{
    /**
     * @var Name
     */
    protected $name;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * OrganizationDataQuery constructor.
     * @param Name $name
     */
    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $data = [];
        $this->initDBConnection();

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return array
     */
    protected function getData($organizationId, $accountId)
    {
        return $this->fetchName($organizationId, $accountId)
                    ->fetchStatus($organizationId, $accountId);
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return $this
     */
    protected function fetchName($organizationId, $accountId)
    {
        $language           = "";
        $dataName           = null;
        $fetchNameInstances = $this->connection->table('iati_organisation/name')
                                               ->select('*')
                                               ->where('organisation_id', '=', $organizationId)
                                               ->get();

        foreach ($fetchNameInstances as $eachName) {
            $id             = $eachName->id;
            $nameNarratives = fetchNarratives($id, 'iati_organisation/name/narrative', 'name_id');
            $Narrative      = [];

            foreach ($nameNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }

            $narrative = $this->name->format($Narrative, $nameNarratives);

            if (!is_null($fetchNameInstances)) {
                $this->data[$organizationId]['name'] = $narrative;
            }
        }

        $this->data[$organizationId]['organization_id'] = (int) $accountId;

        return $this;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return array
     */
    protected function fetchStatus($organizationId, $accountId)
    {
        $status       = 0;
        $fetchStateId = getBuilderFor('state_id', 'iati_organisation', 'account_id', $accountId)->first();

        if (!is_null($fetchStateId)) {
            $status   = $fetchStateId->state_id - 1;
        }

        $this->data[$organizationId]['status'] = $status;

        return $this->data;
    }
}
