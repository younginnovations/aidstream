<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DocumentLinkManager;
use App\Services\DocumentManager;
use App\Services\FormCreator\Activity\DocumentLink as DocumentLinkForm;
use App\Services\RequestManager\Activity\DocumentLink as DocumentLinkRequestManager;
use App\Http\Requests\Request;
use Illuminate\Database\DatabaseManager;

/**
 * Class DocumentLinkController
 * @package App\Http\Controllers\Complete\Activity
 */
class DocumentLinkController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var DocumentLinkForm
     */
    protected $documentLinkForm;
    /**
     * @var DocumentLinkManager
     */
    protected $documentLinkManager;

    /**
     * @param DocumentLinkManager $documentLinkManager
     * @param DocumentLinkForm    $documentLinkForm
     * @param ActivityManager     $activityManager
     */
    function __construct(DocumentLinkManager $documentLinkManager, DocumentLinkForm $documentLinkForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager     = $activityManager;
        $this->documentLinkForm    = $documentLinkForm;
        $this->documentLinkManager = $documentLinkManager;
    }

    /**
     * returns the activity document link edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $documentLink = $this->documentLinkManager->getDocumentLinkData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->documentLinkForm->editForm($documentLink, $id);

        return view('Activity.documentLink.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity document link
     * @param                            $id
     * @param Request                    $request
     * @param DocumentLinkRequestManager $documentLinkRequestManager
     * @param DocumentManager            $documentManager
     * @param DatabaseManager            $database
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, DocumentLinkRequestManager $documentLinkRequestManager, DocumentManager $documentManager, DatabaseManager $database)
    {
        $this->authorize(['edit_activity', 'add_activity']);
        $documentLinks = $request->all();
        $activityData  = $this->activityManager->getActivityData($id);

        $dbDocumentLinks = (array) $this->documentLinkManager->getDocumentLinkData($id);

        foreach ($dbDocumentLinks as $documentLink) {
            $url        = $documentLink['url'];
            $document   = $documentManager->getDocument(session('org_id'), $url);
            $activities = (array) $document->activities;
            unset($activities[$id]);
            $document->activities = $activities;
            $documentManager->store($document);
        }

        if ($this->documentLinkManager->update($documentLinks, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Document Link']]];

            foreach ($documentLinks['document_link'] as $documentLink) {
                $url                  = $documentLink['url'];
                $document             = $documentManager->getDocument(session('org_id'), $url);
                $activities           = (array) $document->activities;
                $identifier           = $activityData->identifier['activity_identifier'];
                $activities[$id]      = $identifier;
                $document->activities = $activities;
                $documentManager->store($document);
            }

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Document Link']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
