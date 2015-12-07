<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Conditions
 * @package App\Core\V201\Forms\Activity
 */
class Conditions extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'condition_attached',
                'select',
                [
                    'choices'     => ['0' => 'No', '1' => 'Yes'],
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Condition Attached'
                ]
            )
            ->addCollection('condition', 'Activity\Condition', 'condition')
            ->addAddMoreButton('add_condition', 'condition');
    }
}
