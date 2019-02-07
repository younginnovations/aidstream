<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Comment;

/**
 * Class Actual
 * Contains the function to create the Period Actual form
 * @package App\Core\V202\Forms\Activity
 */
class Actual extends BaseForm
{
    use Comment;

    /**
     * builds the result actual form
     */
    public function buildForm()
    {
        $this
            ->add('value', 'text', ['label' => trans('elementForm.value')])
            ->addCollection('location', 'Activity\TargetLocation', 'actual_location', [], trans('elementForm.location'))
            ->addAddMoreButton('add_target_location', 'actual_location')
            ->addCollection('dimension', 'Activity\TargetDimension', 'actual_dimension', [], trans('elementForm.dimension'))
            ->addAddMoreButton('add_actual_dimension', 'actual_dimension')
            ->addComments();
    }
}
