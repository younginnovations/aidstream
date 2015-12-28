<?php namespace App\Core\V202\Xml\Organization;

use App\Core\Elements\BaseElement;
use App\Models\Organization\OrganizationData;

/**
 * Class TotalExpenditure
 * @package App\Core\V202\Xml\Organization
 */
class TotalExpenditure extends BaseElement
{
    /**
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData(OrganizationData $organizationData)
    {
        $orgTotalExpenditure = [];
        $totalExpenditures   = (array) $organizationData->total_expenditure;
        foreach ($totalExpenditures as $totalExpenditure) {
            $orgTotalExpenditure[] = [
                'period-start' => [
                    '@attributes' => [
                        'iso-date' => $totalExpenditure['period_start'][0]['date']
                    ]
                ],
                'period-end'   => [
                    '@attributes' => [
                        'iso-date' => $totalExpenditure['period_end'][0]['date']
                    ]
                ],
                'value'        => $this->buildValue($totalExpenditure['value']),
                'expense-line' => $this->buildExpenseLine($totalExpenditure['expense_line'])
            ];
        }

        return $orgTotalExpenditure;
    }

    /**
     * build xml for value sub-element
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

    /**
     * build xml for expense line sub element
     * @param $expenseLines
     * @return array
     */
    protected function buildExpenseLine($expenseLines)
    {
        $expenseLineData = [];
        foreach ($expenseLines as $expenseLine) {
            $expenseLineData[] = [
                '@attributes' => [
                    'ref' => $expenseLine['reference']
                ],
                'value'       => $this->buildValue($expenseLine['value']),
                'narrative'   => $this->buildNarrative($expenseLine['narrative'])
            ];
        }

        return $expenseLineData;
    }
}
