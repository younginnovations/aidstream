<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PeriodStart
 * @package App\Core\V201\Forms\Activity
 */
class PeriodStart extends BaseForm
{
    public function buildForm()
    {
        $this->add('date', 'date', ['help_block' => $this->addHelpText('Activity_Budget_PeriodStart-iso_date'), 'required' => true]);
    }
}
