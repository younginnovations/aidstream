<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class DefaultFlowType
 * @package App\Core\V201\Forms\Activity
 */
class DefaultFlowType extends BaseForm
{
    /**
     * builds the Activity Default Flow Type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'default_flow_type',
                'select',
                [
                    'choices' => $this->getCodeList('FlowType', 'Activity'),
                ]
            );
    }
}
