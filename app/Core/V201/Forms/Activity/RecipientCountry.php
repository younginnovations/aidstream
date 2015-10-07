<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Forms\Activity
 */
class RecipientCountry extends Form
{
    protected $showFieldErrors = true;

    /**
     * builds recipient country form
     */
    public function buildForm()
    {
        $countryCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Organization/CountryCodelist.json")
        );
        $countryList     = json_decode($countryCodeList, true);
        $countries       = $countryList['Country'];
        $countryCode     = [];

        foreach ($countries as $country) {
            $countryCode[$country['code']] = $country['code'] . ' - ' . $country['name'];
        }

        $this
            ->add(
                'country_code',
                'select',
                [
                    'choices' => $countryCode,
                ]
            )
            ->add('percentage', 'text')
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
