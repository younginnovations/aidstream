<?php namespace App\Migration\Migrator\Data;

/**
 * Class OrganizationQuery
 * @package App\Migration\Migrator\Data
 */
class OrganizationQuery extends Query
{
    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $this->initDBConnection();

        $organizations = [];

        foreach ($accountIds as $accountId) {
            $organizationData = $this->simpleValues($accountId);

            if ($organization = getOrganizationFor($accountId)) {
                $organizationData['reporting_org'] = $this->reportingOrgValues($organization->id);
            }

            $organizations[] = $organizationData;
        }

        return $organizations;
    }

    /**
     * Get Simple values.
     * @param $accountId
     * @return array
     */
    protected function simpleValues($accountId)
    {
        $account = $this->connection->table('account')
                                    ->select('*')
                                    ->where('id', '=', $accountId)
                                    ->first();

        $publishedToRegistry = $this->connection->table('organisation_published')
                                                ->select('pushed_to_registry')
                                                ->where('publishing_org_id', '=', $accountId)
                                                ->first();

        $organization = [
            'id'                    => $accountId,
            'user_identifier'       => $account->username,
            'name'                  => $account->name,
            'address'               => $account->address,
            'telephone'             => $account->telephone,
            'status'                => $account->status,
            'organization_url'      => $account->url,
            'disqus_comments'       => ($comment = $account->disqus_comments) ? $comment : 0,
            'twitter'               => $account->twitter,
            'published_to_registry' => $publishedToRegistry ? $publishedToRegistry->pushed_to_registry : 0
        ];

        return $organization;
    }

    /**
     * Get Reporting Organization values.
     * @param $organizationId
     * @return array
     */
    protected function reportingOrgValues($organizationId)
    {
        $reportingOrgReferenceType = $this->connection->table('iati_organisation/reporting_org')
                                                      ->select('@ref as reporting_organization_identifier', '@type as reporting_organization_type')
                                                      ->where('organisation_id', '=', $organizationId)
                                                      ->first();

        $reportingOrgNarratives = $this->connection->table('iati_organisation/reporting_org/narrative')
                                                   ->select('text', '@xml_lang as xml_lang')
                                                   ->where('reporting_org_id', '=', $organizationId)
                                                   ->get();

        $reportingOrgNarrative = [];

        foreach ($reportingOrgNarratives as $narrative) {
            $languageCode            = getLanguageCodeFor($narrative->xml_lang);
            $reportingOrgNarrative[] = ['narrative' => $narrative->text, 'language' => $languageCode];
        }

        return [
            [
                'reporting_organization_identifier' => $reportingOrgReferenceType->reporting_organization_identifier,
                'reporting_organization_type'       => $reportingOrgReferenceType->reporting_organization_type,
                'narrative'                         => $reportingOrgNarrative
            ]
        ];
    }
}
