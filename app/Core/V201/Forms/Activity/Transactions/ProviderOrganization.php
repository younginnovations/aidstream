<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class ProviderOrganization
 * @package App\Core\V201\Forms\Activity
 */
class ProviderOrganization extends BaseForm
{
    /**
     * builds ProviderOrganization form
     */
    public function buildForm()
    {
        $this
            ->add('organization_identifier_code', 'text', ['help_block' => $this->addHelpText('Activity_Transaction_ProviderOrg-ref')])
            ->add('provider_activity_id', 'text', ['help_block' => $this->addHelpText('Activity_Transaction_ProviderOrg-provider_activity_id')])
            ->addNarrative('provider_org_narrative')
            ->addAddMoreButton('add_narrative', 'provider_org_narrative');
    }
}
