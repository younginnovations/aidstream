<?php namespace App\Core\Elements;

/**
 * Class BaseElement
 * @package App\Core\Elements
 */
class BaseElement
{
    /**
     * Build narratives for Elements.
     * @param $narratives
     * @return array
     */
    public function buildNarrative($narratives)
    {
        $narrativeData = [];
        foreach ($narratives as $narrative) {
            $narrativeData[] = [
                '@value'      => getVal($narrative, ['narrative']),
                '@attributes' => [
                    'xml:lang' => getVal($narrative, ['language'])
                ]
            ];
        }

        return $narrativeData;
    }

    /**
     * @param $budgetLines
     * @return array
     */
    public function buildBudgetLine($budgetLines)
    {
        $budgetLineData = [];
        foreach ($budgetLines as $budgetLine) {
            $budgetLineData[] = [
                '@attributes' => [
                    'ref' => $budgetLine['reference']
                ],
                'value'       => $this->buildValue($budgetLine['value']),
                'narrative'   => $this->buildNarrative($budgetLine['narrative'])
            ];
        }

        return $budgetLineData;
    }

    /**
     * 
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
