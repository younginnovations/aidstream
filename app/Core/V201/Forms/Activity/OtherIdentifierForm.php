<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

class OtherIdentifierForm extends Form
{
    public function buildForm()
    {
        $json                = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/OtherIdentifierType.json")
        );
        $response            = json_decode($json, true);
        $otherIdentifierType = $response['OtherIdentifierType'];
        $code_arr            = [];
        foreach ($otherIdentifierType as $val) {
            $code_arr[$val['code']] = $val['code'] . ' - ' . $val['name'];
        }
        $this
            ->add('reference', 'text')
            ->add(
                'type',
                'select',
                [
                    'choices' => $code_arr,
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
                        'class' => 'App\Core\V201\Forms\Activity\OwnerOrgForm',
                        'label' => false,
                    ],
                ]
            );
    }
}
