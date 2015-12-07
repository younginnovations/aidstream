<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class CollaborationType
 * @package App\Core\V201\Forms\Activity
 */
class CollaborationType extends BaseForm
{
    /**
     * builds the Activity Collaboration Type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'collaboration_type',
                'select',
                [
                    'choices'     => $this->getCodeList('CollaborationType', 'Activity'),
                    'empty_value' => 'Select one of the following option :'
                ]
            );
    }
}
