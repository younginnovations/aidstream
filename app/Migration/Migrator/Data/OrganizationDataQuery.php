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
        $this->data = [];

        return $this->fetchName($organizationId, $accountId)
                    ->fetchStatus($organizationId, $accountId)
                    ->fetchDocumentLink($organizationId);
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return $this
     */
    protected function fetchName($organizationId, $accountId)
    {
        $dataName           = null;
        $fetchNameInstances = $this->connection->table('iati_organisation/name')
                                               ->select('*')
                                               ->where('organisation_id', '=', $organizationId)
                                               ->get();

        foreach ($fetchNameInstances as $eachName) {
            $id             = $eachName->id;
            $nameNarratives = fetchNarratives($id, 'iati_organisation/name/narrative', 'name_id');

            $dataName[] = $this->name->format($nameNarratives);
        }

        $this->data[$organizationId]['name']            = $dataName;
        $this->data[$organizationId]['organization_id'] = (int) $accountId;

        return $this;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return $this
     */
    protected function fetchStatus($organizationId, $accountId)
    {
        $status       = 0;
        $fetchStateId = getBuilderFor('state_id', 'iati_organisation', 'account_id', $accountId)->first();

        if (!is_null($fetchStateId)) {
            $status = $fetchStateId->state_id - 1;
        }

        $this->data[$organizationId]['status'] = $status;

        return $this;
    }

    /**
     * @param $organizationId
     * @return array
     */
    protected function fetchDocumentLink($organizationId)
    {
        $documentLinks    = $this->connection->table('iati_organisation/document_link')
                                             ->select('*', '@url as url', '@format as format')
                                             ->where('organisation_id', '=', $organizationId)
                                             ->get();
        $documentLinkData = [];

        foreach ($documentLinks as $documentLink) {
            $documentLinkId       = $documentLink->id;
            $url                  = $documentLink->url;
            $format               = fetchCode($documentLink->format, 'FileFormat', '');
            $categoryData         = $this->fetchDocumentLinkCategory($documentLinkId);
            $languageData         = $this->fetchDocumentLinkLanguage($documentLinkId);
            $recipientCountryData = $this->fetchDocumentLinkRecipientCountry($documentLinkId);
            $titleNarrativeData   = $this->fetchDocumentLinkTitleNarrative($documentLinkId);

            $documentLinkData[] = [
                'url'               => $url,
                'format'            => $format,
                'narrative'         => $titleNarrativeData,
                'category'          => $categoryData,
                'language'          => $languageData,
                'recipient_country' => $recipientCountryData
            ];
        }
        $this->data[$organizationId]['document_link'] = $documentLinkData;

        return $this->data;
    }

    /**
     * @param $documentLinkId
     * @return array
     */
    protected function fetchDocumentLinkTitleNarrative($documentLinkId)
    {
        $narrativeData   = [['narrative' => "", 'language' => ""]];
        $titleNarratives = getBuilderFor('id', 'iati_organisation/document_link/title', 'document_link_id', $documentLinkId)->first();
        if ($titleNarratives) {
            $narratives    = fetchNarratives($titleNarratives->id, 'iati_organisation/document_link/title/narrative', 'title_id');
            $narrativeData = fetchAnyNarratives($narratives);
        }

        return $narrativeData;
    }

    /**
     * @param $documentLinkId
     * @return array
     */
    protected function fetchDocumentLinkCategory($documentLinkId)
    {
        $categories   = fetchDataWithCodeFrom('iati_organisation/document_link/category', 'document_link_id', $documentLinkId);
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryData[] = ['code' => fetchCode($category->code, 'DocumentCategory', '')];
        }

        return $categoryData;
    }

    /**
     * @param $documentLinkId
     * @return array
     */
    protected function fetchDocumentLinkLanguage($documentLinkId)
    {
        $languages    = fetchDataWithCodeFrom('iati_organisation/document_link/language', 'document_link_id', $documentLinkId);
        $languageData = [];

        foreach ($languages as $language) {
            $languageData[] = ['language' => getLanguageCodeFor($language->code)];
        }

        return $languageData;
    }

    /**
     * @param $documentLinkId
     * @return array
     */
    protected function fetchDocumentLinkRecipientCountry($documentLinkId)
    {
        $recipientCountryData = [];
        $recipientCountries   = fetchDataWithCodeFrom('iati_organisation/document_link/recipient_country', 'document_link_id', $documentLinkId);

        foreach ($recipientCountries as $recipientCountry) {
            $recipientCountryCode       = fetchCode($recipientCountry->code, 'Country', '');
            $narratives                 = fetchNarratives($recipientCountry->id, 'iati_organisation/document_link/recipient_country/narrative', 'recipient_country_id');
            $recipientCountryNarratives = fetchAnyNarratives($narratives);
            $recipientCountryData[]     = ['code' => $recipientCountryCode, 'narrative' => $recipientCountryNarratives];
        }

        return $recipientCountryData;
    }
}
