<?php namespace App\Http\Controllers\Tz\Traits;

use App\Helpers\GetCodeName;

trait ProvidesDataForTz
{
//    /**
//     * Activities published by the Organizations having Tz as their system version.
//     *
//     * @return mixed
//     */
//    protected function getPublishedActivitiesForTanzania()
//    {
//        return $this->activity->join('organizations', 'organizations.id', '=', 'activity_data.organization_id')
//                              ->where('organizations.system_version_id', '=', 3)
//                              ->where('activity_data.published_to_registry', '=', 1)
//                              ->get(['activity_data.*']);
//    }

    /**
     * Generate meta data for Tz homepage front end.
     *
     * @param $activities
     * @return array
     */
    protected function generateMetaData($activities)
    {
        $regionName = [];
        $startDate  = "";
        $endDate    = "";
        $jsonData   = [];
        $getCode    = new GetCodeName();

        foreach ($activities as $activity) {
            if ($activity) {
                $activityData = $activity->toArray();

                foreach (getVal($activityData, ['activity_date'], []) as $activityDate) {
                    if ($activityDate['type'] == 2) {
                        $startDate = $activityDate['date'];
                    } elseif ($activityDate['type'] == 4) {
                        $endDate = $activityDate['date'];
                    }
                }

                $i = 0;

                if ($activity->location) {
                    foreach (getVal($activityData, ['location'], []) as $location) {
                        foreach ($location['administrative'] as $index => $administrative) {
                            if ($index == 0) {
                                $regionName[$i] = $administrative['code'];
                                $i ++;
                            }
                        }
                    }
                }

                $organization = $activity->organization;

                $jsonData[] = [
                    'id'                     => $activity->id,
                    'identifier'             => $activity->identifier['activity_identifier'],
                    'title'                  => $activity->title[0]['narrative'],
                    'sectors'                => $activity->sector ? [$getCode->getActivityCodeName('Sector', $activity->sector[0]['sector_category_code'])] : null,
//                    'sectors'                => $activity->sector ? [$getCode->getActivityCodeName('Sector', $activity->sector[0]['sector_code'])] : null,
                    'regions'                => $regionName,
                    'startdate'              => $startDate,
                    'enddate'                => $endDate,
                    'reporting_organisation' => $organization->name,
                    'activity_url'           => ($organization->organizationSnapshot) ? url('/who-is-using/' . $organization->organizationSnapshot->org_slug . '/' . $activity->id) : '#',
                    'organization_url'       => ($organization->organizationSnapshot) ? url('/who-is-using/' . $organization->organizationSnapshot->org_slug) : '#'
                ];
            }
        }

        return $jsonData;
    }

    /**
     * Activities published by the Organizations having Tz as their system version.
     *
     * @return mixed
     */
    protected function getActivityDataForTz()
    {
        return $this->temporarySolution();

//        $publishedActivities = $this->activityPublished->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
//                                                       ->where('activity_published.published_to_register', '=', 0)
//                                                       ->where('organizations.system_version_id', '=', self::TZ_VERSION_ID)
//                                                       ->get(['activity_published.*']);

//        foreach ($publishedActivities as $publishedActivity) {
//            $includedActivities = $publishedActivity->published_activities ? $publishedActivity->published_activities : [];
//            foreach ($includedActivities as $includedActivity) {
//                $activityId = (int) array_last(
//                    explode('-', explode('.', $includedActivity)[0]),
//                    function ($value) {
//                        return true;
//                    }
//                );
//
//                $activities[] = $this->activity->where('id', '=', $activityId)->with('organization')->first();
//            }
//        }
//
//        return $activities;
    }

    /**
     * @return array
     */
    protected function temporarySolution()
    {
        // TODO: remove this
        $activities    = [];
        $organizations = $this->organization->where('system_version_id', '=', 3)->get();
        foreach ($organizations as $organization) {
            foreach ($organization->activities as $activity) {
                if ($activity->activity_workflow == 3) {
//                    $activityIds[] = $activity->id;

//                    $activities[] = $this->activity->where('id', '=', $activityId)->with('organization')->first();
                    $activities[] = $activity;
                }
            }
        }

        return $activities;
    }
}
