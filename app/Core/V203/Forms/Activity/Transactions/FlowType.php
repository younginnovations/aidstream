<?php namespace App\Core\V203\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\FlowType as FlowTypeCodeList;

/**
 * Class FlowType
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class FlowType extends BaseForm
{
    use FlowTypeCodeList;
    protected $showFieldErrors = true;

    /**
     * builds flow type form
     */
    public function buildForm()
    {
        $this->addSelect('flow_type', $this->getFlowTypeCodeList(), trans('elementForm.flow_type'), $this->addHelpText('Activity_Transaction_FlowType-code'));
    }
}
