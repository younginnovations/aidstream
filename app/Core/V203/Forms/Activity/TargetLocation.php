<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class TargetLocation
 * @package App\Core\V202\Forms\Activity
 */
class TargetLocation extends BaseForm
{
    /**
     * builds target location form
     */
    public function buildForm()
    {
        $this
            ->add('ref', 'text', ['label' => trans('elementForm.ref')])
            ->addRemoveThisButton('remove_target_location');
    }
}
