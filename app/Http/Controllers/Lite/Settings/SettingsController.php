<?php namespace App\Http\Controllers\Lite\Settings;

use App\Http\Controllers\Lite\LiteController;
use App\Http\Requests\Request;
use App\Lite\Services\Settings\SettingsService;
use App\Lite\Services\Users\UserService;
use App\Lite\Services\Validation\ValidationService;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Lite\Settings
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
     * SettingsController constructor.
     *
     * @param FormBuilder       $formBuilder
     * @param SettingsService   $settingsService
     * @param UserService       $userService
     * @param ValidationService $validationService
     */
    public function __construct(FormBuilder $formBuilder, SettingsService $settingsService, UserService $userService, ValidationService $validationService)
    {
        $this->middleware('auth');
        $this->middleware('auth.admin', ['only' => ['upgradeVersion']]);
        $this->formBuilder       = $formBuilder;
        $this->settingsService   = $settingsService;
        $this->validationService = $validationService;
        $this->userService       = $userService;
    }

    /**
     * Provides Empty Settings form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $form = $this->formBuilder->create(
            'App\Lite\Forms\V202\Settings',
            [
                'method' => 'PUT',
                'model'  => [],
                'url'    => route('lite.settings.store')
            ]
        );

        return view('lite.settings.index', compact('form'));
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
            'App\Lite\Forms\V202\Settings',
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('lite.settings.store'),
                'files'  => true
            ]
        );

        $registrationAgency = getVal($model, ['registrationRegistrationAgency'], '');
        $country            = getVal($model, ['country'], '');
        $agencies           = json_encode($form->getCodeList('OrganisationRegistrationAgency', 'Organization', false));

        return view('lite.settings.index', compact('form', 'agencies', 'registrationAgency', 'country', 'users'));
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
            return redirect()->route('lite.settings.edit')->with('status', true);
        } elseif ($response) {
            return redirect()->route('lite.settings.edit')->withResponse(['type' => 'success', 'messages' => ['Settings saved successfully.']]);
        }

        return redirect()->route('lite.settings.edit')->withResponse(['type' => 'danger', 'messages' => ['Error occurred during saving.']]);
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

        if (!$this->settingsService->upgradeSystemVersion($organizationId)) {
            return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.upgrade_not_completed')]]);
        }

        session('first_login', true);

        return redirect()->route('welcome')->withResponse(['type' => 'success', 'messages' => [trans('success.aidstream_upgraded')]]);
    }
}

