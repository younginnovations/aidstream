<?php
namespace App\Core\V201;

use App;

class IatiOrganization
{
    protected $org;

    public function getName()
    {
        return App::make('App\Core\V201\Element\Organization\Name');
    }
    public function getNameRequest()
    {
        return App::make('App\Core\V201\Requests\Organization\CreateNameRequest');
    }

    public function getOrgReportingOrg()
    {
        return App::make('App\Core\V201\Element\Organization\OrgReportingOrg');
    }

    public function getTotalBudget()
    {
        return App::make('App\Core\V201\Element\Organization\TotalBudget');
    }
    public function getTotalBudgetRequest()
    {
        return App::make('App\Core\V201\Requests\Organization\CreateTotalBudgetRequest');
    }
    public function getRecipientOrgBudget()
    {
        return App::make('App\Core\V201\Element\Organization\RecipientOrgBudget');
    }

    public function getRecipientCountryBudget()
    {
        return App::make('App\Core\V201\Element\Organization\RecipientCountryBudget');
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\OrganizationRepository');
    }

    public function getRecipientOrgBudgetRequest()
    {
        return App::make('App\Core\V201\Requests\Organization\CreateOrgRecipientOrgBudgetRequest');
    }

    public function getCreateOrgReportingOrgRequest()
    {
        return App::make('App\Core\V201\Requests\CreateOrgReportingOrgRequest');
    }

    public function getCreateOrgRecipientCountryBudgetRequest()
    {
        return App::make('App\Core\V201\Request\CreateOrgRecipientCountryBudgetRequest');
    }
}