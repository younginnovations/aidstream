<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PeriodDate
 * Contains the function to create the period date form
 * @package App\Core\V201\Forms\Activity
 */
class PeriodDate extends BaseForm
{
    /**
     * builds the activity period date form
     */
    public function buildForm()
    {
        $this->add(
            'date',
            'date',
            [
                'label' => trans('elementForm.date'),
                'help_block' => $this->addHelpText('Activity_Result_Indicator_Period_PeriodStart-iso_date'),
                'required' => true,
                'attr' => ['placeholder' => 'YYYY-MM-DD']
            ]
        );
    }
}
