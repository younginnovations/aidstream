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
        $this->addSelect('organization_role', $this->getCodeList('OrganisationRole', 'Activity'), trans('elementForm.organisation_role'), $this->addHelpText('Activity_ParticipatingOrg-role'), null, true)
             ->add('identifier', 'text', ['label' => trans('elementForm.identifier'),'help_block' => $this->addHelpText('Activity_ParticipatingOrg-ref')])
             ->addSelect('organization_type', $this->getCodeList('OrganisationType', 'Activity'), trans('elementForm.organisation_type'), $this->addHelpText('Activity_ParticipatingOrg-type'))
             ->addNarrative('narrative', trans('elementForm.organisation_name'))
             ->addAddMoreButton('add', 'narrative')
             ->addRemoveThisButton('remove_narrative');
    }
}
