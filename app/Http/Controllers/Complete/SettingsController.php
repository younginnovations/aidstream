<?php namespace App\Http\Controllers\Complete;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\OtherIdentifierManager;
use App\Services\RequestManager\Organization\SettingsRequestManager;
use App;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;

class SettingsController extends Controller
{

    protected $settingsManager;
    protected $settings;
    protected $organization;
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var OtherIdentifierManager
     */
    protected $otherIdentifierManager;

    /**
     * @param SettingsManager        $settingsManager
     * @param OrganizationManager    $organizationManager
     * @param ActivityManager        $activityManager
     * @param OtherIdentifierManager $otherIdentifierManager
     */
    function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        ActivityManager $activityManager,
        OtherIdentifierManager $otherIdentifierManager
    ) {
        $this->middleware('auth');
        $this->settingsManager        = $settingsManager;
        $org_id                       = Session::get('org_id');
        $this->settings               = $settingsManager->getSettings($org_id);
        $this->organization           = $organizationManager->getOrganization($org_id);
        $this->activityManager        = $activityManager;
        $this->otherIdentifierManager = $otherIdentifierManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param FormBuilder $formBuilder
     * @return Response
     */
    public function index(FormBuilder $formBuilder)
    {
        $model = [];
        if (isset($this->settings)) {
            $model['version_form']         = [['version' => $this->settings->version]];
            $model['publishing_type']      = [['publishing' => $this->settings->publishing_type]];
            $model['registry_info']        = $this->settings->registry_info;
            $model['default_field_values'] = $this->settings->default_field_values;
            $model['default_field_groups'] = $this->settings->default_field_groups;
        } else {
            $data  = '{"version_form":[{"version":"2.01"}],"reporting_organization_info":[{"reporting_organization_identifier":"","reporting_organization_type":"10","organization_name":"","reporting_organization_language":"es"}],"publishing_type":[{"publishing":"unsegmented"}],"registry_info":[{"publisher_id":"","api_id":"","publish_files: ":"no"}],"default_field_values":[{"default_currency":"AED","default_language":"es","default_hierarchy":"","default_collaboration_type":"1","default_flow_type":"10","default_finance_type":"310","default_aid_type":"A01","Default_tied_status":"3"}],"default_field_groups":[{"title":"Title","description":"Description","activity_status":"Activity Status","activity_date":"Activity Date","participating_org":"Participating Org","recipient_county":"Recipient Country","location":"Location","sector":"Sector","budget":"Budget","transaction":"Transaction","document_ink":"Document Link"}]}';
            $model = json_decode($data, true);
        }
        if (isset($this->organization)) {
            $model['reporting_organization_info'] = $this->organization->reporting_org;
        };
        $url         = 'settings.' . (isset($this->settings) ? 'update' : 'store');
        $method      = isset($this->settings) ? 'PUT' : 'POST';
        $formOptions = [
            'method' => $method,
            'url'    => route($url)
        ];
        if (!empty($model)) {
            $formOptions['model'] = $model;
        }
        $form = $formBuilder->create('App\Core\V201\Forms\SettingsForm', $formOptions);

        return view('settings', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SettingsRequestManager $request
     * @return Response
     */
    public function store(SettingsRequestManager $request)
    {
        $input = Input::all();
        $this->settingsManager->storeSettings($input, $this->organization);
        Session::flash('message', 'Successfully Updated');

        return Redirect::to('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                   $id
     * @param SettingsRequestManager $request
     * @return Response
     */
    public function update($id, SettingsRequestManager $request)
    {
        $input         = Input::all();
        $oldIdentifier = $this->organization->reporting_org[0]['reporting_organization_identifier'];
        $this->settingsManager->updateSettings($input, $this->organization, $this->settings);
        $activities             = $this->activityManager->getActivities($this->organization->id);
        $reportingOrgIdentifier = $input['reporting_organization_info'][0]['reporting_organization_identifier'];
        foreach ($activities as $activity) {
            $status          = $activity['published_to_registry'];
            $otherIdentifier = (array) $activity->other_identifier;
            if ($status == 1 && !in_array(["reference" => $oldIdentifier, "type" => "B1", 'owner_org' => []], $otherIdentifier) && ($oldIdentifier !== $reportingOrgIdentifier)) {
                $otherIdentifier[] = ['reference' => $oldIdentifier, 'type' => 'B1', 'owner_org' => []];
                $this->otherIdentifierManager->update(['other_identifier' => $otherIdentifier], $activity);
            }
        }

        return Redirect::to('/')->withMessage('Successfully Updated');
    }
}
