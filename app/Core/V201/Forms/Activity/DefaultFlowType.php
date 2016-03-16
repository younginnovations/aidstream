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
        $this->addSelect('default_flow_type', $this->getCodeList('FlowType', 'Activity'), 'Default Flow Type', $this->addHelpText('Activity_DefaultFlowType-code'), null, true);
    }
}
