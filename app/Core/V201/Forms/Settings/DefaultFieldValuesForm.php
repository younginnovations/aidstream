<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;

class DefaultFieldValuesForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'default_currency',
                'select',
                [
                    'choices' => $this->getCodeList('Currency', 'Organization')
                ]
            )
            ->add(
                'default_language',
                'select',
                [
                    'choices' => $this->getCodeList('Language', 'Organization')
                ]
            )
            ->add('default_hierarchy', 'text')
            ->add(
                'default_collaboration_type',
                'select',
                [
                    'choices' => $this->getCodeList('CollaborationType', 'Organization')
                ]
            )
            ->add(
                'default_flow_type',
                'select',
                [
                    'choices' => $this->getCodeList('FlowType', 'Organization')
                ]
            )
            ->add(
                'default_finance_type',
                'select',
                [
                    'choices' => $this->getCodeList('FinanceType', 'Organization')
                ]
            )
            ->add(
                'default_aid_type',
                'select',
                [
                    'choices' => $this->getCodeList('AidType', 'Organization')
                ]
            )
            ->add(
                'Default_tied_status',
                'select',
                [
                    'choices' => $this->getCodeList('TiedStatus', 'Organization')
                ]
            );
    }
}
