<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Date
 * @package App\Core\V202\Forms\Activity
 */
class Date extends BaseForm
{
    public function buildForm()
    {
        $this->add('date', 'date', ['label' => trans('elementForm.date'), 'help_block' => $this->addHelpText('Activity_Budget_PeriodStart-iso_date'), 'attr' => ['placeholder' => 'YYYY-MM-DD']]);
    }
}
