<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityDateManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\ActivityDate as ActivityDateForm;
use App\Services\RequestManager\Activity\ActivityDate as ActivityDateRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class ActivityDateController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityDateController extends Controller
{
    /**
     * @var ActivityDateForm
     */
    protected $activityDateForm;
    /**
     * @var ActivityDateManager
     */
    protected $activityDateManager;
    protected $activityManager;

    /**
     * @param ActivityDateManager $activityDateManager
     * @param ActivityDateForm    $activityDateForm
     * @param ActivityManager     $activityManager
     */
    function __construct(
        ActivityDateManager $activityDateManager,
        ActivityDateForm $activityDateForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->activityManager     = $activityManager;
        $this->activityDateForm    = $activityDateForm;
        $this->activityDateManager = $activityDateManager;
    }

    /**
     * returns the activity date edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityDate = $this->activityDateManager->getActivityDateData($id);
        $form         = $this->activityDateForm->editForm($activityDate, $id);

        return view(
            'Activity.activityDate.edit',
            compact('form', 'activityData', 'id')
        );
    }

    /**
     * updates activity description
     * @param                            $id
     * @param Request                    $request
     * @param ActivityDateRequestManager $activityDateRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, ActivityDateRequestManager $activityDateRequestManager)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'activity_date');
        $activityDate = $request->all();
        $messages     = $this->validateData($request->get('activity_date'));
        if ($messages) {
            $response = ['type' => 'warning', 'messages' => array_unique($messages)];

            return redirect()->back()->withInput()->withResponse($response);
        }
        if ($this->activityDateManager->update($activityDate, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.activity_date')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.activity_date')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }

    /**
     * Validate activity date data based on Activity Date and Activity Date Type
     * @param array $data
     * @return bool
     */
    private function validateData(array $data)
    {
        $messages = [];
        $hasStart = false;

        $activityDates = [];

        foreach ($data as $datum) {
            $activityDates[] = $datum;
        }

        foreach ($activityDates as $activityDateIndex => $activityDate) {
            $blockIndex = $activityDateIndex + 1;
            $date       = $activityDate['date'];
            $type       = $activityDate['type'];
            if ($type == 2 || $type == 4) {
                (strtotime($date) <= strtotime(date('Y-m-d'))) ?: $messages[] = trans('error.today_date', ['block' => $blockIndex]);
            }
            if ($type == 1 || $type == 2) {
                $hasStart = true;
            } elseif ($type == 3 || $type == 4) {
                $prevData = isset($activityDates[$activityDateIndex - 1]) ? $activityDates[$activityDateIndex - 1] : null;

                if (!$prevData) {
                    $messages[] = trans('error.end_date_after_start_date', ['block' => $blockIndex]);
                } else {
                    if (strtotime($date) < strtotime($prevData['date'])) {
                        $messages[] = trans('error.end_date_later_start_date', ['block' => $blockIndex]);
                    }
                }

                /*
                 * Commented in case of further confusions.
                 */
//                    (strtotime($prevData['date']) < strtotime($date)) ?: $check = false;
//                    if ($type == 3) {
//                        $prevData['type'] == 1 ?: $messages[] = sprintf('Ends should be after respective Starts in Activity Date Type (block %s)', $blockIndex);
//                    } else {
//                        $prevData['type'] == 2 ?: $messages[] = sprintf('Ends should be after respective Starts in Activity Date Type (block %s)', $blockIndex);
//                    }
//                else {
//                    $messages[] = sprintf('Ends should be after respective Starts in Activity Date Type (block %s)', $blockIndex);
//                }
            }
        }

        if (!$hasStart) {
            array_unshift($messages, 'error.planned_actual_start_date_required');
        }

        return $messages;
    }
}
