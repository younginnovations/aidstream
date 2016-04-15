<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DescriptionManager;
use App\Services\FormCreator\Activity\Description as DescriptionForm;
use App\Services\RequestManager\Activity\Description as DescriptionRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class DescriptionController
 * allow user to add and update Activity Description
 * @package app\Http\Controllers\Complete\Activity
 */
class DescriptionController extends Controller
{
    /**
     * @var DescriptionForm
     */
    protected $description;
    /**
     * @var DescriptionManager
     */
    protected $descriptionManager;
    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @param DescriptionForm    $description
     * @param DescriptionManager $descriptionManager
     * @param ActivityManager    $activityManager
     */
    function __construct(DescriptionForm $description, DescriptionManager $descriptionManager, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->description        = $description;
        $this->descriptionManager = $descriptionManager;
        $this->activityManager    = $activityManager;
    }

    /**
     * returns the activity description edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityDescription = $this->descriptionManager->getDescriptionData($id);
        $form                = $this->description->editForm($activityDescription, $id);

        return view(
            'Activity.description.edit',
            compact('form', 'activityData', 'id')
        );
    }

    /**
     * updates activity description
     * @param                           $id
     * @param Request                   $request
     * @param DescriptionRequestManager $descriptionRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, DescriptionRequestManager $descriptionRequestManager)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'description');
        $activityDescription = $request->all();
        if (!$this->validateDescription($request->get('description'))) {
            $response = ['type' => 'warning', 'code' => ['activity_description', ['name' => 'Description']]];

            return redirect()->back()->withInput()->withResponse($response);
        }
        if ($this->descriptionManager->update($activityDescription, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);

            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity Description']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Activity Description']]];

        return redirect()->back()->withInput()->withResponse($response);
    }

    /**
     * validate description data based on description type
     * @param $descriptions
     * @return bool
     */
    private function validateDescription($descriptions)
    {
        $descriptionTypeList = [];
        foreach ($descriptions as $description) {
            $descriptionTypeList[] = $description['type'];
        }

        return count($descriptionTypeList) === count(array_unique($descriptionTypeList));
    }
}
