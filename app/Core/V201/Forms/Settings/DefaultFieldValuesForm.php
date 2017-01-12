<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class DefaultFieldValuesForm
 * @package App\Core\V201\Forms\Settings
 */
class DefaultFieldValuesForm extends BaseForm
{
    /**
     * build Default field values form
     */
    public function buildForm()
    {
        $this
            ->addSelect(
                'default_currency',
                $this->getCodeList('Currency', 'Organization'),
                trans('elementForm.default_currency'),
                $this->addHelpText('activity_defaults-default_currency', false),
                null,
                true
            )
            ->addSelect(
                'default_language',
                $this->getCodeList('Language', 'Organization'),
                trans('elementForm.default_language'),
                $this->addHelpText('activity_defaults-default_language', false),
                config('app.default_language'),
                true
            )
            ->add('default_hierarchy', 'text', ['label' => trans('elementForm.default_hierarchy'), 'help_block' => $this->addHelpText('activity_defaults-hierarchy', false)])
            ->add('linked_data_uri', 'text', ['label' => trans('elementForm.linked_data_default')])
            ->addSelect(
                'default_collaboration_type',
                $this->getCodeList('CollaborationType', 'Organization'),
                trans('elementForm.default_collaboration_type'),
                $this->addHelpText('activity_defaults-default_collaboration_type', false)
            )
            ->addSelect('default_flow_type', $this->getCodeList('FlowType', 'Organization'), trans('elementForm.default_flow_type'), $this->addHelpText('activity_defaults-default_flow_type', false))
            ->addSelect(
                'default_finance_type',
                $this->getCodeList('FinanceType', 'Organization'),
                trans('elementForm.default_finance_type'),
                $this->addHelpText('activity_defaults-default_finance_type', false)
            )
            ->addSelect('default_aid_type', $this->getCodeList('AidType', 'Organization'), trans('elementForm.default_aid_type'), $this->addHelpText('activity_defaults-default_aid_type', false))
            ->addSelect(
                'default_tied_status',
                $this->getCodeList('TiedStatus', 'Organization'),
                trans('elementForm.default_tied_status'),
                $this->addHelpText('activity_defaults-default_tied_status', false)
            );
    }
}
