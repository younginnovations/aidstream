<?php namespace App\Core\V202\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class ReceiverOrganization
 * @package App\Core\V202\Forms\Activity\Transactions
 */
class ReceiverOrganization extends BaseForm
{
    /**
     * builds receiver org form
     */
    public function buildForm()
    {
        $this
            ->add('organization_identifier_code', 'text', ['label' => trans('elementForm.organisation_identifier_code')])
            ->add('receiver_activity_id', 'text', ['label' => trans('elementForm.receiver_activity_id')])
            ->add('type', 'text', ['label' => trans('elementForm.type')])
            ->addNarrative('receiver_org_narrative')
            ->addAddMoreButton('add_receiver_org_narrative', 'receiver_org_narrative');
    }
}
