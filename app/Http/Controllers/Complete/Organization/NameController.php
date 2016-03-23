<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgNameManager;
use Session;
use URL;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\RequestManager\Organization\NameRequestManager;
use App\Services\FormCreator\Organization\NameForm as FormBuilder;

class NameController extends Controller
{

    protected $formBuilder;
    protected $nameManager;
    protected $nameForm;

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
     * @return Response
     */
    public function index($orgId)
    {
        $orgName = $this->nameManager->getOrganizationNameData($orgId);
        $form    = $this->nameForm->editForm($orgName, $orgId);

        return view('Organization.name.edit', compact('form', 'orgName','orgId'));
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
        $organizationData = $this->nameManager->getOrganizationData($orgId);
        $this->authorizeByRequestType($organizationData, 'name');
        $input            = $request->all();

        if ($this->nameManager->update($input, $organizationData)) {

            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Organization Name']]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Organization Name']]];

        return redirect()->route('organization.name.index', $orgId)->withInput()->withResponse($response);
    }
}
