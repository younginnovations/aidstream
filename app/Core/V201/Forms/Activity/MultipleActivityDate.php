<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleActivityDate
 * @package App\Core\V201\Forms\Activity
 */
class MultipleActivityDate extends BaseForm
{
    /**
     * builds activity date form
     */
    public function buildForm()
    {
        $this
            ->addCollection('activity_date', 'Activity\ActivityDate', 'Activity_date')
            ->addAddMoreButton('add_Activity_date', 'Activity_date');
    }
}
