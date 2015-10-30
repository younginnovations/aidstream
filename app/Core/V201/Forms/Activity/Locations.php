<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Locations
 * @package App\Core\V201\Forms\Activity
 */
class Locations extends BaseForm
{
    /**
     * builds locations form
     */
    public function buildForm()
    {
        $this
            ->addCollection('location', 'Activity\Location', 'location')
            ->addAddMoreButton('add', 'recipient_country');
    }
}
