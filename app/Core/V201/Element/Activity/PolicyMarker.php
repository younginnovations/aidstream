<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class PolicyMarker
 * @package App\Core\V201\Element\Activity
 */
class PolicyMarker extends BaseElement
{

    /**
     * @return string
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\PolicyMarkers';
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\PolicyMarker');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData  = [];
        $policyMarkers = (array) $activity->policy_marker;
        foreach ($policyMarkers as $policyMarker) {
            $activityData[] = [
                '@attributes' => [
                    'vocabulary'   => $policyMarker['vocabulary'],
                    'code'         => $policyMarker['policy_marker'],
                    'significance' => $policyMarker['significance']
                ],
                'narrative'   => $this->buildNarrative($policyMarker['narrative'])
            ];
        }

        return $activityData;
    }
}
