<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class ReceiverOrganization
 * @package App\Core\V201\Forms\Activity
 */
class ReceiverOrganization extends BaseForm
{
    /**
     * builds ProviderOrganization form
     */
    public function buildForm()
    {
        $this
            ->add('organization_identifier_code', 'text', ['help_block' => $this->addHelpText('Activity_Transaction_ReceiverOrg-ref'), 'label' => trans('elementForm.organisation_identifier_code')])
            ->add('receiver_activity_id', 'text', ['label' => trans('elementForm.receiver_activity_id'), 'help_block' => $this->addHelpText('Activity_Transaction_ReceiverOrg-receiver_activity_id')])
            ->addNarrative('receiver_org_narrative')
            ->addAddMoreButton('add_narrative', 'receiver_org_narrative');
    }
}
