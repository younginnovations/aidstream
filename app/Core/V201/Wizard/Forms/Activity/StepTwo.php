<?php namespace App\Core\V201\Wizard\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class StepTwo
 * @package App\Core\V201\Wizard\Forms\Activity
 */
class StepTwo extends BaseForm
{
    /**
     * builds the activity iati identifier wizard form
     */
    public function buildForm()
    {
        $this
            ->add(
                'title',
                'text',
                [
                    'label' => 'What is your activity title?'
                ]
            )
            ->add(
                'general',
                'text',
                [
                    'label' => 'General description of activity'
                ]
            )
            ->add(
                'objective',
                'text',
                [
                    'label' => 'Objective description of activity'
                ]
            )
            ->add(
                'target',
                'text',
                [
                    'label' => 'Target description of activity'
                ]
            );
    }
}
