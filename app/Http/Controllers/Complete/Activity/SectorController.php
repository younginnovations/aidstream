<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\SectorManager;
use App\Services\FormCreator\Activity\Sector as SectorForm;
use App\Services\RequestManager\Activity\Sector as SectorRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

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
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $sector       = $this->sectorManager->getSectorData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->sectorForm->editForm($sector, $id);

        return view('Activity.sector.edit', compact('form', 'activityData', 'id'));
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
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData = $this->activityManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'sector');
        $sectors = $request->all();
        foreach ($sectors['sector'] as &$sector) {
            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                $sector['sector_vocabulary'] = 1;
                $sector['sector_category_code'] = '';
                $sector['sector_text']          = '';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $sector['sector_code'] = '';
                $sector['sector_text'] = '';
            } else {
                $sector['sector_code']          = '';
                $sector['sector_category_code'] = '';
            }
        }
        if ($this->sectorManager->update($sectors, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Sector']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Sector']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
