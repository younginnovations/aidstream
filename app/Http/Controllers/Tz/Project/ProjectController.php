<?php namespace App\Http\Controllers\Tz\Project;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Tz\TanzanianController;
use App\Services\Organization\OrganizationManager;
use App\Tz\Aidstream\Requests\ProjectRequests;
use App\Tz\Aidstream\Services\Project\ProjectService;
use App\Tz\Aidstream\Services\Transaction\TransactionService;
use App\Tz\Aidstream\Traits\FormatsProjectFormInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class ProjectController
 * @package App\Http\Controllers\Tz\Project
 */
class ProjectController extends TanzanianController
{
    use FormatsProjectFormInformation;

    /**
     * @var ProjectService
     */
    protected $project;

    /**
     * @var TransactionService
     */
    protected $transaction;
    protected $orgManager;

    /**
     * ProjectController constructor.
     * @param ProjectService      $project
     * @param TransactionService  $transaction
     * @param OrganizationManager $organizationManager
     */
    public function __construct(ProjectService $project, TransactionService $transaction, OrganizationManager $organizationManager)
    {
        $this->middleware('auth');
        $this->project     = $project;
        $this->transaction = $transaction;
        $this->orgManager  = $organizationManager;
    }

    /**
     * List all Projects.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects    = $this->project->all();
        $statusLabel = ['draft', 'completed', 'verified', 'published'];

        return view('tz.project.index', compact('projects', 'statusLabel'));
    }

    /**
     * Show the form to create a new Project.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $baseForm           = new BaseForm();
        $codeList           = $baseForm->getCodeList('ActivityStatus', 'Activity');
        $sectors            = $baseForm->getCodeList('SectorCategory', 'Activity');
        $recipientRegions   = $baseForm->getCodeList('Region', 'Activity');
        $participatingOrg   = $baseForm->getCodeList('OrganisationRole', 'Activity');
        $organizationType   = $baseForm->getCodeList('OrganisationType', 'Activity');
        $recipientCountries = $baseForm->getCodeList('Country', 'Organization');
        $fileFormat         = $baseForm->getCodeList('FileFormat', 'Activity');
        $transactionType    = $baseForm->getCodeList('TransactionType', 'Activity');
        $currency           = $baseForm->getCodeList('Currency', 'Activity');

        return view('tz.project.create', compact('codeList', 'sectors', 'recipientRegions', 'participatingOrg', 'organizationType', 'recipientCountries', 'fileFormat', 'transactionType', 'currency'));
    }

    /**
     * Store a new Project into the database
     * @param Request         $request
     * @param ProjectRequests $projectRequests
     * @return RedirectResponse
     */
    public function store(Request $request, ProjectRequests $projectRequests)
    {
        $projectId = $this->project->create($this->process($request->all()), $request->all());

        if (!$projectId) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Project could not be saved.']]];

            return redirect()->route('project.index')->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['message', ['message' => 'Project successfully saved.']]];

        return redirect()->route('project.show', $projectId)->withResponse($response);
    }

    /**
     * Show a Project.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $incomingFund = $this->transaction->getTransactions($id, 1);
        $disbursement = $this->transaction->getTransactions($id, 3);
        $expenditure  = $this->transaction->getTransactions($id, 4);
        $transactions = $this->transaction->findByActivityId($id);
        $fundings      = $this->project->getParticipatingOrganizations($id, 1);
        $implementings = $this->project->getParticipatingOrganizations($id, 4);

        $statusLabel      = ['draft', 'completed', 'verified', 'published'];
        $activityWorkflow = $project->activity_workflow;
        $btn_status_label = ['Completed', 'Verified', 'Published'];
        $btn_text         = $activityWorkflow > 2 ? "" : $btn_status_label[$activityWorkflow];

        if ($project['activity_workflow'] == 0) {
            $nextRoute = route('activity.complete', $id);
        } elseif ($project['activity_workflow'] == 1) {
            $nextRoute = route('activity.verify', $id);
        } else {
            $nextRoute = route('activity.publish', $id);
        }

        return view(
            'tz.project.show',
            compact('fundings','implementings','project', 'transactions', 'activityResult', 'id', 'statusLabel', 'btn_text', 'activityWorkflow', 'nextRoute', 'incomingFund', 'disbursement', 'expenditure')
        );
    }

    /**
     * Edit an existing Project.
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $project            = $this->project->find($id);
        $project            = $this->reverseMap($project);
        $documentLinks      = $this->project->findDocumentLinkByProjectId($id);
        $baseForm           = new BaseForm();
        $codeList           = $baseForm->getCodeList('ActivityStatus', 'Activity');
        $sectors            = $baseForm->getCodeList('SectorCategory', 'Activity');
        $recipientRegions   = $baseForm->getCodeList('Region', 'Activity');
        $recipientCountries = $baseForm->getCodeList('Country', 'Organization');
        $organizationType   = $baseForm->getCodeList('OrganisationType', 'Activity');
        $fileFormat         = $baseForm->getCodeList('FileFormat', 'Activity');

        return view('tz.project.edit', compact('documentLinks', 'project', 'codeList', 'sectors', 'recipientRegions', 'recipientCountries', 'organizationType', 'fileFormat'));
    }

    /**
     * Update an existing Project.
     * @param                 $id
     * @param Request         $request
     * @param ProjectRequests $requests
     * @return mixed
     */
    public function update($id, Request $request, ProjectRequests $requests)
    {
        if (!$this->project->update($id, $this->process($request->all()), $request->all())) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Project could not be updated.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Project successfully updated.']]];
        }

        return redirect()->route('project.show', $id)->withResponse($response);
    }

    /**
     * Delete an existing Project.
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $response = $this->project->delete($id)
            ? ['type' => 'success', 'code' => ['message', 'message' => 'Project successfully deleted.']]
            : ['type' => 'danger', 'code' => ['message', 'message' => 'Project could not be deleted.']];

        return redirect()->route('project.index')->withResponse($response);
    }

    /**
     * Show the form to change Project Default Field Values.
     * @param $id
     * @return \Illuminate\View\View
     */
    public function changeProjectDefaults($id)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $project);

        $baseForm = new BaseForm();
        $language = $baseForm->getCodeList('Language', 'Organization');
        $currency = $baseForm->getCodeList('Currency', 'Organization');
        $project  = $this->projectDefaults($project, $project->organization->settings);

        return view('tz.project.overrideProjectDefaults', compact('project', 'language', 'currency'));
    }

    /**
     * Override Project Default Field Values.
     * @param         $id
     * @param Request $request
     * @return mixed
     */
    public function overrideProjectDefaults($id, Request $request)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $project);

        $response = $this->project->updateDefaults($id, $this->processDefaultFieldValues($request->all()))
            ? ['type' => 'success', 'code' => ['message', 'message' => 'Project Defaults successfully overridden.']]
            : ['type' => 'danger', 'code' => ['message', 'message' => 'Could not override Project defaults.']];

        return redirect()->route('project.show', [$id])->withResponse($response);
    }

    public function upload()
    {
        // TODO
    }

    /**
     * List Published Files.
     * @return mixed
     */
    public function listPublishedFiles()
    {
        $publishedFiles = $this->project->getPublishedFiles();

        return view('tz.project.publishedFiles', compact('publishedFiles'));
    }

    /**
     * Show the list of Users.
     * @return mixed
     */
    public function listUsers()
    {
        $users = $this->project->getUsers();

        return view('tz.users', compact('users'));
    }

    /**
     * Show the download page.
     * @return mixed
     */
    public function download()
    {
        return view('tz.downloads.index');
    }

    /**
     * Duplicate an existing Project.
     * @param         $id
     * @return mixed
     */
    public function duplicate($id)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->project->duplicate($project)) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Project could not be duplicated.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Project successfully duplicated.']]];
        }

        return redirect()->route('project.index', $id)->withResponse($response);
    }

    /**
     * Get a Project's default field values.
     * @param $project
     * @param $settings
     * @return array
     */
    protected function projectDefaults($project, $settings)
    {
        return [
            'id'               => $project->id,
            'default_currency' => $project->default_field_values[0]['default_currency']
                ? getVal($project->default_field_values, [0, 'default_currency'])
                : getVal($settings->default_field_values, [0, 'default_currency']),
            'default_language' => $project->default_field_values[0]['default_language']
                ? getVal($project->default_field_values, [0, 'default_language'])
                : getVal(
                    $settings->default_field_values,
                    [0, 'default_language']
                )
        ];
    }

    public function projectPublic($orgId)
    {
        $projectId = 3115;
        $projects  = $this->project->getProjectData($projectId);
        $jsonData  = $this->project->getJsonData($projects);

//        $projects = $this->project->getProjectsByOrganisationId($orgId);
//        $transactionCount = $this->transaction->getTransactionsSum($projects);
//        $orgDetails = $this->orgManager->getOrganization($orgId);
//        $user = $this->user->getDataByOrgIdAndRoleId($orgId, '1');
//
//        return view('tz.projectPublicPage', compact('projects', 'orgDetails', 'user', 'transactionCount'));
    }
}
