<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Location as LocationForm;
use App\Services\Activity\LocationManager;
use App\Services\RequestManager\Activity\Location as LocationRequestManager;
use Illuminate\Support\Facades\Gate;

/**
 * Class LocationController
 * @package app\Http\Controllers\Complete\Activity
 */
class LocationController extends Controller
{
    /**
     * @var LocationForm
     */
    protected $locationForm;
    /**
     * @var LocationManager
     */
    protected $locationManager;
    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @param LocationForm    $locationForm
     * @param LocationManager $locationManager
     * @param ActivityManager $activityManager
     */
    public function __construct(
        LocationForm $locationForm,
        LocationManager $locationManager,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->locationForm    = $locationForm;
        $this->locationManager = $locationManager;
        $this->activityManager = $activityManager;
    }

    /**
     * returns location edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $location = $this->locationManager->getLocation($id);

        $form     = $this->locationForm->editForm($location, $id);
        // dd($form);

        return view('Activity.location.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates location
     * @param                                $id
     * @param Request                        $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, LocationRequestManager $locationRequestManager)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'location');
        $location = $request->all();
        if ($this->locationManager->update($location, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.location')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.location')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
