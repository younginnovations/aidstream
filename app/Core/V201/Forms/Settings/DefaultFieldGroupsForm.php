<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class DefaultFieldGroupsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('Check all', 'checkbox', [
                'label' => 'Check All',
                'checked' => false,
                'attr' => ['class' => 'checkAll']
            ])
            ->add('identification', 'static')
            ->add('other activity identifier', 'checkbox', [
                'default_value' => 1,
                'label' => 'Other Activity Identifier',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('basic activity information', 'static')
            ->add('title', 'checkbox', [
                'default_value' => 1,
                'label' => 'Title',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('description', 'checkbox', [
                'default_value' => 1,
                'label' => 'Description',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('activity status', 'checkbox', [
                'default_value' => 1,
                'label' => 'Activity Status',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('activity date', 'checkbox', [
                'default_value' => 1,
                'label' => 'Activity Date',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('contact info', 'checkbox', [
                'default_value' => 1,
                'label' => 'Contanct Info',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('activity scope', 'checkbox', [
                'default_value' => 1,
                'label' => 'Activity Scope',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('participation organization', 'static')
            ->add('participating org', 'checkbox', [
                'default_value' => 1,
                'label' => 'Participating Org',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('Geopolitical Information', 'static')
            ->add('recipient county', 'checkbox', [
                'default_value' => 1,
                'label' => 'Recipient Country',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('recipient region', 'checkbox', [
                'default_value' => 1,
                'label' => 'Recipient Region',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('location', 'checkbox', [
                'default_value' => 1,
                'label' => 'Location',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('classifications', 'static')
            ->add('sector', 'checkbox', [
                'default_value' => 1,
                'label' => 'Sector',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('policy maker', 'checkbox', [
                'default_value' => 1,
                'label' => 'Policy Maker',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('collaboration type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Collaboration Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('flow type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Flow Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('finance type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Finance Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('aid type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Aid Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('tied status', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Tied Status',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('country budget items', 'checkbox', [
                'default_value' => 1,
                'label' => 'Country Budget Items',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('financial','static')
            ->add('budget', 'checkbox', [
                'default_value' => 1,
                'label' => 'Budget',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('planned disbursement', 'checkbox', [
                'default_value' => 1,
                'label' => 'Planned Disbursement',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('transaction', 'checkbox', [
                'default_value' => 1,
                'label' => 'Transaction',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('capital spend', 'checkbox', [
                'default_value' => 1,
                'label' => 'Capital Spend',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('related documents', 'static')
            ->add('document link', 'checkbox', [
                'default_value' => 1,
                'label' => 'Document Link',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('relations', 'static')
            ->add('related activity', 'checkbox', [
                'default_value' => 1,
                'label' => 'Related Activity',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('performance', 'static')
            ->add('conditions', 'checkbox', [
                'default_value' => 1,
                'label' => 'conditions',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('results', 'checkbox', [
                'default_value' => 1,
                'label' => 'Results',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('legacy data', 'checkbox', [
                'default_value' => 1,
                'label' => 'Legacy Data',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ]);
    }
}