<?php namespace App\Http\Controllers\Np;

use App\Http\Controllers\Lite\LiteController;
use App\Http\Controllers\Np\Traits\ProvidesDataForNp;
use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;

/**
 * Class TzController
 * @package App\Http\Controllers\Tz
 */
class NpController extends LiteController
{
    use ProvidesDataForNp;

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
    const NP_VERSION_ID = 4;

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

        return view('np.home', compact('organizationCount', 'publishedActivitiesCount'));
    }

    public function about()
    {
        return view('np.about');
    }

    public function municipality($municipalityId)
    {
        if($municipalityId != 'dhangadi'){

            return redirect()->back();
        }

        $organizationCount  = $this->organizationCount();

        $activityData = $this->getActivityDataForNp();
        $sectors = [];
        foreach($activityData as $activity){
            $sectorCode = getVal($activity->sector, [0 , 'sector_code']);
                $sectors[] = $sectorCode;
        }
        $sectors = array_count_values($sectors);

        $sectorsArray = [];
        foreach($sectors as $key => $sector){
            $sectorsArray[] = [
                "sector_count"  => $sector,
                "sector_area"   => app('App\Helpers\GetCodeName')->getCodeName('Activity', 'Sector', $key, null)
            ];
        }

        $sectorCount = count($sectors);

        $sectors = json_encode($sectorsArray);

        return view('np.municipality', compact('organizationCount', 'sectorCount', 'sectors'));
    }

    /**
     * Get the data for the Tz homepage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities()
    {
        $jsonData = json_encode($this->generateMetaData($this->getActivityDataForNp()));

        return view('np.jsonActivities', compact('jsonData'));
    }

    /**
     * Returns count of organisation registered for tz.
     *
     * @return mixed
     */
    protected function organizationCount()
    {
        return $this->activityPublished->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
                                       ->where('organizations.system_version_id', config('system-version.Np.id'))
                                       ->count();
    }

    /**
     * Returns count of published activities of organisation registered for tz.
     *
     * @return mixed
     */
    protected function publishedActivities()
    {
        return count($this->getActivityDataForNp());

        //        $activitiesCount = 0;
//        $this->activityPublished->join('organizations', 'organizations.id', '=', 'activity_published.organization_id')
//                                ->where('organizations.system_version_id', config('system-version.Tz.id'))
//                                ->select('activity_published.published_activities')
//                                ->get()
//                                ->each(
//                                    function ($publishedActivity) use (&$activitiesCount) {
//                                        $activitiesCount += count($publishedActivity->published_activities);
//                                    }
//                                );
//
//        return $activitiesCount;
    }
}

