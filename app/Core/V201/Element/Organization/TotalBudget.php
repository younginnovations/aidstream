<?php namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

/**
 * Class TotalBudget
 * @package App\Core\V201\Element\Organization
 */
class TotalBudget extends BaseElement
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
     * return total budgets form path
     * @return string
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Organization\MultipleTotalBudgetForm';
    }

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

    /**
     * return total budget repository
     * @return mixed
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\TotalBudgetRepository');
    }
}
