<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\FormCreator\Organization\NameForm as FormBuilder;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgNameManager;
use App\Services\RequestManager\Organization\NameRequestManager;
use Illuminate\Support\Facades\Gate;
use Session;
use URL;

class NameController extends Controller
{

    protected $formBuilder;
    protected $nameManager;
    protected $nameForm;
    protected $organizationManager;

    public function __construct(
        FormBuilder $formBuilder,
        OrgNameManager $nameManager,
        OrganizationManager $organizationManager
    ) {
        $this->middleware('auth');
        $this->nameForm            = $formBuilder;
        $this->nameManager         = $nameManager;
        $this->organizationManager = $organizationManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $orgId
     * @return Response
     */
    public function index($orgId)
    {
        $id           = $orgId;
        $organization = $this->organizationManager->findOrganizationData($orgId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $orgName = $this->nameManager->getOrganizationNameData($orgId);
        $form    = $this->nameForm->editForm($orgName, $orgId);

        return view('Organization.name.edit', compact('form', 'orgName', 'orgId', 'id'));
    }

    /**
     * update organization name
     * @param                    $orgId
     * @param NameRequestManager $nameRequestManager
     * @param Request            $request
     * @return mixed
     */
    public function update($orgId, NameRequestManager $nameRequestManager, Request $request)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $organization);

        $this->authorizeByRequestType($organization, 'name');
        $input = $request->all();

        if ($this->nameManager->update($input, $organization)) {

            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('title.org_name')]]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('title.org_name')]]];

        return redirect()->route('organization.name.index', $orgId)->withInput()->withResponse($response);
    }
}
