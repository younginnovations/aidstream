<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class DefaultFieldGroupsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('identification', 'static')
            ->add(
                'other_activity_identifier',
                'checkbox',
                [
                    'value' => 'Other Activity Identifier',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('basic_activity_information', 'static')
            ->add(
                'title',
                'checkbox',
                [
                    'value' => 'Title',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'description',
                'checkbox',
                [
                    'value' => 'Description',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'activity_status',
                'checkbox',
                [
                    'value' => 'Activity Status',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'activity_date',
                'checkbox',
                [
                    'value' => 'Activity Date',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'contact_info',
                'checkbox',
                [
                    'value' => 'Contact Info',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'activity_scope',
                'checkbox',
                [
                    'value' => 'Activity Scope',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('participation_organization', 'static')
            ->add(
                'participating_org',
                'checkbox',
                [
                    'value' => 'Participating Org',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('Geopolitical_information', 'static')
            ->add(
                'recipient_county',
                'checkbox',
                [
                    'value' => 'Recipient Country',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'recipient_region',
                'checkbox',
                [
                    'value' => 'Recipient Region',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'location',
                'checkbox',
                [
                    'value' => 'Location',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('classifications', 'static')
            ->add(
                'sector',
                'checkbox',
                [
                    'value' => 'Sector',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'policy_maker',
                'checkbox',
                [
                    'value' => 'Policy Maker',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'collaboration_type',
                'checkbox',
                [
                    'value' => 'Collaboration Type',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'default_flow_type',
                'checkbox',
                [
                    'value' => 'Default Flow Type',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'default_finance_type',
                'checkbox',
                [
                    'value' => 'Default Finance Type',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'default_aid_type',
                'checkbox',
                [
                    'value' => 'Default Aid Type',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'default_tied_status',
                'checkbox',
                [
                    'value' => 'Default Tied Status',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'country_budget_items',
                'checkbox',
                [
                    'value' => 'Country Budget Items',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('financial', 'static')
            ->add(
                'budget',
                'checkbox',
                [
                    'value' => 'Budget',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'planned_disbursement',
                'checkbox',
                [
                    'value' => 'Planned Disbursement',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'transaction',
                'checkbox',
                [
                    'value' => 'Transaction',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'capital_spend',
                'checkbox',
                [
                    'value' => 'Capital Spend',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('related_documents', 'static')
            ->add(
                'document_ink',
                'checkbox',
                [
                    'value' => 'Document Link',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('relations', 'static')
            ->add(
                'related_activity',
                'checkbox',
                [
                    'value' => 'Related Activity',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add('performance', 'static')
            ->add(
                'conditions',
                'checkbox',
                [
                    'value' => 'conditions',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'results',
                'checkbox',
                [
                    'value' => 'Results',
                    'attr'  => ['class' => 'field1']
                ]
            )
            ->add(
                'legacy_data',
                'checkbox',
                [
                    'value' => 'Legacy Data',
                    'attr'  => ['class' => 'field1']
                ]
            );
    }
}