<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ReceiverOrg
 * @package App\Core\V202\Forms\Activity
 */
class ReceiverOrg extends BaseForm
{
    /**
     * builds receiver org form
     */
    public function buildForm()
    {
        $this
            ->add('ref', 'text')
            ->add('activity_id', 'text')
            ->add('type', 'text')
            ->addNarrative('receiver_org_narrative')
            ->addAddMoreButton('add_receiver_org_narrative', 'receiver_org_narrative');
    }
}
