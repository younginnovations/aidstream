<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class RecipientRegion
 * @package App\Core\V201\Forms\Activity
 */
class RecipientRegion extends Form
{
    /**
     * builds activity Recipient Region form
     */
    public function buildForm()
    {
        $regionCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/Region.json")
        );
        $regions        = json_decode($regionCodeList, true);
        $region         = $regions['Region'];
        $regionCode     = [];

        foreach ($region as $recipientRegion) {
            $regionCode[$recipientRegion['code']] = $recipientRegion['code'] . ' - ' . $recipientRegion['name'];
        }

        $regionVocabularyCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/RegionVocabulary.json")
        );
        $regionVocabs             = json_decode($regionVocabularyCodeList, true);
        $regionVocab              = $regionVocabs['RegionVocabulary'];
        $regionVocabCode          = [];

        foreach ($regionVocab as $recipientRegionVocab) {
            $regionVocabCode[$recipientRegionVocab['code']] = $recipientRegionVocab['code'] . ' - ' . $recipientRegionVocab['name'];
        }

        $this
            ->add(
                'region_code',
                'select',
                [
                    'choices' => $regionCode,
                ]
            )
            ->add(
                'region_vocabulary',
                'select',
                [
                    'choices' => $regionVocabCode,
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
