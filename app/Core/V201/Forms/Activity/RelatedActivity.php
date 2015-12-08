<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class RelatedActivity
 * @package App\Core\V201\Forms\Activity
 */
class RelatedActivity extends BaseForm
{
    /**
     * builds activity activity date form
     */
    public function buildForm()
    {
        $this
            ->add(
                'relationship_type',
                'select',
                [
                    'choices'     => $this->getCodeList('RelatedActivityType', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Type of Relationship'
                ]
            )
            ->add('activity_identifier', 'text')
            ->addRemoveThisButton('remove');
    }
}
