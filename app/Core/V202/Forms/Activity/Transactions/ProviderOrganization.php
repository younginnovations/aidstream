<?php namespace App\Core\V202\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class ProviderOrganization
 * @package App\Core\V202\Forms\Activity\Transactions
 */
class ProviderOrganization extends BaseForm
{
    /**
     * builds provider org form
     */
    public function buildForm()
    {
        $this
            ->add('organization_identifier_code', 'text', ['label' => 'Organisation Identifier Code'])
            ->add('provider_activity_id', 'text')
            ->add('type', 'text')
            ->addNarrative('provider_org_narrative')
            ->addAddMoreButton('add_provider_org_narrative', 'provider_org_narrative');
    }
}
