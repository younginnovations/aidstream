<?php namespace App\Http\Controllers\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Services\FormCreator\Organization\OrgNameForm;
use App\Http\Requests\CreateOrganizationRequest;
use Illuminate\Support\Facades\Input;
use App\Helpers\JsonHelper;
use App\Services\Organization\OrganizationManager;
use App\Generator\XmlGenerator;
use App\Core\Repositories\OrganizationRepositoryInterface;


class OrganizationController extends Controller
{
    protected $arrayToXml;
    protected $org;
    protected $orgNameForm;

    function __construct(
        OrgNameForm $orgNameForm,
        XmlGenerator $xmlGenerator,
        OrganizationManager $organizationManager
    ) {
        $this->middleware('auth');
        $this->xmlGenerator = $xmlGenerator;
        $this->organizationManager = $organizationManager;
        $this->orgNameForm = $orgNameForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $organizations = $this->organizationManager->getOrganizations();
        return view('organization.list', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->orgNameForm->create();
        return view('organization.create', compact('form'));
    }

    /**
     * stores data in database
     * @param CreateOrganizationRequest $request
     * @return mixed
     */
    public function store(CreateOrganizationRequest $request)
    {
        $input = Input::all();
        $this->organizationManager->createOrganization($input);
        Session::flash('message', 'Successfully created !');
        return Redirect::to('organization');
    }

    /**
     * display specific organization data
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $organization = $this->organizationManager->getOrganization($id);
        $names = JsonHelper::JsonDecode($organization->name);
        $reportingOrgs = JsonHelper::JsonDecode($organization->reporting_org);
        $totalBudgets = JsonHelper::JsonDecode($organization->total_budget);
        $recipientOrgBudgets = JsonHelper::JsonDecode($organization->recipient_org_budget);
        $recipientCountryBudgets = JsonHelper::JsonDecode($organization->recipient_country_budget); 
        return view('organization.show', compact('organization', 'names', 'reportingOrgs', 'totalBudgets', 'recipientOrgBudgets', 'recipientCountryBudgets'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $organization = $this->organizationManager->getOrganization($id);
        $data['identifier'] = $organization->identifier;
        $data['name'] = $organization->buildOrganizationName();
        $form = $this->orgNameForm->editForm($data, $organization);
        return view('organization.edit', compact('form', 'organization'));
    }

    /**
     * @param $id
     * @param CreateOrganizationRequest $request
     * @return mixed
     */
    public function update($id, CreateOrganizationRequest $request)
    {
        $input = Input::all();
        $organization = $this->organizationManager->getOrganization($id);
        $this->organizationManager->updateOrganization($input, $organization);
        Session::flash('message', 'Successfully Edit');
        return Redirect::to('organization');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $organization = $this->organizationManager->getOrganization($id);
        $this->deleteOrganization($organization);
        Session::flash('message', 'Successfully deleted !');
        return Redirect::to('organization');
    }

    /**
     * @param $organization
     */
    public function deleteOrganization($organization)
    {
        $organization->delete();
    }

    /**
     * @return string
     */
    public function generateXml()
    {
        $this->xmlGenerator->generateFile();
        return "";
    }

    /**
     *
     */
    public function generateOrganizationXml()
    {
        $this->xmlGenerator->getXml();
    }
}
