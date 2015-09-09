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

    public function getXmlData($org)
    {
        $orgTotalBudgetData = array();
        foreach($org->buildOrgTotalBudget() as $orgTotalBudget)
        {
            $orgTotalBudgetData['narrative'] =array(
                '@value' => $orgTotalBudget['narrative'],
                '@attributes' => array(
                    'xml:lang' => $orgTotalBudget['language']
                )
            );
        }
        return $orgTotalBudgetData;
    }

    public  function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\TotalBudgetRepository');
    }
}