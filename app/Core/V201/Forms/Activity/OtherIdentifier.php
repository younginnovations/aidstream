<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OtherIdentifier
 * contains function that creates Other Identifier Form
 * @package App\Core\V201\Forms\Activity
 */
class OtherIdentifier extends Form
{
    public function buildForm()
    {
        $json                = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/OtherIdentifierType.json")
        );
        $response            = json_decode($json, true);
        $otherIdentifierType = $response['OtherIdentifierType'];
        $typeCodes           = [];
        foreach ($otherIdentifierType as $val) {
            $typeCodes[$val['code']] = $val['code'] . ' - ' . $val['name'];
        }
        $this
            ->add('reference', 'text')
            ->add(
                'type',
                'select',
                [
                    'choices' => $typeCodes,
                    'label'   => 'Type'
                ]
            )
            ->add(
                'ownerOrg',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\OwnerOrg',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form owner_organization'
                    ]
                ]
            )
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
