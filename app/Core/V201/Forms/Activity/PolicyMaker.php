<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PolicyMaker
 * @package App\Core\V201\Forms\Activity
 */
class PolicyMaker extends BaseForm
{

    public function buildForm()
    {
        $this
            ->add(
                'significance',
                'select',
                [
                    'choices'     => $this->getCodeList('PolicySignificance', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Significance'
                ]
            )
            ->add(
                'policy_marker',
                'select',
                [
                    'choices'     => $this->getCodeList('PolicyMarker', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Policy Marker'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_policy_maker');
    }
}
