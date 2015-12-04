<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class ParticipatingOrganization
 * @package app\Core\V201\Element\Activity
 */
class ParticipatingOrganization extends BaseElement
{
    /**
     * @return  Participating Organization form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleParticipatingOrganization";
    }

    /**
     * @return Participating Organization repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ParticipatingOrganization');
    }

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
                    'ref'  => $participatingOrganization['identifier'],
                    'type' => $participatingOrganization['organization_type'],
                    'role' => $participatingOrganization['organization_role']
                ],
                'narrative'   => $this->buildNarrative($participatingOrganization['narrative'])
            ];
        }

        return $activityData;
    }
}
