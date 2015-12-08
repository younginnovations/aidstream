<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Description
 * @package App\Core\V201\Forms\Activity
 */
class Description extends BaseForm
{
    /**
     * builds activity description form
     */
    public function buildForm()
    {
        $this
            ->add(
                'type',
                'select',
                [
                    'choices'     => $this->getCodeList('DescriptionType', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Description Type'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addRemoveThisButton('remove_description');
    }
}
