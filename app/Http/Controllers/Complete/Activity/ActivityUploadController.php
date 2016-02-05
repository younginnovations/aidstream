<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\Repositories\Activity\IatiIdentifierRepository;
use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\UploadActivityManager;
use App\Services\FormCreator\Activity\UploadActivity;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Activity\CsvImportValidator;
use App\Services\RequestManager\Activity\UploadActivity as UploadActivityRequest;
use App\Http\Requests\Request;
use Illuminate\Session\SessionManager;

class ActivityUploadController extends Controller
{
    /**
     * @var UploadActivity
     */
    protected $uploadActivity;
    /**
     * @var UploadActivityManager
     */
    protected $uploadActivityManager;
    protected $activityManager;
    protected $organizationId;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @param OrganizationManager   $organizationManager
     * @param SessionManager        $sessionManager
     * @param ActivityManager       $activityManager
     * @param UploadActivityManager $uploadActivityManager
     * @param UploadActivity        $uploadActivity
     */
    function __construct(
        OrganizationManager $organizationManager,
        SessionManager $sessionManager,
        ActivityManager $activityManager,
        UploadActivityManager $uploadActivityManager,
        UploadActivity $uploadActivity
    ) {
        $this->middleware('auth');
        $this->activityManager       = $activityManager;
        $this->uploadActivity        = $uploadActivity;
        $this->uploadActivityManager = $uploadActivityManager;
        $this->sessionManager        = $sessionManager;
        $this->organizationId        = $this->sessionManager->get('org_id');
        $this->organizationManager   = $organizationManager;
    }

    /**
     * show the upload form
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $organization = $this->organizationManager->getOrganization($this->organizationId);
        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }
        $form = $this->uploadActivity->createForm();

        return view('Activity.upload', compact('form'));
    }

    /**
     * Stores the activity by uploading csv
     * @param Request                  $request
     * @param UploadActivityRequest    $uploadActivityRequest
     * @param CsvImportValidator       $csvImportValidator
     * @param IatiIdentifierRepository $iatiIdentifierRepository
     * @return $this
     */
    public function store(Request $request, UploadActivityRequest $uploadActivityRequest, CsvImportValidator $csvImportValidator, IatiIdentifierRepository $iatiIdentifierRepository)
    {
        $this->authorize('add_activity');
        $organization = $this->organizationManager->getOrganization($this->organizationId);

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $identifiers         = [];
        $ActivityIdentifiers = $iatiIdentifierRepository->getIdentifiersForOrganization();
        foreach ($ActivityIdentifiers as $identifier) {
            $identifiers[] = $identifier->identifier['activity_identifier'];
        }

        $file      = $request->file('activity');
        $validator = $csvImportValidator->validator->isValidActivityCsv($file, $identifiers);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $check = $this->uploadActivityManager->save($file, $organization);
        if (is_a($check, 'Illuminate\View\View')) {
            return $check;
        }
        $response = ($check) ? ['type' => 'success', 'code' => ['updated', ['name' => 'Activities']]] : ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Activities']]];

        return redirect()->to(sprintf('/activity'))->withResponse($response);
    }

    /**
     * update activities by uploading csv
     * @param         $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $activityDetails = $request->get('checkbox');
        $this->uploadActivityManager->update($activityDetails);
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activities']]];

        return redirect()->to(sprintf('/activity'))->withResponse($response);
    }
}
