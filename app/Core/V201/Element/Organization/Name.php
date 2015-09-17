<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class Name extends BaseElement
{
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\NameForm";
    }

    public function getXmlData($orgData)
    {
        $orgNameData = array();
        $orgNameData[] = array(
            'narrative'   => $this->buildNarrative($orgData->name)
        );

        return $orgNameData;
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\NameRepository');
    }
}