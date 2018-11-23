<?php namespace App\Http\Controllers\Np\Profile;

use App\Http\Controllers\Lite\LiteController;
use App\Http\Requests\Request;
use App\Np\Services\Profile\ProfileService;
use App\Np\Services\Validation\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Np\Profile
 */
class ProfileController extends LiteController
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var ValidationService
     */
    protected $validationService;

    /**
     * @var ProfileService
     */
    protected $profileService;

    /**
     * ProfileController constructor.
     * @param FormBuilder       $formBuilder
     * @param ProfileService    $profileService
     * @param ValidationService $validationService
     */
    public function __construct(FormBuilder $formBuilder, ProfileService $profileService, ValidationService $validationService)
    {
        $this->middleware('auth');
        $this->profileService    = $profileService;
        $this->formBuilder       = $formBuilder;
        $this->validationService = $validationService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organisation = auth()->user()->organization;

        if (Gate::denies('belongsToOrganization', $organisation)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        return view('np.profile.index', compact('organisation'));
    }

    /**
     * Provides Profile form with models
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile()
    {
        $user         = auth()->user();
        $version      = session('version');
        $organisation = auth()->user()->organization;

        if (Gate::denies('belongsToOrganization', $user->organization)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $model = $this->profileService->getFormModel($user->toArray(), $organisation->toArray(), $version);
        $form  = $this->formBuilder->create(
            'App\Np\Forms\V202\Profile',
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('np.user.profile.store'),
                'files'  => true
            ]
        );

        return view('np.profile.editProfile', compact('form'));
    }

    /**
     * Stores the Profile value
     *
     * @param Request $request
     * @return ProfileController|\Illuminate\Http\RedirectResponse
     */
    public function storeProfile(Request $request)
    {
        $user    = auth()->user();
        $orgId   = session('org_id');
        $rawData = $request->all();
        $version = session('version');

        if (Gate::denies('belongsToOrganization', $user->organization)) {
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->validationService->passes($rawData, 'Profile', $version)) {
            return redirect()->back()->with('errors', $this->validationService->errors())->withInput($rawData);
        }

        if ($this->profileService->store($orgId, $user->id, $rawData, $version)) {
            return redirect()->route('np.user.profile.index')->withResponse(['type' => 'success', 'messages' => [trans('lite/profile.profile_saved_successfully')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('lite/profile.failed_to_save_profile')]]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPassword()
    {
        if (Gate::denies('belongsToOrganization', auth()->user()->organization)) {
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $form = $this->formBuilder->create(
            'App\Np\Forms\V202\Password',
            [
                'method' => 'PUT',
                'url'    => route('np.user.password.store')
            ]
        );

        return view('np.profile.editPassword', compact('form'));
    }

    /**
     * Stores changed password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storePassword(Request $request)
    {
        $user    = auth()->user();
        $rawData = $request->all();
        $version = session('version');

        if (Gate::denies('belongsToOrganization', $user->organization)) {
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->validationService->passes($rawData, 'Password', $version)) {
            return redirect()->back()->with('errors', $this->validationService->errors())->withInput($rawData);
        }

        if ($this->profileService->storePassword($user, $rawData)) {
            return redirect()->route('np.user.profile.index')->withResponse(['type' => 'success', 'messages' => [trans('lite/profile.profile_saved_successfully')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('lite/profile.new_password_mismatched')]]);
    }
}
