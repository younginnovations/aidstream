<?php namespace App\SuperAdmin\Forms;

use App\Core\Form\BaseForm;
use App\Core\Traits\OrganizationName;
use App\Services\Organization\OrganizationManager;

/**
 * Class OrganizationGroupInformation
 * @package App\SuperAdmin\Forms
 */
class OrganizationGroupInformation extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * @param OrganizationManager $organizationManager
     */
    function __construct(OrganizationManager $organizationManager)
    {
        $this->organizationManager = $organizationManager;
    }

    /**
     * builds organization group information form
     */
    public function buildForm()
    {
        $this
            ->add('group_name', 'text')
            ->add(
                'organizations',
                'choice',
                [
                    'choices'  => $this->getOrganizationName(),
                    'multiple' => true
                ]
            )
            ->add(
                'group_identifier',
                'text',
                [
                    'attr'       => [
                        'id' => 'group_identifier'
                    ],
                    'help_block' => [
                        'text' => "Your group identifier will be used as a prefix for your organisation group. We recommend that you use a short abbreviation that uniquely identifies your organisation group. If your group identifier is 'abc' the username for the group created with this registration will be 'abc_group'.",
                        'tag'  => 'p',
                        'attr' => ['class' => 'help-block']
                    ],
                    'label'      => 'Group Identifier'
                ]
            );
    }

    /**
     * get organization names for select box
     * @return array
     */
    protected function getOrganizationName()
    {
        $organizations = $this->organizationManager->getOrganizations();
        $data          = [];
        foreach ($organizations as $organization) {
            $data[$organization['id']] = $organization['name'];
        }

        return $data;
    }
}
