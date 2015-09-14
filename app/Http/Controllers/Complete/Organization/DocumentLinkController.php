<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
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

    public function __construct(
        FormBuilder $formBuilder,
        DocumentLinkManager $documentLinkManager
    ) {
        $this->middleware('auth');
        $this->documentLinkForm    = $formBuilder;
        $this->documentLinkManager = $documentLinkManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $documentLink = $this->documentLinkManager->getDocumentLinkData($orgId);
        $form         = $this->documentLinkForm->editForm($documentLink, $orgId);

        return view('Organization.documentLink.documentLink', compact('form', 'documentLink'));
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
            return redirect()->to(sprintf('/organization/%s', $orgId))->withMessage(
                'Organization Document Link Updated !'
            );
        }
        return redirect()->to->route('organization.document-link.index',$orgId);
    }
}
