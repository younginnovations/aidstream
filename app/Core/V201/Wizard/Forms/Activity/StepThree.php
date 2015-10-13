<?php namespace App\Core\V201\Wizard\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class StepThree
 * @package App\Core\V201\Wizard\Forms\Activity
 */
class StepThree extends BaseForm
{
    /**
     * builds the activity iati identifier wizard form
     */
    public function buildForm()
    {
        $this
            ->add(
                'activity_status',
                'choice',
                [
                    'label'          => "What is your activity status currently is :",
                    'choices'        => $this->addCodeList('ActivityStatus', 'Activity'),
                    'expanded'       => true,
                    'choice_options' => [
                        'wrapper' => ['class' => 'choice-wrapper']
                    ]
                ]
            )
            ->add('start_date', 'date')
            ->add('end_date', 'date')
            ->add(
                'date_type',
                'choice',
                [
                    'label'    => "What is your activity state currently is :",
                    'choices'  => ['1' => 'planned', '2' => 'actual'],
                    'expanded' => true
                ]
            );
    }
}
