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
            ->add('organization_identifier_code', 'text', ['label' => trans('elementForm.organisation_identifier_code')])
            ->add('provider_activity_id', 'text', ['label' => trans('elementForm.provider_activity_id')])
            ->add('type', 'text', ['label' => trans('elementForm.type')])
            ->addNarrative('provider_org_narrative')
            ->addAddMoreButton('add_provider_org_narrative', 'provider_org_narrative');
    }
}
