<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Telephone
 * @package App\Core\V201\Forms\Activity
 */
class Telephone extends Form
{
    /**
     * builds the contact info telephone form
     */
    public function buildForm()
    {
        $this
            ->add('telephone', 'text')
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
