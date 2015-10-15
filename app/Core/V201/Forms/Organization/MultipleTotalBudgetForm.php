<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleTotalBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('total_budget', 'Organization\TotalBudgetForm', 'total_budget')
            ->addAddMoreButton('add', 'total_budget');
    }
}
