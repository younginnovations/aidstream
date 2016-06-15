<?php
namespace App\Core\V201;

use App;

class IatiOrganization
{
    protected $org;

    public function getName()
    {
        return app('App\Core\V201\Element\Organization\Name');
    }

    public function getNameRequest()
    {
        return app('App\Core\V201\Requests\Organization\CreateNameRequest');
    }

    public function getOrgReportingOrg()
    {
        return app('App\Core\V201\Element\Organization\OrgReportingOrg');
    }

    public function getTotalBudget()
    {
        return app('App\Core\V201\Element\Organization\TotalBudget');
    }

    public function getTotalBudgetRequest()
    {
        return app('App\Core\V201\Requests\Organization\CreateTotalBudgetRequest');
    }

    public function getRecipientOrgBudget()
    {
        return app('App\Core\V201\Element\Organization\RecipientOrgBudget');
    }

    public function getRecipientCountryBudget()
    {
        return app('App\Core\V201\Element\Organization\RecipientCountryBudget');
    }

    public function getRepository()
    {
        return app('App\Core\V201\Repositories\Organization\OrganizationRepository');
    }

    public function getRecipientOrgBudgetRequest()
    {
        return app('App\Core\V201\Requests\Organization\CreateOrgRecipientOrgBudgetRequest');
    }

    public function getCreateOrgReportingOrgRequest()
    {
        return app('App\Core\V201\Requests\Organization\CreateOrgReportingOrgRequest');
    }

    public function getRecipientCountryBudgetRequest()
    {
        return app('App\Core\V201\Requests\Organization\CreateRecipientCountryBudgetRequest');
    }

    public function getDocumentLink()
    {
        return app('App\Core\V201\Element\Organization\DocumentLink');
    }

    public function getDocumentLinkRequest()
    {
        return app('App\Core\V201\Requests\Organization\CreateDocumentLinkRequest');
    }

    public function getOrgXmlService()
    {
        return app('App\Core\V201\Element\Organization\XmlService');
    }

    public function getOrganizationElementValidator()
    {
        return app('App\Core\V201\Requests\OrganizationElementValidation');
    }
}
