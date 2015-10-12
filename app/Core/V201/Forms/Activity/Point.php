<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Point
 * @package App\Core\V201\Forms\Activity
 */
class Point extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds point form
     */
    public function buildForm()
    {
        $this
            ->add('srs_name', 'text')
            ->add(
                'position',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\Position',
                        'label' => false,
                    ]
                ]
            );
    }
}
