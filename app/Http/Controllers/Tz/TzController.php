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
//        $activities = $this->getPublishedActivitiesForTanzania();

        return view('tz.home');
    }

    /**
     * Get the data for the Tz homepage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities()
    {
        $jsonData   = json_encode($this->generateMetaData($this->getActivityDataForTz()));

        return view('tz.jsonActivities', compact('jsonData'));
    }
}
