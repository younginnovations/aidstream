<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleDescription
 * @package App\Core\V201\Forms\Activity
 */
class MultipleDescription extends BaseForm
{
    /**
     * builds activity description form
     */
    public function buildForm()
    {
        $this
            ->addCollection('description', 'Activity\Description', 'description')
            ->addAddMoreButton('add_description', 'description');
    }
}
