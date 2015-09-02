<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use App\Services\SettingsManager;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class SettingsController extends Controller {

	protected $organization;
	protected $orgReportingOrgInfoForm;
	protected $orgPublishingTypeForm;
	protected $orgRegistryInfoForm;
	protected $orgDefaultFieldValuesForm;
	protected $orgDefaultFieldGroupsForm;

	function __construct(
		SettingsManager $settingsManager
	) {
		$this->middleware('auth');
		$this->settingsManager = $settingsManager;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Core\V201\Forms\SettingsForm', [
			'method' => 'POST',
			'url' => route('settings.store')
		]);
		return view('settings', compact('form'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$input = Input::all();
		$settings = $this->settingsManager->getSettings($id);
		$this->settingsManager->updateSettings($input, $settings);
		Session::flash('message', 'Successfully Edit');
		return Redirect::to('settings');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
