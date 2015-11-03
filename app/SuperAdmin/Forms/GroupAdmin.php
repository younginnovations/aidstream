<?php namespace App\SuperAdmin\Forms;

use App\Core\Form\BaseForm;

/**
 * Class GroupAdmin
 * @package App\SuperAdmin\Forms
 */
class GroupAdmin extends BaseForm
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
                        'id'       => 'group_admin_username'
                    ],
                    'help_block' => [
                        'text' => "User Name is a combination of Group Identifier and '_group'. You may only change Group Identifier portion of the username.",
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
