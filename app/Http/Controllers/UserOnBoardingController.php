<?php

namespace App\Http\Controllers;

use App\Models\Organization\Organization;
use App\Services\UserOnBoarding\UserOnBoardingService;
use Aws\Common\Facade\Ses;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class UserOnBoardingController extends Controller
{
    /**
     * @var UserOnBoardingService
     */
    protected $userOnBoardingService;

    /**
     * UserOnBoardingController constructor.
     * @param UserOnBoardingService $userOnBoardingService
     */
    public function __construct(UserOnBoardingService $userOnBoardingService)
    {
        $this->userOnBoardingService = $userOnBoardingService;
    }

    /**
     * start welcome page of user onboarding.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function welcome()
    {
        $firstname = Auth::user()->first_name;

        if (Auth::user()->userOnBoarding->has_completed_steps) {
            return redirect()->to('/activity');
        }

        return view('onBoarding.welcome', compact('firstname'));
    }

    /**
     * start dashboard tour.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function startDashboardTour()
    {
        return view('onBoarding.dashboardTour');
    }

    /**
     * store publisher and api id of settings.
     */
    public function storePublisherAndApiId()
    {
        $organization      = $this->getOrganization();
        $publisherId       = Input::get('publisherId');
        $apiId             = Input::get('apiId');
        $publisherIdStatus = Input::get('publisherIdStatus');
        $apiIdStatus       = Input::get('apiIdStatus');

        $this->userOnBoardingService->storePublisherAndApiKey($organization, $publisherId, $apiId, $publisherIdStatus, $apiIdStatus);
    }

    /**
     * store publishing type settings.
     */
    public function storePublishingType()
    {
        $organization   = $this->getOrganization();
        $publishingType = Input::get('publishing');

        $this->userOnBoardingService->storePublishingType($organization, $publishingType);
    }

    /**
     *store automatic publish to registry to settings.
     */
    public function storePublishFiles()
    {
        $organization = $this->getOrganization();
        $publishFiles = Input::get('publish_files');

        $this->userOnBoardingService->storePublishFiles($organization, $publishFiles);
    }

    /**
     * store activity elements checklist of settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeActivityElementsChecklist(Request $request)
    {
        $organization         = $this->getOrganization();
        $default_field_groups = $request->get('default_field_groups');

        $this->userOnBoardingService->storeActivityElementsChecklist($default_field_groups, $organization);

        return redirect()->to('/default-values#5');
    }

    /**
     * store default values of settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDefaultValues(Request $request)
    {
        $organization = $this->getOrganization();
        $this->userOnBoardingService->storeDefaultValues($request, $organization);
        $status = $this->userOnBoardingService->isAllStepsCompleted();

        return redirect()->to('continueExploring')->with('completed', $status);
    }

    /**
     * return explore later view.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function exploreLater()
    {
        if (Auth::user()->userOnBoarding->completed_tour) {
            return redirect()->back();
        }
        Session::put('first_login', true);

        return view('onBoarding.exploreLater');
    }

    /**
     * returns continue exploring view.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function continueExploring()
    {
        $firstname = Auth::user()->first_name;
        $steps     = $this->userOnBoardingService->isAllStepsCompleted();
        (!$steps) ?: Session::put('steps', $steps);

        return view('onBoarding.continueExploring', compact('firstname'));
    }

    /**
     *  complete user on boarding process
     */
    public function completeOnBoarding()
    {
        $this->userOnBoardingService->completeTour();
    }

    /**
     * returns organization currently in session.
     * @return mixed
     */
    public function getOrganization()
    {
        $org_id       = session('org_id');
        $organization = Organization::findorfail($org_id);

        return $organization;
    }
}
