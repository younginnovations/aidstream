<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

/**
 * Class Classifications
 * @package App\Core\V201\Forms\Settings
 */
class Classifications extends BaseForm
{
    /**
     * build classifications form
     */
    public function buildForm()
    {
        $this
            ->addCheckBox('sector', 'Sector', true, 'readonly')
            ->addCheckBox('policy_maker', 'Policy Maker')
            ->addCheckBox('collaboration_type', 'Collaboration Type')
            ->addCheckBox('default_flow_type', 'Default Flow Type')
            ->addCheckBox('default_finance_type', 'Default Finance Type')
            ->addCheckBox('default_aid_type', 'Default Aid Type')
            ->addCheckBox('default_tied_status', 'Default Tied Status')
            ->addCheckBox('country_budget_items', 'Country Budget Items');
    }
}
