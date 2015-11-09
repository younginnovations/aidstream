<?php namespace App\SuperAdmin\Forms;

use App\Core\Form\BaseForm;

/**
 * Class AdminInformation
 * @package App\SuperAdmin\Forms
 */
class AdminInformation extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds the admin information form
     */
    public function buildForm()
    {
        $this
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add(
                'username',
                'text',
                [
                    'attr'       => [
                        'readonly' => 'readonly',
                        'id'       => 'admin_username'
                    ],
                    'help_block' => [
                        'text' => "AidStream will create a default username with your Organisation User Identifier as prefix. You will not be able to change '_admin' part of the username. This user will have administrative privilege and can create multiple AidStream users with different set of permissions.",
                        'tag'  => 'p',
                        'attr' => ['class' => 'help-block']
                    ]
                ]
            )
            ->add('email', 'text')
            ->add(
                'password',
                'repeated',
                [
                    'type'           => 'password',
                    'second_name'    => 'password_confirmation',
                    'first_options'  => [],
                    'second_options' => [],
                ]
            );
    }
}
