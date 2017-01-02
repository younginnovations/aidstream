<?php namespace App\Lite\Forms\V202;

use App\Core\Form\BaseForm;

/**
 * Class ActivityDuplicate
 * @package App\Lite\Forms\V202
 */
class ActivityDuplicate extends BaseForm
{
    /**
     * ActivityDuplicate Form
     */
    public function buildForm()
    {
        return $this
            ->add('activityIdentifier', 'text', ['label' => trans('lite/elementForm.activity_identifier'), 'required' => true, 'wrapper' => ['class' => 'form-group col-sm-6'],])
            ->add('activityId', 'hidden');
    }
}
