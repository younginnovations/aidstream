<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ParticipatingOrganization
 * @package App\Core\V201\Forms\Activity
 */
class ParticipatingOrganization extends BaseForm
{
    /**
     * builds activity participating organization form
     */
    public function buildForm()
    {
        $this
            ->add(
                'organization_role',
                'select',
                [
                    'choices' => $this->addCodeList('OrganisationRole', 'Activity'),
                    'label' => 'Organization Role'
                ]
            )
            ->add('identifier', 'text')
            ->add(
                'organization_type',
                'select',
                [
                    'choices' => $this->addCodeList('OrganisationType', 'Activity'),
                    'label' => 'Organization Type'
                ]
            )
            ->addNarrative('narrative', 'Organization Name')
            ->addAddMoreButton('add', 'narrative')
            ->addRemoveThisButton('remove_narrative');
    }
}
