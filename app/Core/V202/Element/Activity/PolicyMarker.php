<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\PolicyMarker as V201PolicyMarker;
use App\Models\Activity\Activity;

/**
 * Class PolicyMarker
 * @package App\Core\V202\Element\Activity
 */
class PolicyMarker extends V201PolicyMarker
{
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
                    'vocabulary'     => $policyMarker['vocabulary'],
                    'vocabulary-uri' => $policyMarker['vocabulary_uri'],
                    'code'           => $policyMarker['policy_marker'],
                    'significance'   => $policyMarker['significance']
                ],
                'narrative'   => $this->buildNarrative($policyMarker['narrative'])
            ];
        }

        return $activityData;
    }
}
