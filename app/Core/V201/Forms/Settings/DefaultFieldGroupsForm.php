<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class DefaultFieldGroupsForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('identification', 'static')
            ->addCheckBox('other_activity_identifier', 'Other Activity Identifier')
            ->add('basic_activity_information', 'static')
            ->addCheckBox('title', 'Title')
            ->addCheckBox('description', 'Description')
            ->addCheckBox('activity_status', 'Activity Status')
            ->addCheckBox('activity_date', 'Activity Date')
            ->addCheckBox('contact_info', 'Contact Info')
            ->addCheckBox('activity_scope', 'Activity Scope')
            ->add('participation_organization', 'static')
            ->addCheckBox('participating_org', 'Participating Org')
            ->add('Geopolitical_information', 'static')
            ->addCheckBox('recipient_country', 'Recipient Country')
            ->addCheckBox('recipient_region', 'Recipient Region')
            ->addCheckBox('location', 'Location')
            ->add('classifications', 'static')
            ->addCheckBox('sector', 'Sector')
            ->addCheckBox('policy_maker', 'Policy Maker')
            ->addCheckBox('collaboration_type', 'Collaboration Type')
            ->addCheckBox('default_flow_type', 'Default Flow Type')
            ->addCheckBox('default_finance_type', 'Default Finance Type')
            ->addCheckBox('default_aid_type', 'Default Aid Type')
            ->addCheckBox('default_tied_status', 'Default Tied Status')
            ->addCheckBox('country_budget_items', 'Country Budget Items')
            ->add('financial', 'static')
            ->addCheckBox('budget', 'Budget')
            ->addCheckBox('planned_disbursement', 'Planned Disbursement')
            ->addCheckBox('transaction', 'Transaction')
            ->addCheckBox('capital_spend', 'Capital Spend')
            ->add('related_documents', 'static')
            ->addCheckBox('document_link', 'Document Link')
            ->add('relations', 'static')
            ->addCheckBox('related_activity', 'Related Activity')
            ->add('performance', 'static')
            ->addCheckBox('conditions', 'conditions')
            ->addCheckBox('results', 'Results')
            ->addCheckBox('legacy_data', 'Legacy Data');
    }
}
