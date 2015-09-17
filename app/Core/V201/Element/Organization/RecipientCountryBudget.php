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
        foreach ($org->recipient_country_budget as $orgRecipientCountry) {
            $orgRecipientCountryData[] = array(
                'recipient-country' => array(
                    '@attributes' => array(
                        'code' => $orgRecipientCountry['recipientCountry'][0]['code']
                    ),
                    'narrative' => $this->buildNarrative($orgRecipientCountry['recipientCountry'][0]['narrative'])
                ),
                'period-start' => array(
                    '@attributes' => array(
                        'iso-date' => $orgRecipientCountry['periodStart'][0]['date']
                    )
                ),
                'period-end' => array(
                    '@attributes' => array(
                        'iso-date' => $orgRecipientCountry['periodEnd'][0]['date']
                    )
                ),
                'value' => array(
                    '@value'      => $orgRecipientCountry['value'][0]['amount'],
                    '@attributes' => array(
                        'currency' => $orgRecipientCountry['value'][0]['currency'],
                        'value-date' => $orgRecipientCountry['value'][0]['value_date']
                    )
                )
            );
        }

        return $orgRecipientCountryData;
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\RecipientCountryBudgetRepository');
    }
}