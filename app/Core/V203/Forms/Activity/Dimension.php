<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Locations
 * @package App\Core\V201\Forms\Activity
 */
class Dimension extends BaseForm
{
    /**
     * builds locations form
     */
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => trans('elementForm.name'), 'help_block' => $this->addHelpText('ActivityResultsIndicatorDimensionName')])
            ->add('value', 'text', ['label' => trans('elementForm.value'), 'help_block' => $this->addHelpText('ActivityResultsIndicatorDimensionValue')])
            ->addRemoveThisButton('remove_dimension');
    }
}
