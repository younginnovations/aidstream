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

    public function getOrgReportingOrg()
    {
        return App::make('App\Core\V201\Element\Organization\OrgReportingOrg');
    }

    public function getTotalBudget()
    {
        return App::make('App\Core\V201\Element\Organization\TotalBudget');
    }

    public function getRecipientOrgBudget()
    {
        return App::make('App\Core\V201\Element\Organization\RecipientOrgBudget');
    }

    public function getRecipientCountryBudget()
    {
        return App::make('App\Core\V201\Element\Organization\RecipientCountryBudget');
    }

    public function getBudgetLine()
    {
        return App::make('App\Core\V201\Element\Organization\BudgetLine');
    }

    public function getNarrative()
    {
        return App::make('App\Core\V201\Element\Organization\Narrative');
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\OrganizationRepository');
    }

    public function getRecipientOrgBudgetRequest()
    {
        return App::make('App\Core\V201\Request\CreateOrgRecipientOrgBudgetRequest');
    }

}