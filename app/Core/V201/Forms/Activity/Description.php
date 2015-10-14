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
                    'choices' => $this->getCodeList('DescriptionType', 'Activity'),
                    'label'   => 'Description Type'
                ]
            )
            ->getNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
