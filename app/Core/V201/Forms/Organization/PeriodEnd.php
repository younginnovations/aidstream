<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class PeriodEnd extends BaseForm
{
    public function buildForm()
    {
        $this->add('date', 'date', ['help_block' => $this->addHelpText('Organisation_TotalBudget_PeriodEnd-iso_date'), 'required' => true, 'attr' => ['placeholder' => 'YYYY-MM-DD']]);
    }
}
