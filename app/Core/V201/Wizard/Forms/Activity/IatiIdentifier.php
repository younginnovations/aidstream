<?php namespace App\Core\V201\Wizard\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class IatiIdentifier
 * @package App\Core\V201\Wizard\Forms\Activity
 */
class IatiIdentifier extends BaseForm
{
    /**
     * builds the activity iati identifier wizard form
     */
    public function buildForm()
    {
        $this
            ->add(
                'activity_identifier',
                'text',
                [
                    'label' => 'What is your activity identifier?',
                    'wrapper' => ['class' => 'form-group col-xs-12 col-sm-6']
                ]
            );
    }
}
