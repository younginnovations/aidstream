<?php namespace app\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DescriptionManager;
use App\Services\FormCreator\Activity\Description as DescriptionForm;
use App\Services\RequestManager\Activity\Description as DescriptionRequestManager;
use Illuminate\Http\Request;

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
    function __construct(
        DescriptionForm $description,
        DescriptionManager $descriptionManager,
        ActivityManager $activityManager
    ) {
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
        $activityDescription = $this->descriptionManager->getDescriptionData($id);
        $activityData        = $this->activityManager->getActivityData($id);
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
        $activityDescription = $request->all();
        $activityData        = $this->activityManager->getActivityData($id);
        if ($this->descriptionManager->update($activityDescription, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage(
                'Activity Description Updated !'
            );
        }

        return redirect()->back();
    }
}
