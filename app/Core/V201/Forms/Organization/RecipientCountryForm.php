<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class RecipientCountryForm extends Form
{
    public function buildForm()
    {
        $json     = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Organization/CountryCodelist.json")
        );
        $response = json_decode($json, true);
        $country  = $response['Country'];
        $code_arr = [];
        foreach ($country as $val) {
            $code_arr[$val['code']] = $val['code'] . ' - ' . $val['name'];
        }
        $this
            ->add(
                'code',
                'select',
                [
                    'choices' => $code_arr,
                    'label'   => 'Code'
                ]
            )
            ->add(
                'narrative',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_country_narrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'recipient_country_narrative'
                    ]
                ]
            );
    }
}