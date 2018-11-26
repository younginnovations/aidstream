<?php namespace App\Core\V203\Xml\Organization;

use App\Core\Elements\BaseElement;
use App\Models\Organization\OrganizationData;

/**
 * Class RecipientRegionBudget
 * @package App\Core\V202\Xml\Organization
 */
class RecipientRegionBudget extends BaseElement
{
    /**
     * return recipient region budget xml data
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData(OrganizationData $organizationData)
    {
        $orgRecipientRegionData = [];
        $recipientRegionBudget  = (array) $organizationData->recipient_region_budget;
        foreach ($recipientRegionBudget as $orgRecipientRegion) {
            $orgRecipientRegionData[] = [
                '@attributes'      => [
                    'status' => $orgRecipientRegion['status']
                ],
                'recipient-region' => [
                    '@attributes' => [
                        'vocabulary'     => $orgRecipientRegion['recipient_region'][0]['vocabulary'],
                        'vocabulary-uri' => $orgRecipientRegion['recipient_region'][0]['vocabulary_uri'],
                        'code'           => $orgRecipientRegion['recipient_region'][0]['code']
                    ],
                    'narrative'   => $this->buildNarrative($orgRecipientRegion['recipient_region'][0]['narrative'])
                ],
                'period-start'     => [
                    '@attributes' => [
                        'iso-date' => $orgRecipientRegion['period_start'][0]['date']
                    ]
                ],
                'period-end'       => [
                    '@attributes' => [
                        'iso-date' => $orgRecipientRegion['period_end'][0]['date']
                    ]
                ],
                'value'            => $this->buildValue($orgRecipientRegion['value']),
                'budget-line'      => $this->buildBudgetLine($orgRecipientRegion['budget_line'])
            ];
        }

        return $orgRecipientRegionData;
    }

    /**
     * return value xml data
     * @param $values
     * @return array
     */
    protected function buildValue($values)
    {
        $valueData = [];
        foreach ($values as $value) {
            $valueData[] = [
                '@value'      => $value['amount'],
                '@attributes' => [
                    'currency'   => $value['currency'],
                    'value-date' => $value['value_date']
                ]
            ];
        }

        return $valueData;
    }
}
