<?php namespace App\Http\Controllers\Np\Traits;

use App\Helpers\GetCodeName;
use App\Models\Activity\Activity;

trait ProvidesDataForNp
{
    /**
     * Generate meta data for Np homepage front end.
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
                    'sectors'                => $this->getSectors($activity, $getCode),
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
     * Activities published by the Organizations having Np as their system version.
     *
     * @return mixed
     */
    protected function getActivityDataForNp()
    {
        $oldActivities       = $this->temporarySolution();
        $publishedActivities = $this->activityPublished->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
                                                       ->where('organizations.system_version_id', '=', config('system-version.Np.id'))
                                                       ->where('activity_published.published_to_register', '=', 0)
                                                       ->get(['activity_published.*']);

        $activities = [];

        foreach ($publishedActivities as $publishedActivity) {
            $includedActivities = $publishedActivity->published_activities ? $publishedActivity->published_activities : [];
            foreach ($includedActivities as $includedActivity) {
                $activityId = (int) array_last(
                    explode('-', explode('.', $includedActivity)[0]),
                    function ($value) {
                        return true;
                    }
                );

                $activities[] = $this->activity->where('id', '=', $activityId)->with('organization')->first();
            }
        }

        $allActivities = array_filter(array_merge($oldActivities, $activities));

        foreach ($allActivities as $index => $activity) {
            if ($activity->organization->system_version_id !== config('system-version.Np.id')) {
                unset($allActivities[$index]);
            }
        }

        return $allActivities;
    }

    /**
     * @return array
     */
    protected function temporarySolution()
    {
        // TODO: remove this
        $activities    = [];
        $organizations = $this->organization->where('system_version_id', '=', 4)->get();
        foreach ($organizations as $organization) {
            foreach ($organization->activities as $activity) {
                if ($activity->activity_workflow == 4) {
                    $activities[] = $activity;
                }
            }
        }

        return $activities;
    }

    protected function getSectors(Activity $activity, $getCode)
    {
        if ($activity->sector) {
            if ($sector = $getCode->getActivityCodeName('Sector', getVal($activity->sector, [0, 'sector_category_code'], ''))) {
                return $sector;
            } else {
                return $getCode->getActivityCodeName('Sector', getVal($activity->sector, [0, 'sector_code']));
            }
        }

        return null;
    }
}
