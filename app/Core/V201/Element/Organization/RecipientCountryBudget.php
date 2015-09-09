<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class RecipientCountryBudget extends BaseElement
{
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;
        return $this;
    }

    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\MultipleRecipientCountryBudgetForm";
    }

    public function getXmlData($org)
    {
        $orgRecipientCountryData = array();
        foreach($org->buildOrgRecipientCountryBudget() as $orgRecipientCountry)
        {
            $orgRecipientCountryData['narrative'] =array(
                '@value' => $orgRecipientCountry['narrative'],
                '@attributes' => array(
                    'xml:lang' => $orgRecipientCountry['language']
                )
            );
        }
        return $orgRecipientCountryData;
    }

    public  function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\RecipientCountryBudgetRepository');
    }
}