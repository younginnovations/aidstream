<?php namespace App\Core\V203\Forms\Activity;

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
            ->add('ref', 'text', ['label' => trans('elementForm.ref')])
            ->add('activity_id', 'text', ['label' => trans('elementForm.activity_id')])
            ->add('type', 'text', ['label' => trans('elementForm.type')])
            ->addNarrative('receiver_org_narrative')
            ->addAddMoreButton('add_receiver_org_narrative', 'receiver_org_narrative');
    }
}
