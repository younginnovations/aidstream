<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Description
 * @package App\Core\V201\Forms\Activity
 */
class Description extends Form
{
    /**
     * builds activity description form
     */
    public function buildForm()
    {
        $descriptionCodeList  = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/DescriptionType.json")
        );
        $descriptionTypes = json_decode($descriptionCodeList, true);
        $descriptionType  = $descriptionTypes['DescriptionType'];
        $descriptionCode  = [];

        foreach ($descriptionType as $description) {
            $descriptionCode[$description['code']] = $description['code'] . ' - ' . $description['name'];
        }

        $this
            ->add(
                'type',
                'select',
                [
                    'choices' => $descriptionCode,
                    'label'   => 'Description Type'
                ]
            )
            ->add(
                'narrative',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Narrative',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form narrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'narrative'
                    ]
                ]
            );
    }
}
