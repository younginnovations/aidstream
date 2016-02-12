<?php namespace App\Http\Controllers\Wizard\Activity;

use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\Wizard\Activity\ActivityManager;
use App\Services\Wizard\Activity\StepTwoManager;
use App\Services\Wizard\FormCreator\Activity\StepTwo as StepTwoForm;
use App\Services\Wizard\RequestManager\Activity\StepTwo as StepTwoRequestManager;
use App\Http\Requests\Request;

/**
 * Class StepTwoController
 * @package app\Http\Controllers\Wizard\Activity
 */
class StepTwoController extends Controller
{
    /**
     * @var StepTwoForm
     */
    protected $stepTwoForm;
    /**
     * @var StepTwoManager
     */
    protected $stepTwoManager;
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @param StepTwoManager      $stepTwoManager
     * @param StepTwoForm         $stepTwoForm
     * @param OrganizationManager $organizationManager
     * @param ActivityManager     $activityManager
     */
    function __construct(StepTwoManager $stepTwoManager, StepTwoForm $stepTwoForm, OrganizationManager $organizationManager, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->stepTwoForm         = $stepTwoForm;
        $this->stepTwoManager      = $stepTwoManager;
        $this->activityManager     = $activityManager;
        $this->organization_id     = session('org_id');
        $this->organizationManager = $organizationManager;
    }

    /**
     * step two from view
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $this->authorize('edit_activity');
        $activityData      = $this->activityManager->getActivityData($id);
        $iatiIdentifier    = $activityData->identifier;
        $data['title']     = $activityData->title[0]['narrative'];
        $data['general']   = $activityData->description[0]['narrative'][0]['narrative'];
        $data['objective'] = $activityData->description[1]['narrative'][0]['narrative'];
        $data['target']    = $activityData->description[2]['narrative'][0]['narrative'];
        $form              = $this->stepTwoForm->editForm($data, $id);

        return view(
            'wizard.activity.stepTwo.create',
            compact('form', 'iatiIdentifier', 'id')
        );
    }

    /**
     * updates activity title and description
     * @param                       $id
     * @param Request               $request
     * @param StepTwoRequestManager $stepTwoRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, StepTwoRequestManager $stepTwoRequestManager)
    {
        $title            = [['narrative' => $request->title, 'language' => 'en']];
        $description      = [
            [
                'type'      => "1",
                'narrative' => [['narrative' => $request->general, 'language' => 'en']]
            ],
            [
                'type'      => "2",
                'narrative' => [['narrative' => $request->objective, 'language' => 'en']]
            ],
            [
                'type'      => "3",
                'narrative' => [['narrative' => $request->target, 'language' => 'en']]
            ]
        ];
        $titleDescription = ['title' => $title, 'description' => $description];
        $activityData     = $this->activityManager->getActivityData($id);
        if ($this->stepTwoManager->update($titleDescription, $activityData)) {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Step Two Completed!']]];
            return redirect()->to(sprintf('wizard/activity/%s/date-status', $id))->withResponse($response);
        }

        return redirect()->back();
    }
}
