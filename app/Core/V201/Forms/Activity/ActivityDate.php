<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ActivityDate
 * @package App\Core\V201\Forms\Activity
 */
class ActivityDate extends Form
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $activityDateCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/ActivityDate.json")
        );
        $activityDates        = json_decode($activityDateCodeList, true);
        $activityDate         = $activityDates['ActivityDateType'];
        $activityDateCode     = [];

        foreach ($activityDate as $date) {
            $activityDateCode[$date['code']] = $date['code'] . ' - ' . $date['name'];
        }

        $this
            ->add('date', 'date')
            ->add(
                'type',
                'select',
                [
                    'choices' => $activityDateCode,
                    'label'   => 'Activity Date Type'
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
