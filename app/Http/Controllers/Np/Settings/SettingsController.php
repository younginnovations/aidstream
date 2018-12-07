<?php namespace App\Http\Controllers\Np\Settings;

use App\Http\Controllers\Lite\LiteController;
use App\Http\Requests\Request;
use App\Np\Services\Settings\SettingsService;
use App\Np\Services\Users\UserService;
use App\Np\Services\Validation\ValidationService;
use Illuminate\Contracts\Auth\Guard;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Np\Settings
 */
class SettingsController extends LiteController
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var ValidationService
     */
    protected $validationService;
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Guard
     */
    protected $auth;

    const NP_VERSION_ID = 4;

    /**
     * SettingsController constructor.
     *
     * @param FormBuilder       $formBuilder
     * @param SettingsService   $settingsService
     * @param UserService       $userService
     * @param ValidationService $validationService
     */
    public function __construct(FormBuilder $formBuilder, SettingsService $settingsService, UserService $userService, ValidationService $validationService, Guard $auth)
    {
        $this->middleware('auth');
        $this->middleware('auth.admin', ['only' => ['upgradeVersion']]);
        $this->formBuilder       = $formBuilder;
        $this->settingsService   = $settingsService;
        $this->validationService = $validationService;
        $this->userService       = $userService;
        $this->auth              = $auth;
    }

    /**
     * Provides Empty Settings form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $form = $this->formBuilder->create(
            'App\Np\Forms\V202\Settings',
            [
                'method' => 'PUT',
                'model'  => [],
                'url'    => route('np.settings.store')
            ]
        );

        return view('np.settings.index', compact('form'));
    }

    /**
     * Provides Settings form with models
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $orgId   = session('org_id');
        $version = session('version');

        $model = $this->settingsService->getSettingsModel($orgId, $version);
        $users = $this->userService->all($orgId);

        $form = $this->formBuilder->create(
            'App\Np\Forms\V202\Settings',
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('np.settings.store'),
                'files'  => true
            ]
        );

        $registrationAgency = getVal($model, ['registrationRegistrationAgency'], '');
        $country            = getVal($model, ['country'], '');
        $agencies           = json_encode($form->getCodeList('NpOrganisationRegistrationAgency', 'Organization', false));

        return view('np.settings.index', compact('form', 'agencies', 'registrationAgency', 'country', 'users'));
    }

    /**
     * Stores the settings value
     *
     * @param Request $request
     * @return SettingsController|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('settings', auth()->user());

        $rawData = $request->all();
        $orgId   = session('org_id');
        $version = session('version');

        if (!$this->validationService->passes($rawData, 'Settings', $version)) {
            return redirect()->back()->with('errors', $this->validationService->errors())->withInput($rawData);
        }

        if (($response = $this->settingsService->store($orgId, $rawData, $version)) === config('users.usernameUpdatedCode')) {
            return redirect()->route('np.settings.edit')->with('status', true);
        } elseif ($response) {
            return redirect()->route('np.settings.edit')->withResponse(['type' => 'success', 'messages' => ['Settings saved successfully.']]);
        }

        return redirect()->route('np.settings.edit')->withResponse(['type' => 'danger', 'messages' => ['Error occurred during saving.']]);
    }

    /**
     * Upgrade AidStream to Core.
     *
     * @param Request $request
     * @return mixed
     */
    public function upgradeVersion(Request $request)
    {
        $organizationId = session()->get('org_id');
        $this->authorize('settings', auth()->user());
        $organization    = $this->settingsService->getOrganization($organizationId);
        $systemVersionId = $organization->system_version_id;

        if (!$this->settingsService->upgradeSystemVersion($organizationId)) {
            return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.upgrade_not_completed')]]);
        }

        session('first_login', true);

        if ($this->hasBeenUpgradedFromNp($systemVersionId)) {
            $this->auth->logOut();
            return redirect()->route('main.home');
        }

        return redirect()->route('welcome')->withResponse(['type' => 'success', 'messages' => [trans('success.aidstream_upgraded')]]);
    }

    /**
     * Check if the upgrade has been triggered from TZ version.
     *
     * @param $systemVersionId
     * @return bool
     */
    protected function hasBeenUpgradedFromNp($systemVersionId)
    {
        return ($systemVersionId == self::NP_VERSION_ID);
    }
}

