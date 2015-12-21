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
            ->add('name', 'text')
            ->add('value', 'text')
            ->addRemoveThisButton('remove_target_dimension');
    }
}
