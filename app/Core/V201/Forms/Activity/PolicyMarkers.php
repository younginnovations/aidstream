<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PolicyMarkers
 * @package App\Core\V201\Forms\Activity
 */
class PolicyMarkers extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('policy_marker', 'Activity\PolicyMarker', 'policy_marker')
            ->addAddMoreButton('add_policy_marker', 'policy_marker');
    }
}
