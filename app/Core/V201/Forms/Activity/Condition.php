<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Condition
 * @package App\Core\V201\Forms\Activity
 */
class Condition extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->addSelect('condition_type', $this->getCodeList('ConditionType', 'Activity'), trans('elementForm.condition_type'), $this->addHelpText('Activity_Conditions_Condition-type'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove');
    }
}
