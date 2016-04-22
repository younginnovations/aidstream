<?php namespace App\Core\V202\Element\Activity;

use App\Models\Activity\Activity;
use App\Core\V201\Element\Activity\ParticipatingOrganization as V201ParticipatingOrganization;

class ParticipatingOrganization extends V201ParticipatingOrganization
{

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData               = [];
        $participatingOrganizations = (array) $activity->participating_organization;
        foreach ($participatingOrganizations as $participatingOrganization) {
            $activityData[] = [
                '@attributes' => [
                    'ref'         => $participatingOrganization['identifier'],
                    'type'        => $participatingOrganization['organization_type'],
                    'role'        => $participatingOrganization['organization_role'],
                    'activity-id' => getVal($participatingOrganization, ['activity_id'])
                ],
                'narrative'   => $this->buildNarrative($participatingOrganization['narrative'])
            ];
        }

        return $activityData;
    }
}
