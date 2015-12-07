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
                    'choices'     => $this->getCodeList('OrganisationRole', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Organization Role'
                ]
            )
            ->add('identifier', 'text')
            ->add(
                'organization_type',
                'select',
                [
                    'choices'     => $this->getCodeList('OrganisationType', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Organization Type'
                ]
            )
            ->addNarrative('narrative', 'Organization Name')
            ->addAddMoreButton('add', 'narrative')
            ->addRemoveThisButton('remove_narrative');
    }
}
