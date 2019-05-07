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
            ->add('ref', 'text', ['label' => trans('elementForm.ref'), 'help_block' => $this->addHelpText('organisation-ref')])
            ->add('activity_id', 'text', ['label' => trans('elementForm.activity_id')])
            ->addSelect(
                'type',
                $this->getCodeList('OrganizationType', 'Organization'),
                trans('elementForm.type'),
                $this->addHelpText('receiver_org-type')
            )
            ->addNarrative('receiver_org_narrative')
            ->addAddMoreButton('add_receiver_org_narrative', 'receiver_org_narrative');
    }
}
