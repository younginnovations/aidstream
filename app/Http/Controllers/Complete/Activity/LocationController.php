<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\Location as LocationForm;
use App\Services\Activity\LocationManager;
use App\Services\RequestManager\Activity\Location as LocationRequestManager;

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
        $location     = $this->locationManager->getLocation($id);
        $form         = $this->locationForm->editForm($location, $id);

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
        $this->authorize(['edit_activity', 'add_activity']);
        $location     = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->locationManager->update($location, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Location']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Location']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
