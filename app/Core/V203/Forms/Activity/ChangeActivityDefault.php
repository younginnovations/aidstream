<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ChangeActivityDefault
 * @package App\Core\V201\Forms\Activity
 */
class ChangeActivityDefault extends BaseForm
{
    /**
     * builds activity default form
     */
    public function buildForm()
    {
        $this
            ->addSelect('default_currency', $this->getCodeList('Currency', 'Organization'), trans('elementForm.default_currency'), $this->addHelpText('activity_defaults-default_currency'))
            ->addSelect('default_language', $this->getCodeList('Language', 'Organization'), trans('elementForm.default_language'), $this->addHelpText('activity_defaults-default_language'))
            ->add('default_hierarchy', 'text', ['label' => trans('elementForm.default_hierarchy'), 'help_block' => $this->addHelpText('activity_defaults-hierarchy')])
            ->add('linked_data_uri', 'text', ['label' => trans('elementForm.linked_data_uri'), 'help_block' => $this->addHelpText('activity-linked_data_uri')])
            ->addSelect('humanitarian', ['1' => trans('elementForm.yes'), '0' => trans('elementForm.no')], trans('elementForm.humanitarian'), $this->addHelpText('activity_defaults-humanitarian'))
            ->addSelect(
                'budget_not_provided',
                $this->getCodeList('BudgetNotProvided','Activity'),
                trans('elementForm.budget_not_provided'),
                $this->addHelpText('activity_defaults-budget_not_provided', false),
                config('app.default_language'),
                false,
                [
                    'wrapper' => ['class' => 'form-group col-sm-6']
                ]

                );
    }
}
