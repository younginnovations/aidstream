<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PlannedDisbursement
 * @package App\Core\V202\Forms\Activity
 */
class PlannedDisbursement extends BaseForm
{
    /**
     * builds activity planned disbursement form
     */
    public function buildForm()
    {
        $this
            ->addSelect('planned_disbursement_type', $this->getCodeList('BudgetType', 'Activity'), trans('elementForm.type'))
            ->addCollection('period_start', 'Activity\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Activity\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Activity\ValueForm', '', [], trans('elementForm.value'))
            ->addCollection('provider_org', 'Activity\ProviderOrg', '', [], trans('elementForm.provider_org'))
            ->addCollection('receiver_org', 'Activity\ReceiverOrg', '', [], trans('elementForm.receiver_org'))
            ->addRemoveThisButton('remove');
    }
}
