<?php namespace App\Core\V201\Traits\Forms\Transaction;

/**
 * Class FlowType
 * @package App\Core\V201\Traits\Forms\Transaction
 */
trait FlowType
{
    /**
     * add flow type form
     * @return mixed
     */
    public function addFlowType()
    {
        return $this->addCollection('flow_type', 'Activity\Transactions\FlowType', '', [], trans('elementForm.flow_type'));
    }

    /**
     * get FlowTypeCode List
     * @return mixed
     */
    public function getFlowTypeCodeList()
    {
        return $this->getCodeList('FlowType', 'Activity');
    }
}
