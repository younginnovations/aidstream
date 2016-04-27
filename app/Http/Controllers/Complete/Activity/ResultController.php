<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\ResultManager;
use App\Services\FormCreator\Activity\Result as ResultForm;
use App\Services\RequestManager\Activity\Result as ResultRequestManager;
use App\Http\Requests\Request;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Gate;

/**
 * Class ResultController
 * @package App\Http\Controllers\Complete\Activity
 */
class ResultController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var ResultManager
     */
    protected $resultManager;
    /**
     * @var ResultForm
     */
    protected $resultForm;

    /**
     * @param ResultManager   $resultManager
     * @param ResultForm      $resultForm
     * @param ActivityManager $activityManager
     */
    function __construct(ResultManager $resultManager, ResultForm $resultForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager = $activityManager;
        $this->resultManager   = $resultManager;
        $this->resultForm      = $resultForm;
    }

    /**
     * Return results list
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $results = $this->resultManager->getResults($id);

        return view('Activity.result.index', compact('results', 'activityData', 'id'));
    }

    /**
     * display result with specific id
     * @param $activityId
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($activityId, $id)
    {
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $result   = $this->resultManager->getResult($id, $activityId);
        $resultId = $id;
        $id       = $activityId;

        return view('Activity.result.show', compact('result', 'activityData', 'id', 'resultId'));
    }

    /**
     * Show the form for creating result
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
     * Show the form for editing activity result.
     *
     * @param  int $id
     * @param      $resultId
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $resultId)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activityData);
        $result = $this->resultManager->getResult($resultId, $id);

        return $this->loadForm($id, $result, $resultId);
    }

    /**
     * return form view for create and edit result
     * @param      $id
     * @param null $result
     * @param null $resultId
     * @return \Illuminate\View\View
     */
    public function loadForm($id, $result = null, $resultId = null)
    {
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->resultForm->getForm($id, $result);

        return view('Activity.result.edit', compact('form', 'activityData', 'id', 'resultId'));
    }

    /**
     * Update activity result
     * @param                      $id
     * @param                      $resultId
     * @param Request              $request
     * @param ResultRequestManager $resultRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, $resultId, Request $request, ResultRequestManager $resultRequestManager)
    {
        $resultData     = $request->all();
        $activityResult = $this->resultManager->getResult($resultId, $id);
        $activityData   = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityResult, 'result', true);
        if ($this->resultManager->update($resultData, $activityResult)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => [($resultId) ? 'updated' : 'created', ['name' => 'Activity Result']]];

            return redirect()->to(sprintf('/activity/%s/result', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Related Activity']]];

        return redirect()->back()->withInput()->withResponse($response);
    }

    /**
     * Remove result from storage.
     * @param  int $id
     * @param      $resultId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $resultId)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activityData);
        $activityResult = $this->resultManager->getResult($resultId, $id);

        $response = ($this->resultManager->deleteResult($activityResult)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Result']]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'result']]
        ];

        app(Log::class)->activity(
            "activity.activity_result_deleted",
            [
                'result_id'   => $resultId,
                'activity_id' => $id
            ]
        );

        return redirect()->back()->withResponse($response);
    }
}
