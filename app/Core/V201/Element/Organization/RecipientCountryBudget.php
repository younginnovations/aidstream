<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;
use App\Models\Organization\OrganizationData;

class RecipientCountryBudget extends BaseElement
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
        return "App\Core\V201\Forms\Organization\MultipleRecipientCountryBudgetForm";
    }

    /**
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData(OrganizationData $organizationData)
    {
        $orgRecipientCountryData = array();
        $recipientCountryBudget = (array) $organizationData->recipient_country_budget;
        foreach ($recipientCountryBudget as $orgRecipientCountry) {
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

    /**
     * @return Organization recipient country budget repository
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\RecipientCountryBudgetRepository');
    }
}