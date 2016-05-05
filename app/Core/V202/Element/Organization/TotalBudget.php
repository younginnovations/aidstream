<?php namespace App\Core\V202\Element\Organization;

use App;
use App\Core\V201\Element\Organization\TotalBudget as V201TotalBudget;

/**
 * Class TotalBudget
 * @package App\Core\V202\Element\Organization
 */
class TotalBudget extends V201TotalBudget
{
    /**
     * @param $orgData
     * @return array
     */
    public function getXmlData($orgData)
    {
        $orgTotalBudgetData = [];
        $totalBudget        = (array) $orgData->total_budget;
        foreach ($totalBudget as $orgTotalBudget) {
            $orgTotalBudgetData[] = [
                '@attributes'  => [
                    'status' => $orgTotalBudget['status']
                ],
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $orgTotalBudget['period_start'][0]['date']
                    ]
                ],
                'period-end'   => [
                    '@attributes' => [
                        'iso-date' => $orgTotalBudget['period_end'][0]['date']
                    ]
                ],
                'value'        => [
                    '@value'      => $orgTotalBudget['value'][0]['amount'],
                    '@attributes' => [
                        'currency'   => $orgTotalBudget['value'][0]['currency'],
                        'value-date' => $orgTotalBudget['value'][0]['value_date']
                    ]
                ],
                'budget-line' => $this->buildBudgetLine($orgTotalBudget['budget_line'])
            ];
        }

        return $orgTotalBudgetData;
    }
}
