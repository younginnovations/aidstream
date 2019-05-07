<?php namespace App\Core\V203\Forms\Activity;

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
            ->add('ref', 'text', ['label' => trans('elementForm.ref'), 'help_block' => $this->addHelpText('organisation-ref')])
            ->add('activity_id', 'text', ['label' => trans('elementForm.activity_id')])
            ->addSelect(
                'type',
                $this->getCodeList('OrganizationType', 'Organization'),
                trans('elementForm.type'),
                $this->addHelpText('provider_org-type')
            )
            ->addNarrative('provider_org_narrative')
            ->addAddMoreButton('add_provider_org_narrative', 'provider_org_narrative');
    }
}
