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
            ->add('organization_identifier_code', 'text')
            ->add('provider_activity_id', 'text')
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
