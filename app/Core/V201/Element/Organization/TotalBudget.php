<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class TotalBudget extends BaseElement
{
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\MultipleTotalBudgetForm";
    }

    public function getXmlData($orgData)
    {
        $orgTotalBudgetData = array();
        $totalBudget = (array) $orgData->total_budget;
        foreach ($totalBudget as $orgTotalBudget) {
            $orgTotalBudgetData[] = array(
                'period-start' => array(
                    '@attributes' => array(
                        'iso-date' => $orgTotalBudget['periodStart'][0]['date']
                    )
                ),
                'period-end' => array(
                    '@attributes' => array(
                        'iso-date' => $orgTotalBudget['periodEnd'][0]['date']
                    )
                ),
                'value' => array(
                    '@value'      => $orgTotalBudget['value'][0]['amount'],
                    '@attributes' => array(
                        'currency' => $orgTotalBudget['value'][0]['currency'],
                        'value-date' => $orgTotalBudget['value'][0]['value_date']
                    )
                )
            );
        }

        return $orgTotalBudgetData;
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\TotalBudgetRepository');
    }
}