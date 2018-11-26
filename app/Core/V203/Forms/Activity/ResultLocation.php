<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Locations
 * @package App\Core\V201\Forms\Activity
 */
class ResultLocation extends BaseForm
{
    /**
     * builds locations form
     */
    public function buildForm()
    {
        $this
            ->add('ref', 'text', ['label' => trans('elementForm.location'), 'help_block' => $this->addHelpText('Activity_Result_Indicator_Baseline-year')])
            ->addRemoveThisButton('remove_ref');
    }
}
