<?php namespace App\Core\V202\Forms\Activity;

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
            ->addSelect('planned_disbursement_type', $this->getCodeList('BudgetType', 'Activity'), 'Type')
            ->addCollection('period_start', 'Activity\PeriodStart')
            ->addCollection('period_end', 'Activity\PeriodEnd')
            ->addCollection('value', 'Activity\ValueForm')
            ->addCollection('provider_org', 'Activity\ProviderOrg')
            ->addCollection('receiver_org', 'Activity\ReceiverOrg')
            ->addRemoveThisButton('remove');
    }
}
