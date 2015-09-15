<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class ReportingOrganizationInfo extends BaseElement
{

    public function getForm()
    {
        return "App\Core\V201\Forms\Settings\OrganizationForm";
    }

    public function getReportingOrganizationInfoForm()
    {
        return "App\Core\V201\Forms\Organization\ReportingOrganizationInfoForm";
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\OrganizationRepository');
    }

}