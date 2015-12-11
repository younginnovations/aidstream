<?php

namespace App\Core\V202\Element\Organization;

use App\Core\Elements\BaseElement;
use App;
use App\Core\V201\Element\Organization\Name as V201Name;
use App\Models\Organization\OrganizationData;

class Name extends V201Name
{
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    public function getForm()
    {
        return "App\Core\V202\Forms\Organization\NameForm";
    }

    public function getXmlData(OrganizationData $orgData)
    {
        $orgNameData = [];
        $name = (array) $orgData->name;
        if($name) {
            $orgNameData[] = [
                'narrative'   => $this->buildNarrative($name)
            ];
        }

        return $orgNameData;
    }

    public function getRepository()
    {
        return App::make('App\Core\V202\Repositories\Organization\NameRepository');
    }
}