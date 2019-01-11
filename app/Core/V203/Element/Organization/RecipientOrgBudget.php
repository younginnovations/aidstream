<?php namespace App\Core\V203\Element\Organization;

use App;
use App\Core\V201\Element\Organization\RecipientOrgBudget as V201RecipientOrgBudget;

/**
 * Class RecipientOrgBudget
 * @package App\Core\V202\Element\Organization
 */
class RecipientOrgBudget extends V201RecipientOrgBudget
{
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
                '@attributes'   => [
                    'status' => $RecipientOrgBudget['status']
                ],
                'recipient-org' => [
                    '@attributes' => [
                        'ref' => $RecipientOrgBudget['recipient_organization'][0]['ref']
                    ],
                    'narrative'   => $this->buildNarrative($RecipientOrgBudget['recipient_organization'][0]['narrative'])
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
                ],
                'budget-line' => $this->buildBudgetLine($RecipientOrgBudget['budget_line'])
            ];
        }

        return $organizationData;
    }
}
