<?php namespace app\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\SectorManager;
use App\Services\FormCreator\Activity\Sector as SectorForm;
use App\Services\RequestManager\Activity\Sector  as SectorRequestManager;
use Illuminate\Http\Request;

/**
 * Class SectorController
 * @package app\Http\Controllers\Complete\Activity
 */
class SectorController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var SectorForm
     */
    protected $sectorForm;
    /**
     * @var SectorManager
     */
    protected $sectorManager;

    /**
     * @param SectorManager   $sectorManager
     * @param SectorForm      $sectorForm
     * @param ActivityManager $activityManager
     */
    function __construct(SectorManager $sectorManager, SectorForm $sectorForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager = $activityManager;
        $this->sectorForm      = $sectorForm;
        $this->sectorManager   = $sectorManager;
    }

    /**
     * returns the activity sector edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $sector       = $this->sectorManager->getSectorData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->sectorForm->editForm($sector, $id);

        return view(
            'Activity.sector.edit',
            compact('form', 'activityData', 'id')
        );
    }

    /**
     * updates activity sector
     * @param                      $id
     * @param Request              $request
     * @param SectorRequestManager $sectorRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, SectorRequestManager $sectorRequestManager)
    {
        $sector       = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->sectorManager->update($sector, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage(
                'Activity Sector Updated !'
            );
        }

        return redirect()->back();
    }
}
