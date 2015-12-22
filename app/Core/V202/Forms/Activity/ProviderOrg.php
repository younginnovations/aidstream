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
            ->add('ref', 'text')
            ->add('activity_id', 'text')
            ->add('type', 'text')
            ->addNarrative('provider_org_narrative')
            ->addAddMoreButton('add_provider_org_narrative', 'provider_org_narrative');
    }
}
