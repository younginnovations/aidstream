<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PlannedDisbursement
 * @package App\Core\V201\Forms\Activity
 */
class PlannedDisbursement extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add(
                'planned_disbursement_type',
                'select',
                [
                    'choices'     => $this->getCodeList('BudgetType', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Type'
                ]
            )
            ->addCollection('period_start', 'Activity\PeriodStart')
            ->addCollection('period_end', 'Activity\PeriodEnd')
            ->addCollection('value', 'Activity\ValueForm')
            ->addRemoveThisButton('remove');
    }
}
