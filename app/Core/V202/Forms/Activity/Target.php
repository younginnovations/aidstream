<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Result\Comment;

/**
 * Class Target
 * Contains the function to create the target form
 * @package App\Core\V202\Forms\Activity
 */
class Target extends BaseForm
{
    use Comment;

    /**
     * builds the activity target form
     */
    public function buildForm()
    {
        $this
            ->add('value', 'text')
            ->addCollection('location', 'Activity\TargetLocation', 'target_location')
            ->addAddMoreButton('add_target_location', 'target_location')
            ->addCollection('dimension', 'Activity\TargetDimension', 'target_dimension')
            ->addAddMoreButton('add_target_dimension', 'target_dimension')
            ->addComments();
    }
}
