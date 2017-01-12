<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class TargetDimension
 * @package App\Core\V202\Forms\Activity
 */
class TargetDimension extends BaseForm
{
    /**
     * builds target dimension form
     */
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => trans('elementForm.name')])
            ->add('value', 'text', ['label' => trans('elementForm.value')])
            ->addRemoveThisButton('remove_target_dimension');
    }
}
