<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class PeriodStart extends BaseForm
{
    public function buildForm()
    {
        $this->add('date', 'date', ['help_block' => $this->addHelpText('Organisation_TotalBudget_PeriodStart-iso_date')]);
    }
}
