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
            ->add('organization_identifier_code', 'text', ['label' => 'Organisation Identifier Code'])
            ->add('receiver_activity_id', 'text')
            ->add('type', 'text')
            ->addNarrative('receiver_org_narrative')
            ->addAddMoreButton('add_receiver_org_narrative', 'receiver_org_narrative');
    }
}
