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
        $orgRecipientCountryData = [];
        $recipientCountryBudget = (array) $organizationData->recipient_country_budget;
        foreach ($recipientCountryBudget as $orgRecipientCountry) {
            $orgRecipientCountryData[] = [
                'recipient-country' => [
                    '@attributes' => [
                        'code' => $orgRecipientCountry['recipient_country'][0]['code']
                    ],
                    'narrative' => $this->buildNarrative($orgRecipientCountry['recipient_country'][0]['narrative'])
                ],
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $orgRecipientCountry['period_start'][0]['date']
                    ]
                ],
                'period-end' => [
                    '@attributes' => [
                        'iso-date' => $orgRecipientCountry['period_end'][0]['date']
                    ]
                ],
                'value' => [
                    '@value'      => $orgRecipientCountry['value'][0]['amount'],
                    '@attributes' => [
                        'currency' => $orgRecipientCountry['value'][0]['currency'],
                        'value-date' => $orgRecipientCountry['value'][0]['value_date']
                    ]
                ],
                'budget-line' => $this->buildBudgetLine($orgRecipientCountry['budget_line'])
            ];
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