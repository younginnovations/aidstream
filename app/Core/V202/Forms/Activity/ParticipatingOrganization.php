<?php namespace App\Core\V202\Forms\Activity;

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
            ->addSelect('organization_role', $this->getCodeList('OrganisationRole', 'Activity'), 'Organization Role', $this->addHelpText('Activity_ParticipatingOrg-role'), null, true)
            ->add('identifier', 'text', ['help_block' => $this->addHelpText('Activity_ParticipatingOrg-ref')])
            ->addSelect('organization_type', $this->getCodeList('OrganisationType', 'Activity'), 'Organisation Type', $this->addHelpText('Activity_ParticipatingOrg-type'))
            ->add('activity_id', 'text')
            ->addNarrative('narrative', 'Organization Name')
            ->addAddMoreButton('add', 'narrative')
            ->addRemoveThisButton('remove_narrative');
    }
}
