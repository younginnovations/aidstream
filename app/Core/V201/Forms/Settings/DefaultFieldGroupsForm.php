<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class DefaultFieldGroupsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('identification', 'static')
            ->add('other activity identifier', 'checkbox', [
                'default_value' => 1,
                'label' => 'Other Activity Identifier',
                'checked' => false
            ])
            ->add('basic activity information', 'static')
            ->add('title', 'checkbox', [
                'default_value' => 1,
                'label' => 'Title',
                'checked' => true
            ])
            ->add('description', 'checkbox', [
                'default_value' => 1,
                'label' => 'Description',
                'checked' => true
            ])
            ->add('activity status', 'checkbox', [
                'default_value' => 1,
                'label' => 'Activity Status',
                'checked' => true
            ])
            ->add('activity date', 'checkbox', [
                'default_value' => 1,
                'label' => 'Activity Date',
                'checked' => true
            ])
            ->add('contact info', 'checkbox', [
                'default_value' => 1,
                'label' => 'Contanct info',
                'checked' => false
            ])
            ->add('activity scope', 'checkbox', [
                'default_value' => 1,
                'label' => 'Activity Scope',
                'checked' => false
            ])
            ->add('participation organization', 'static')
            ->add('participating org', 'checkbox', [
                'default_value' => 1,
                'label' => 'Participating Org',
                'checked' => true
            ])
            ->add('Geopolitical Information', 'static')
            ->add('recipient county', 'checkbox', [
                'default_value' => 1,
                'label' => 'Recipient Country',
                'checked' => true
            ])
            ->add('recipient region', 'checkbox', [
                'default_value' => 1,
                'label' => 'Recipient Region',
                'checked' => false
            ])
            ->add('location', 'checkbox', [
                'default_value' => 1,
                'label' => 'Location',
                'checked' => true
            ])
            ->add('classifications', 'static')
            ->add('sector', 'checkbox', [
                'default_value' => 1,
                'label' => 'Sector',
                'checked' => true
            ])
            ->add('policy maker', 'checkbox', [
                'default_value' => 1,
                'label' => 'Policy Maker',
                'checked' => false
            ])
            ->add('collaboration type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Collaboration Type',
                'checked' => false
            ])
            ->add('flow type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Flow Type',
                'checked' => false
            ])
            ->add('finance type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Finance Type',
                'checked' => false
            ])
            ->add('aid type', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Aid Type',
                'checked' => false
            ])
            ->add('tied status', 'checkbox', [
                'default_value' => 1,
                'label' => 'Default Tied Status',
                'checked' => false
            ])
            ->add('country budget items', 'checkbox', [
                'default_value' => 1,
                'label' => 'Country Budget Items',
                'checked' => false
            ])
            ->add('financial','static')
            ->add('budget', 'checkbox', [
                'default_value' => 1,
                'label' => 'Budget',
                'checked' => true
            ])
            ->add('planned disbursement', 'checkbox', [
                'default_value' => 1,
                'label' => 'Planned Disbursement',
                'checked' => false
            ])
            ->add('transaction', 'checkbox', [
                'default_value' => 1,
                'label' => 'Transaction',
                'checked' => true
            ])
            ->add('capital spend', 'checkbox', [
                'default_value' => 1,
                'label' => 'Capital Spend',
                'checked' => false
            ])
            ->add('related documents', 'static')
            ->add('document link', 'checkbox', [
                'default_value' => 1,
                'label' => 'Document Link',
                'checked' => true
            ])
            ->add('relations', 'static')
            ->add('related activity', 'checkbox', [
                'default_value' => 1,
                'label' => 'Related Activity',
                'checked' => false
            ])
            ->add('performance', 'static')
            ->add('conditions', 'checkbox', [
                'default_value' => 1,
                'label' => 'conditions',
                'checked' => false
            ])
            ->add('results', 'checkbox', [
                'default_value' => 1,
                'label' => 'Results',
                'checked' => false
            ])
            ->add('legacy data', 'checkbox', [
                'default_value' => 1,
                'label' => 'Legacy Data',
                'checked' => false
            ]);
    }
}