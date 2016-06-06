<?php namespace App\Http\Controllers;

use App\Models\Organization\Organization;
use App\Services\Organization\OrganizationManager;
use App\Tz\Aidstream\Services\Project\ProjectService;
use App\Tz\Aidstream\Services\Transaction\TransactionService;
use App\User;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var WhoIsUsingController
     */
    protected $organizationCount;
    protected $project;
    protected $transaction;
    protected $orgManager;
    protected $user;

    function __construct(WhoIsUsingController $organizationCount, ProjectService $projectService, TransactionService $transaction, OrganizationManager $organizationManager, User $user)
    {
        $this->organizationCount = $organizationCount;
        $this->project           = $projectService;
        $this->transaction       = $transaction;
        $this->orgManager        = $organizationManager;
        $this->user              = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizationCount = $this->organizationCount->initializeOrganizationQueryBuilder()->get()->count();

        if ($this->hasSubdomain($this->getRoutePieces())) {
            $projects = $this->project->getProjectData();

            return view('tz.homepage', compact('organizationCount', 'projects'));
        }

        return view('home', compact('organizationCount'));
    }

    /**
     * view project public page
     * @param $orgId
     * @return mixed
     */
    public function projectPublicPage($orgId)
    {
        $projects         = $this->project->getProjectsByOrganisationId($orgId);
        $transactionCount = $this->transaction->getTransactionsSum($projects);
        $orgDetails       = $this->orgManager->getOrganization($orgId);
        $user             = $this->user->getDataByOrgIdAndRoleId($orgId, '1');

        return view('tz.projectPublicPage', compact('orgDetails', 'user', 'transactionCount'));
    }

    public function projectlists($orgId = 0)
    {
        $projects = $this->project->getPublishedProjects($orgId ? $orgId : "");
        $jsonData = json_encode($this->project->getProjectJsonData($projects));

        return view('tz.project.jsonProjects', compact('jsonData'));
    }

    /**
     * view project
     * @param $id
     * @return mixed
     */
    public function projectDetails($id)
    {
        $project = $this->project->find($id);

        if (!$this->hasBeenPublished($id)) {
            return view('tz.unauthorized');
        }

        $incomingFunds = $this->transaction->getTransactions($id, 1);
        $disbursements = $this->transaction->getTransactions($id, 3);
        $expenditures  = $this->transaction->getTransactions($id, 4);
        $documentLinks = $this->project->findDocumentLinkByProjectId($id);
        $fundings      = $this->project->getParticipatingOrganizations($id, 1);
        $implementings = $this->project->getParticipatingOrganizations($id, 4);
        $orgDetail     = $this->orgManager->getOrganization($project->organization_id);

        return view('tz.projectDetail', compact('orgDetail', 'project', 'incomingFunds', 'disbursements', 'expenditures', 'documentLinks', 'fundings', 'implementings'));
    }

    protected function hasBeenPublished($projectId)
    {
        return $this->project->hasBeenPublished($projectId);
    }
}
