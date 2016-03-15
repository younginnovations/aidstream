<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Organization\OrganizationManager;
use App\Http\Requests\Request;
use App\Services\Organization\DocumentLinkManager;
use Session;
use URL;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\RequestManager\Organization\DocumentLinkRequestManager;
use App\Services\FormCreator\Organization\DocumentLinkForm as FormBuilder;


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
        $input            = $request->all();
        $organizationData = $this->documentLinkManager->getOrganizationData($orgId);

        if ($this->documentLinkManager->update($input, $organizationData)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Organization Document Link']]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Organization Document Link']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
