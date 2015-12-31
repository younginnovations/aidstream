<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PlannedDisbursement
 * @package App\Core\V201\Forms\Activity
 */
class PlannedDisbursement extends BaseForm
{
    /**
     * builds activity planned disbursement form
     */
    public function buildForm()
    {
        $this
            ->addSelect('planned_disbursement_type', $this->getCodeList('BudgetType', 'Activity'), 'Type', $this->addHelpText('Activity_PlannedDisbursement-type'))
            ->addCollection('period_start', 'Activity\PeriodStart')
            ->addCollection('period_end', 'Activity\PeriodEnd')
            ->addCollection('value', 'Activity\ValueForm')
            ->addRemoveThisButton('remove');
    }
}
