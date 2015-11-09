<?php namespace App\SuperAdmin\Forms;

use App\Core\Form\BaseForm;
use App\Core\Version;

/**
 * Class Organization
 * @package App\SuperAdmin\Forms
 */
class Organization extends BaseForm
{
    protected $showFieldErrors = true;
    protected $version;
    protected $defaultFieldValues;
    protected $defaultFieldGroups;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->defaultFieldValues = $version->getSettingsElement()->getdefaultFieldValues()->getdefaultFieldValuesForm();
        $this->defaultFieldGroups = $version->getSettingsElement()->getdefaultFieldGroups()->getdefaultFieldGroupsForm();
    }

    /**
     * builds the organization form
     */
    public function buildForm()
    {
        $this
            ->add(
                'organization_information',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\SuperAdmin\Forms\OrganizationInformation',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'admin_information',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\SuperAdmin\Forms\AdminInformation',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'default_field_values',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => $this->defaultFieldValues,
                        'label' => false,
                    ],
                    'label'   => false
                ]
            )
            ->add(
                'default_field_groups',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => $this->defaultFieldGroups,
                        'label' => false,
                    ],
                    'label'   => false
                ]
            )
            ->add(
                'Save',
                'submit',
                [
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
    }
}
