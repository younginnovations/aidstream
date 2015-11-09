<?php namespace App\SuperAdmin\Forms;

use App\Core\Form\BaseForm;

/**
 * Class OrganizationInformation
 * @package App\SuperAdmin\Forms
 */
class OrganizationInformation extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds the organization information form
     */
    public function buildForm()
    {
        $this
            ->add('name', 'text')
            ->add('address', 'text')
            ->add(
                'user_identifier',
                'text',
                [
                    'attr'       => [
                        'id' => 'organization_user_identifier'
                    ],
                    'help_block' => [
                        'text' => "Your organisation user identifier will be used as a prefix for all the AidStream users in your organisation. We recommend that you use a short abbreviation that uniquely identifies your organisation. If your organisation is 'Acme Bellus Foundation', your organisation user identifier should be 'abf', depending upon it's availability.",
                        'tag'  => 'p',
                        'attr' => ['class' => 'help-block']
                    ],
                    'label'      => 'Organization User Identifier'
                ]
            );
    }
}
