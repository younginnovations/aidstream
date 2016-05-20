<?php namespace App\Http\Controllers\Tz\Settings;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Tz\TanzanianController;
use App\Http\Requests\Request;
use App\Tz\Aidstream\Services\Setting\SettingService;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Tz\Settings
 */
class SettingsController extends TanzanianController
{
    /**
     * @var SettingService
     */
    protected $settings;

    /**
     * SettingsController constructor.
     * @param SettingService $settings
     */
    public function __construct(SettingService $settings)
    {
        $this->settings = $settings;
    }

    /**
     * View settings form page
     * @return mixed
     */
    public function index()
    {
        $baseForm = new BaseForm();
        $orgType  = $baseForm->getCodeList('OrganizationType', 'Organization');
        $language = $baseForm->getCodeList('Language', 'Organization');
        $currency = $baseForm->getCodeList('Currency', 'Organization');
        $settings = $this->settings->findByOrgId(session('org_id'));

        if (is_null($settings)) {
            return view('tz.settings.create', compact('settings', 'orgType', 'language', 'currency'));
        } else {
            return view('tz.settings.edit', compact('settings', 'orgType', 'language', 'currency'));
        }
    }

    /**
     *
     */
    public function create()
    {

    }

    /**
     * Save settings
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if (!$this->settings->create($request->all())) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Settings could not be saved.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Settings successfully saved.']]];
        }

        return redirect()->route('settings.index')->withResponse($response);

    }

    /**
     * @param $id
     */
    public function show($id)
    {

    }

    /**
     * @param $id
     */
    public function edit($id)
    {

    }

    /**
     * Update Settings
     * @param Request $request
     * @param         $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        if (!$this->settings->update($request->all(), $id)) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Settings could not be updated.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Settings successfully updated.']]];
        }

        return redirect()->route('settings.index')->withResponse($response);
    }

    /**
     *
     */
    public function destroy()
    {

    }
}
