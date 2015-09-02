<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class DefaultFieldGroupsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('identification', 'static')
            ->add('otherActivityIdentifier', 'checkbox', [
                'value' => 'Other Activity Identifier',
                'label' => 'Other Activity Identifier',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('basic activity information', 'static')
            ->add('title', 'checkbox', [
                'value' => 'Title',
                'label' => 'Title',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('description', 'checkbox', [
                'label' => 'Description',
                'value' => 'Description',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('activityStatus', 'checkbox', [
                'label' => 'Activity Status',
                'value' => 'Activity Status',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('activityDate', 'checkbox', [
                'label' => 'Activity Date',
                'value' => 'Activity Date',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('contactInfo', 'checkbox', [
                'label' => 'Contact Info',
                'value' => 'Contact Info',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('activityScope', 'checkbox', [
                'label' => 'Activity Scope',
                'value' => 'Activity Scope',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('participation organization', 'static')
            ->add('participatingOrg', 'checkbox', [
                'label' => 'Participating Org',
                'value' => 'Participating Org',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('Geopolitical Information', 'static')
            ->add('recipientCounty', 'checkbox', [
                'label' => 'Recipient Country',
                'value' => 'Recipient Country',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('recipientRegion', 'checkbox', [
                'label' => 'Recipient Region',
                'value' => 'Recipient Region',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('location', 'checkbox', [
                'label' => 'Location',
                'value' => 'Location',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('classifications', 'static')
            ->add('sector', 'checkbox', [
                'label' => 'Sector',
                'value' => 'Sector',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('policyMaker', 'checkbox', [
                'label' => 'Policy Maker',
                'value' => 'Policy Maker',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('collaborationType', 'checkbox', [
                'label' => 'Collaboration Type',
                'value' => 'Collaboration Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('flowType', 'checkbox', [
                'label' => 'Default Flow Type',
                'value' => 'Default Flow Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('financeType', 'checkbox', [
                'label' => 'Default Finance Type',
                'value' => 'Default Finance Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('aidType', 'checkbox', [
                'label' => 'Default Aid Type',
                'value' => 'Default Aid Type',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('tiedStatus', 'checkbox', [
                'label' => 'Default Tied Status',
                'value' => 'Default Tied Status',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('countryBudgetItems', 'checkbox', [
                'label' => 'Country Budget Items',
                'value' => 'Country Budget Items',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('financial','static')
            ->add('budget', 'checkbox', [
                'label' => 'Budget',
                'value' => 'Budget',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('plannedDisbursement', 'checkbox', [
                'label' => 'Planned Disbursement',
                'value' => 'Planned Disbursement',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('transaction', 'checkbox', [
                'label' => 'Transaction',
                'value' => 'Transaction',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('capitalSpend', 'checkbox', [
                'label' => 'Capital Spend',
                'value' => 'Capital Spend',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('related documents', 'static')
            ->add('documentLink', 'checkbox', [
                'label' => 'Document Link',
                'value' => 'Document Link',
                'checked' => true,
                'attr' => ['class' => 'field1']
            ])
            ->add('relations', 'static')
            ->add('relatedActivity', 'checkbox', [
                'label' => 'Related Activity',
                'value' => 'Related Activity',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('performance', 'static')
            ->add('conditions', 'checkbox', [
                'label' => 'conditions',
                'value' => 'conditions',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('results', 'checkbox', [
                'label' => 'Results',
                'value' => 'Results',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ])
            ->add('legacyData', 'checkbox', [
                'label' => 'Legacy Data',
                'value' => 'Legacy Data',
                'checked' => false,
                'attr' => ['class' => 'field1']
            ]);
    }
}