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
            ->add('organization_identifier_code', 'text')
            ->add('receiver_activity_id', 'text')
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
