<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;
use App\Models\Organization\OrganizationData;

class Name extends BaseElement
{
    protected $narratives = [];

    /**
     * @param $narrative
     * @return $this
     */
    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\NameForm";
    }

    /**
     * @param OrganizationData $orgData
     * @return array
     */
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

    /**
     * @return organization name repository
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\NameRepository');
    }
}