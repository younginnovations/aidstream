<?php namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

/**
 * Class RecipientOrgBudget
 * @package App\Core\V201\Element\Organization
 */
class RecipientOrgBudget extends BaseElement
{
    /**
     * @var array
     */
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
     * return recipient organization budget form
     * @return string
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Organization\MultipleRecipientOrgBudgetForm';
    }

    /**
     * @param $organization
     * @return mixed
     */
    public function getXmlData($organization)
    {
        $organizationData   = [];
        $recipientOrgBudget = (array) $organization->recipient_organization_budget;
        foreach ($recipientOrgBudget as $RecipientOrgBudget) {
            $organizationData[] = [
                'recipient-org' => [
                    '@attributes' => [
                        'ref' => $RecipientOrgBudget['recipient_organization'][0]['ref']
                    ],
                    'narrative'   => $this->buildNarrative($RecipientOrgBudget['narrative'])
                ],
                'period-start'  => [
                    '@attributes' => [
                        'iso-date' => $RecipientOrgBudget['period_start'][0]['date']
                    ]
                ],
                'period-end'    => [
                    '@attributes' => [
                        'iso-date' => $RecipientOrgBudget['period_end'][0]['date']
                    ]
                ],
                'value'         => [
                    '@value'      => $RecipientOrgBudget['value'][0]['amount'],
                    '@attributes' => [
                        'currency'   => $RecipientOrgBudget['value'][0]['currency'],
                        'value-date' => $RecipientOrgBudget['value'][0]['value_date']
                    ]
                ]
            ];
        }

        return $organizationData;
    }

    /**
     * return recipient organization budget repository
     * @return mixed
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\RecipientOrgBudgetRepository');
    }
}
