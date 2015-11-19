<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;


/**
 * Class Budget
 * @package App\Core\V201\Forms\Activity
 */
class Budget extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add(
                'budget_type',
                'select',
                [
                    'choices' => $this->getCodeList('BudgetType', 'Activity'),
                    'label'   => 'Budget Type'
                ]
            )
            ->addCollection('period_start', 'Activity\PeriodStart')
            ->addCollection('period_end', 'Activity\PeriodEnd')
            ->addCollection('value', 'Activity\ValueForm')
            ->addRemoveThisButton('remove');
    }
}
