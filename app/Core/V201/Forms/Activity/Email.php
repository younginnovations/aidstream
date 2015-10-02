<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Email
 * @package App\Core\V201\Forms\Activity
 */
class Email extends Form
{
    /**
     * builds the contact info email form
     */
    public function buildForm()
    {
        $this
            ->add('email', 'text')
            ->add(
                'Remove this',
                'button',
                [
                    'attr' => [
                        'class' => 'remove_from_collection',
                    ]
                ]
            );
    }
}
