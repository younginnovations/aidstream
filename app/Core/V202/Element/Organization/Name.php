<?php

namespace App\Core\V202\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class Name extends \App\Core\V201\Element\Organization\Name
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

    public function getXmlData($orgData)
    {
        $orgNameData   = array();
        $orgNameData[] = array(
            'narrative' => $this->buildNarrative($orgData->name)
        );

        return $orgNameData;
    }

    public function getRepository()
    {
        return App::make('App\Core\V202\Repositories\Organization\NameRepository');
    }
}