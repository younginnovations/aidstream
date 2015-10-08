<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class Sectors extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'sector',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\Sector',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form sector'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'sector');
    }
}
