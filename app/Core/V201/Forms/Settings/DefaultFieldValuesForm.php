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
                    'choices' => $this->addCodeList('Currency', 'Organization')
                ]
            )
            ->add(
                'default_language',
                'select',
                [
                    'choices' => $this->addCodeList('Language', 'Organization')
                ]
            )
            ->add('default_hierarchy', 'text')
            ->add(
                'default_collaboration_type',
                'select',
                [
                    'choices' => $this->addCodeList('CollaborationType', 'Organization')
                ]
            )
            ->add(
                'default_flow_type',
                'select',
                [
                    'choices' => $this->addCodeList('FlowType', 'Organization')
                ]
            )
            ->add(
                'default_finance_type',
                'select',
                [
                    'choices' => $this->addCodeList('FinanceType', 'Organization')
                ]
            )
            ->add(
                'default_aid_type',
                'select',
                [
                    'choices' => $this->addCodeList('AidType', 'Organization')
                ]
            )
            ->add(
                'Default_tied_status',
                'select',
                [
                    'choices' => $this->addCodeList('TiedStatus', 'Organization')
                ]
            );
    }
}