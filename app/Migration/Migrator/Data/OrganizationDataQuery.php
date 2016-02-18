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
        $this->fetchName($organizationId, $accountId)
             ->fetchStatus($organizationId, $accountId)
             ->fetchTotalBudget($organizationId)
             ->fetchRecipientOrgBudget($organizationId)
             ->fetchDocumentLink($organizationId);

        return $this->data;
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
     * @return $this
     */
    protected function fetchTotalBudget($organizationId)
    {
        $table           = 'iati_organisation/total_budget';
        $totalBudgets    = getBuilderFor('id', $table, 'organisation_id', $organizationId)->get();
        $totalBudgetData = [];

        foreach ($totalBudgets as $totalBudget) {
            $totalBudgetId = $totalBudget->id;
            $childColumn   = 'total_budget_id';
            $periodStart   = $this->fetchPeriodStart($table, $childColumn, $totalBudgetId);
            $periodEnd     = $this->fetchPeriodEnd($table, $childColumn, $totalBudgetId);
            $value         = $this->fetchValue($table, $childColumn, $totalBudgetId);
            $budgetLine    = $this->fetchBudgetLine($table, $childColumn, $totalBudgetId);

            $totalBudgetData[] = [
                'period_start' => $periodStart,
                'period_end'   => $periodEnd,
                'value'        => $value,
                'budget_line'  => $budgetLine
            ];
        }
        $this->data[$organizationId]['total_budget'] = $totalBudgetData;

        return $this;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     * @internal param $totalBudgetId
     */
    protected function fetchPeriodStart($parentTable, $column, $parentId)
    {
        $periodStart = getBuilderFor('@iso_date as date', $parentTable . '/period_start', $column, $parentId)->first();
        $periodStart = [["date" => $periodStart->date]];

        return $periodStart;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     * @internal param $totalBudgetId
     */
    protected function fetchPeriodEnd($parentTable, $column, $parentId)
    {
        $periodEnd = getBuilderFor('@iso_date as date', $parentTable . '/period_end', $column, $parentId)->first();
        $periodEnd = [["date" => $periodEnd->date]];

        return $periodEnd;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $totalBudgetId
     * @return array
     */
    protected function fetchBudgetLine($parentTable, $column, $totalBudgetId)
    {
        $table          = $parentTable . '/budget_line';
        $budgetLineData = [];
        $budgetLines    = getBuilderFor(['id', '@ref as ref'], $table, $column, $totalBudgetId)->get();
        foreach ($budgetLines as $budgetLine) {
            $budgetLineId     = $budgetLine->id;
            $value            = $this->fetchValue($table, 'budget_line_id', $budgetLineId);
            $narrative        = $this->fetchNarrative($table, 'budget_line_id', $budgetLineId);
            $budgetLineData[] = [
                "reference" => $budgetLine->ref,
                "value"     => $value,
                "narrative" => $narrative
            ];
        }

        return $budgetLineData;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     */
    protected function fetchValue($parentTable, $column, $parentId)
    {
        $fields   = ['@currency as currency', '@value_date as value_date', 'text'];
        $value    = getBuilderFor($fields, $parentTable . '/value', $column, $parentId)->first();
        $currency = fetchCode($value->currency, 'Currency');
        $value    = [["amount" => $value->text, "currency" => $currency, "value_date" => $value->value_date]];

        return $value;
    }

    /**
     * @param      $parentTable
     * @param      $column
     * @param      $parentId
     * @param null $customTable
     * @return array
     */
    protected function fetchNarrative($parentTable, $column, $parentId, $customTable = null)
    {
        $narratives    = getBuilderFor(['text', '@xml_lang as xml_lang'], $parentTable . ($customTable ? $customTable : '/narrative'), $column, $parentId)->get();
        $narrativeData = [];
        foreach ($narratives as $narrative) {
            $language        = getLanguageCodeFor($narrative->xml_lang);
            $narrativeData[] = ['narrative' => $narrative->text, 'language' => $language];
        }
        $narrativeData ?: $narrativeData = [['narrative' => "", 'language' => ""]];

        return $narrativeData;
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

        return $this;
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

    /**
     * @param $organizationId
     * @return $this
     */
    protected function fetchRecipientOrgBudget($organizationId)
    {
        $table                  = 'iati_organisation/recipient_org_budget';
        $recipientOrgBudgets    = getBuilderFor('id', $table, 'organisation_id', $organizationId)->get();
        $recipientOrgBudgetData = [];

        foreach ($recipientOrgBudgets as $recipientOrgBudget) {
            $recipientOrgBudgetId = $recipientOrgBudget->id;
            $childColumn          = 'recipient_org_budget_id';
            $recipientOrg         = $this->fetchRecipientOrg($recipientOrgBudgetId);
            $narrative            = $this->fetchNarrative($table, 'recipient_org_id', $recipientOrgBudgetId, '/recipient_org/nar');
            $periodStart          = $this->fetchPeriodStart($table, $childColumn, $recipientOrgBudgetId);
            $periodEnd            = $this->fetchPeriodEnd($table, $childColumn, $recipientOrgBudgetId);
            $value                = $this->fetchValue($table, $childColumn, $recipientOrgBudgetId);
            $budgetLine           = $this->fetchBudgetLine($table, $childColumn, $recipientOrgBudgetId);

            $recipientOrgBudgetData[] = [
                'recipient_organization' => $recipientOrg,
                'narrative'              => $narrative,
                'period_start'           => $periodStart,
                'period_end'             => $periodEnd,
                'value'                  => $value,
                'budget_line'            => $budgetLine
            ];
        }

        $this->data[$organizationId]['recipient_organization_budget'] = $recipientOrgBudgetData;

        return $this;
    }

    /**
     * @param $parentId
     * @return array
     */
    protected function fetchRecipientOrg($parentId)
    {
        $recipientOrg = getBuilderFor('@ref as ref', 'iati_organisation/recipient_org_budget/recipient_org', 'recipient_org_budget_id', $parentId)->first();
        $recipientOrg = [["ref" => $recipientOrg->ref]];

        return $recipientOrg;
    }
}
