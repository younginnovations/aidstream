<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class PeriodStart extends BaseForm
{
    public function buildForm()
    {
        $this->add(
            'date',
            'date',
            [
                'label' => trans('elementForm.date'),
                'help_block' => $this->addHelpText('Organisation_TotalBudget_PeriodStart-iso_date'),
                'required' => true,
                'attr' => ['placeholder' => 'YYYY-MM-DD']
            ]
        );
    }
}
