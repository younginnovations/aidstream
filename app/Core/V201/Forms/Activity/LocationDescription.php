<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LocationDescription
 * @package App\Core\V201\Forms\Activity
 */
class LocationDescription extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds location description form
     */
    public function buildForm()
    {
        $this
            ->addNarrative('location_description_narrative')
            ->addAddMoreButton('add', 'location_description_narrative');
    }
}
