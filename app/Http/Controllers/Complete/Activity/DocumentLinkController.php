<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DocumentLinkManager;
use App\Services\DocumentManager;
use App\Services\FormCreator\Activity\DocumentLink as DocumentLinkForm;
use App\Services\RequestManager\Activity\DocumentLink as DocumentLinkRequestManager;
use App\Http\Requests\Request;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Gate;

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
     * Return document link list
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $documentLinks = $activityData->documentLinks()->orderBy('updated_at', 'desc')->get();

        return view('Activity.documentLink.index', compact('documentLinks', 'activityData', 'id'));
    }

    /**
     * display document link with specific id
     * @param $activityId
     * @param $documentLinkId
     * @return \Illuminate\View\View
     */
    public function show($activityId, $documentLinkId)
    {
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $documentLinks = [$this->documentLinkManager->getDocumentLink($documentLinkId, $activityId)];
        $id            = $activityId;

        return view('Activity.documentLink.show', compact('documentLinks', 'activityData', 'id', 'documentLinkId'));
    }

    /**
     * Show the form for creating document link
     * @param $id
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activityData);

        return $this->loadForm($id);
    }

    /**
     * Show the form for editing document link
     * @param $id
     * @param $documentLinkId
     * @return \Illuminate\View\View
     */
    public function edit($id, $documentLinkId)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activityData);
        $documentLink = $this->documentLinkManager->getDocumentLink($documentLinkId, $id);

        return $this->loadForm($id, $documentLink, $documentLinkId);
    }

    /**
     * return form view for create and edit result
     * @param      $id
     * @param null $documentLink
     * @param null $documentLinkId
     * @return \Illuminate\View\View
     */
    public function loadForm($id, $documentLink = null, $documentLinkId = null)
    {
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->documentLinkForm->getForm($id, $documentLink);

        return view('Activity.documentLink.edit', compact('form', 'activityData', 'id', 'documentLinkId'));
    }

    /**
     * Update activity document link
     * @param                            $id
     * @param                            $documentLinkId
     * @param Request                    $request
     * @param DocumentLinkRequestManager $documentLinkRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, $documentLinkId, Request $request, DocumentLinkRequestManager $documentLinkRequestManager)
    {
        $documentLinkData     = $request->get('document_link');
        $activityDocumentLink = $this->documentLinkManager->getDocumentLink($documentLinkId, $id);
        $activityData         = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityDocumentLink, 'document_link', true);
        if ($this->documentLinkManager->update($documentLinkData, $activityDocumentLink)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => [($documentLinkId) ? 'updated' : 'created', ['name' => 'Activity Document Link']]];

            return redirect()->to(route('activity.document-link.index', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => [($documentLinkId) ? 'update_failed' : 'save_failed', ['name' => 'Activity Document Link']]];

        return redirect()->back()->withInput()->withResponse($response);
    }

    /**
     * Remove document link from database.
     * @param  int $id
     * @param      $documentLinkId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $documentLinkId)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activityData);
        $activityDocumentLink = $this->documentLinkManager->getDocumentLink($documentLinkId, $id);

        $response = ($this->documentLinkManager->delete($activityDocumentLink))
            ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Document Link']]]
            : ['type' => 'danger', 'code' => ['delete_failed', ['name' => 'Document Link']]];

        return redirect()->back()->withResponse($response);
    }
}
