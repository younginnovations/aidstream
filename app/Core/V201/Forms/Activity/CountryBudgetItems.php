<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class CountryBudgetItems extends BaseForm
{
    public function buildForm()
    {
        $this->addCollection('country_budget_item', 'Activity\CountryBudgetItem');
    }
}
