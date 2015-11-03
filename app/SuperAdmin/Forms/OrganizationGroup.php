<?php namespace App\SuperAdmin\Forms;

use App\Core\Form\BaseForm;

/**
 * Class OrganizationGroup
 * @package App\SuperAdmin\Forms
 */
class OrganizationGroup extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds the organization group form
     */
    public function buildForm()
    {
        $this
            ->add(
                'new_organization_group',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\SuperAdmin\Forms\OrganizationGroupInformation',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'group_admin_information',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\SuperAdmin\Forms\GroupAdmin',
                        'label' => false,
                    ]
                ]
            )
            ->addSaveButton();
    }
}
