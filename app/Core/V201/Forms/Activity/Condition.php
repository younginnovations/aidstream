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
            ->add(
                'condition_type',
                'select',
                [
                    'choices' => $this->getCodeList('ConditionType', 'Activity'),
                    'label'   => 'Condition Type'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove');
    }
}
