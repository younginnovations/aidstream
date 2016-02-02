<?php namespace App\Migration;

use App\Migration\Elements\ReportingOrganization;
use App\Models\Organization\Organization as OrganizationModel;
use Illuminate\Database\DatabaseManager;
use App\Migration\Elements\Organization;


class MigrateOrganization
{
    protected $OrganizationModel;
    protected $mysqlConn;
    protected $org;
    protected $reportingOrg;
    protected $migrateHelper;

    public function __construct(
        OrganizationModel $OrganizationModel,
        DatabaseManager $databaseManager,
        Organization $organization,
        ReportingOrganization $reportingOrganization,
        MigrateHelper $migrateHelper
    ) {
        $this->mysqlConn         = $databaseManager->connection('mysql');
        $this->OrganizationModel = $OrganizationModel;
        $this->org               = $organization;
        $this->reportingOrg      = $reportingOrganization;
        $this->migrateHelper     = $migrateHelper;
    }

    public function orgDataFetch($orgId)
    {
        $formattedData      = [];
        $simpleValues       = $this->FetchSimpleValues($orgId);
        $reportingOrgValues = $this->FetchReportingOrgValues($orgId);

        $formattedData                  = $simpleValues;
        $formattedData['reporting_org'] = $reportingOrgValues;

        return $formattedData;
    }

    public function FetchSimpleValues($orgId)
    {
        $userIdentifier = $this->mysqlConn
            ->table('iati_organisation/identifier')
            ->select('text')
            ->where('organisation_id', '=', $orgId)
            ->first()->text;

        $iatiOrgInfo = $this->mysqlConn->table('iati_organisation')
                                       ->select('account_id', '@last_updated_datetime as last_updated_datetime')
                                       ->where('id', '=', $orgId)
                                       ->first();

        $accountInfo = $this->mysqlConn->table('account')
                                       ->select('address', 'name', 'telephone', 'status')
                                       ->where('id', '=', $iatiOrgInfo->account_id)
                                       ->first();
        $OrgArray    = [
            'id'              => $orgId,
            'user_identifier' => $userIdentifier,
            'name'            => $accountInfo->name,
            'address'         => $accountInfo->address,
            'telephone'       => $accountInfo->telephone,
            'created_at'      => $iatiOrgInfo->last_updated_datetime,
            'updated_at'      => $iatiOrgInfo->last_updated_datetime,
            'status'          => $accountInfo->status
        ];

        return $OrgArray;
    }

    public function FetchReportingOrgValues($orgId)
    {
        $reportingOrgRefType   = $this->mysqlConn->table('iati_organisation/reporting_org')
                                                 ->select('@ref as reporting_organization_identifier', '@type as reporting_organization_type')
                                                 ->where('organisation_id', '=', $orgId)
                                                 ->first();
        $reportingOrgNarrative = [];
        $reportingOrgNarrative = $this->mysqlConn->table('iati_organisation/reporting_org/narrative')
                                                 ->select('text', '@xml_lang as xml_lang')
                                                 ->where('reporting_org_id', '=', $orgId)
                                                 ->get();
        $repOrgNarrative       = [];
        foreach ($reportingOrgNarrative as $r) {
            $lang_code         = $this->migrateHelper->FetchLangCode($r->xml_lang);
            $repOrgNarrative[] = ['narrative' => $r->text, 'language' => $lang_code];
        }
        //reporting org data
        $reportingOrg                                      = [];
        $reportingOrg['reporting_organization_identifier'] = $reportingOrgRefType->reporting_organization_identifier;
        $reportingOrg['reporting_organization_type']       = $reportingOrgRefType->reporting_organization_type;
        $reportingOrg['narrative']                         = $repOrgNarrative;
        $reportingOrg                                      = [$reportingOrg];

        return $reportingOrg;
    }
}