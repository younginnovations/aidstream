<?php namespace App\Http\Controllers\Tz;

use App\Http\Controllers\Lite\LiteController;
use App\Http\Controllers\Tz\Traits\ProvidesDataForTz;
use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;

/**
 * Class TzController
 * @package App\Http\Controllers\Tz
 */
class TzController extends LiteController
{
    use ProvidesDataForTz;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var ActivityPublished
     */
    protected $activityPublished;

    /**
     *
     */
    const TZ_VERSION_ID = 3;

    /**
     * TzController constructor.
     * @param Organization      $organization
     * @param Activity          $activity
     * @param ActivityPublished $activityPublished
     */
    public function __construct(Organization $organization, Activity $activity, ActivityPublished $activityPublished)
    {
        $this->organization      = $organization;
        $this->activity          = $activity;
        $this->activityPublished = $activityPublished;
    }

    /**
     * Get the Tanzanian Version homepage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizationCount        = $this->organizationCount();
        $publishedActivitiesCount = $this->publishedActivities();

        return view('tz.home', compact('organizationCount', 'publishedActivitiesCount'));
    }

    /**
     * Get the data for the Tz homepage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities()
    {
        $jsonData = json_encode($this->generateMetaData($this->getActivityDataForTz()));

        return view('tz.jsonActivities', compact('jsonData'));
    }

    /**
     * Returns count of organisation registered for tz.
     *
     * @return mixed
     */
    protected function organizationCount()
    {
        return $this->activityPublished->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
                                       ->where('organizations.system_version_id', config('system-version.Tz.id'))
                                       ->count();
    }

    /**
     * Returns count of published activities of organisation registered for tz.
     *
     * @return mixed
     */
    protected function publishedActivities()
    {
        $activitiesCount = 0;
        $this->activityPublished->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
                                ->where('organizations.system_version_id', config('system-version.Tz.id'))
                                ->select('activity_published.published_activities')
                                ->get()
                                ->each(
                                    function ($publishedActivity) use (&$activitiesCount) {
                                        $activitiesCount += count($publishedActivity->published_activities);
                                    }
                                );

        return $activitiesCount;
    }
}

