<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Website
 * @package App\Core\V201\Forms\Activity
 */
class Website extends Form
{
    /**
     * builds the contact info Website form
     */
    public function buildForm()
    {
        $this
            ->add('website', 'text')
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
