<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\FormCreator\Organization\DocumentLinkForm as FormBuilder;
use App\Services\Organization\DocumentLinkManager;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Organization\DocumentLinkRequestManager;
use Illuminate\Support\Facades\Gate;
use Session;
use URL;


class DocumentLinkController extends Controller
{

    protected $formBuilder;
    protected $documentLinkManager;
    protected $documentLinkForm;
    protected $organizationManager;

    public function __construct(
        FormBuilder $formBuilder,
        DocumentLinkManager $documentLinkManager,
        OrganizationManager $organizationManager
    ) {
        $this->middleware('auth');
        $this->documentLinkForm    = $formBuilder;
        $this->documentLinkManager = $documentLinkManager;
        $this->organizationManager = $organizationManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $documentLink = $this->documentLinkManager->getDocumentLinkData($orgId);
        $form         = $this->documentLinkForm->editForm($documentLink, $orgId);

        return view('Organization.documentLink.documentLink', compact('form', 'documentLink', 'orgId'));
    }


    /**
     * @param                            $orgId
     * @param DocumentLinkRequestManager $documentLinkRequestManager
     * @param Request                    $request
     * @return mixed
     */
    public function update($orgId, DocumentLinkRequestManager $documentLinkRequestManager, Request $request)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);
        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($organization, 'document_link');
        $input = $request->all();

        if ($this->documentLinkManager->update($input, $organization)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('title.org_document_link')]]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('title.org_document_link')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
