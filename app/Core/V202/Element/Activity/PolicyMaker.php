<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\PolicyMaker as V201PolicyMaker;
use App\Models\Activity\Activity;

/**
 * Class PolicyMaker
 * @package App\Core\V202\Element\Activity
 */
class PolicyMaker extends V201PolicyMaker
{
    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $policyMakers = (array) $activity->policy_maker;
        foreach ($policyMakers as $policyMaker) {
            $activityData[] = [
                '@attributes' => [
                    'vocabulary'     => $policyMaker['vocabulary'],
                    'vocabulary-uri' => $policyMaker['vocabulary_uri'],
                    'code'           => $policyMaker['policy_marker'],
                    'significance'   => $policyMaker['significance']
                ],
                'narrative'   => $this->buildNarrative($policyMaker['narrative'])
            ];
        }

        return $activityData;
    }
}
