<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OtherIdentifier
 * Activity other identifier form to collect activity other identifier
 * @package App\Core\V201\Forms\Activity
 */
class OtherIdentifier extends Form
{
    public function buildForm()
    {
        $json                 = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/OtherIdentifierType.json")
        );
        $otherIdentifierTypes = json_decode($json, true);
        $otherIdentifierType  = $otherIdentifierTypes['OtherIdentifierType'];
        $typeCodes            = [];
        foreach ($otherIdentifierType as $otherIdentifier) {
            $typeCodes[$otherIdentifier['code']] = $otherIdentifier['code'] . ' - ' . $otherIdentifier['name'];
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
                ]
            );
    }
}
