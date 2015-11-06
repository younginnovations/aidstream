<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class RelatedActivities
 * @package App\Core\V201\Forms\Activity
 */
class RelatedActivities extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('related_activity', 'Activity\RelatedActivity', 'related_activity')
            ->addAddMoreButton('add_related_activity', 'related_activity');
    }
}
