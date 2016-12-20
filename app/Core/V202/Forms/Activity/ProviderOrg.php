<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ProviderOrg
 * @package App\Core\V202\Forms\Activity
 */
class ProviderOrg extends BaseForm
{
    /**
     * builds provider org form
     */
    public function buildForm()
    {
        $this
            ->add('ref', 'text', ['label' => trans('elementForm.ref')])
            ->add('activity_id', 'text', ['label' => trans('elementForm.activity_id')])
            ->add('type', 'text', ['label' => trans('elementForm.type')])
            ->addNarrative('provider_org_narrative')
            ->addAddMoreButton('add_provider_org_narrative', 'provider_org_narrative');
    }
}
