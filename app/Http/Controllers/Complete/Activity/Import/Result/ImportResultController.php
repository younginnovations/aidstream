<?php namespace App\Http\Controllers\Complete\Activity\Import\Result;

use App\Http\Requests\Request;
use App\Services\CsvImporter\ImportResultManager as ImportManager;
use App\Http\Controllers\Controller;
use App\Core\V201\Requests\Activity\Result\ImportResult;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Activity\Result\ImportResult as ImportResultForm;
use Illuminate\Support\Facades\File;

class ImportResultController extends Controller
{

    /**
     * @var ImportResultForm
     */
    protected $form;

    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @var ImportManager
     */
    protected $importManager;

    /**
     * Result Template file path.
     */
    const RESULT_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/result.csv';

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

    /**
     * ImportController constructor.
     * @param ImportResultForm    $form
     * @param OrganizationManager $organizationManager
     * @param ImportManager       $importManager
     */
    public function __construct(ImportResultForm $form, OrganizationManager $organizationManager, ImportManager $importManager)
    {
        $this->form                = $form;
        $this->organizationManager = $organizationManager;
        $this->importManager       = $importManager;
        $this->userId              = auth()->user()->id;
        $this->middleware('auth');
    }

    /**
     * Download the Result Template.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadResultTemplate()
    {
        $path = self::RESULT_TEMPLATE_PATH;

        return response()->download(app_path(sprintf($path, session('version'))));
    }

    /**
     * Show the form to upload the Result Csv.
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadResultCsv($activityId)
    {
        if (session()->has('header_mismatch') && (session()->get('header_mismatch') == true)) {

            return redirect()->route('activity.result.upload-redirect', $activityId);
        }

        $organization = $this->organizationManager->getOrganization(session('org_id'));

        $this->importManager->refreshSessionIfRequired();

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $form = $this->form->createForm($activityId);

        return view('Activity.result.uploader', compact('form', 'activityId'));
    }

    /**
     * Import Activities into the database.
     * @param              $activityId
     * @param ImportResult $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function results($activityId, ImportResult $request)
    {
        $file = $request->file('result');

        if ($this->importManager->storeCsv($file)) {
            $filename = $file->getClientOriginalName();
            $this->importManager->startImport($filename)
                                ->fireCsvUploadEvent($filename);

            $this->fixStagingPermission(storage_path('csvImporter/tmp'));

            return redirect()->route('activity.result.import-status', $activityId);
        }

        $response = ['type' => 'danger', 'code' => ['csv_header_mismatch', ['message' => 'Something is not right.']]];

        return redirect()->to('activity.result.upload-csv')->withResponse($response);
    }

    /**
     * Import validated activities into the database.
     * @param Request $request
     * @return mixed
     */
    public function importValidatedResults($activityId, Request $request)
    {
        $results = $request->get('results');

        if ($results) {
            $this->importManager->create($activityId, $results);
            $this->importManager->endImport();

            return redirect()->route('activity.index')->withResponse(['type' => 'success', 'code' => ['message', ['message' => 'Activities successfully imported.']]]);
        } else {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => 'Please select the activities to be imported.']]]);
        }
    }

    /**
     * Show the status page for the Csv Import process.
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status($activityId)
    {
        return view('Activity.csvImporter.result.status', compact('activityId'));
    }

    /**
     * Check Import Status.
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus()
    {
        if ($this->importManager->caughtExceptions()) {
            $this->importManager->deleteFile('header_mismatch.json')
                                ->reportHeaderMismatch();

            return response()->json(json_encode(['status' => 'Error', 'message' => 'The headers in the uploaded Csv file do not match with the provided template.']));
        }

        if ($result = $this->importManager->importIsComplete()) {
            return response()->json($result);
        }

        return response()->json(json_encode(['status' => 'Incomplete']));
    }

    /**
     * Get the remaining invalid data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRemainingInvalidData()
    {
        $filepath = $this->importManager->getFilePath(false);

        if (file_exists($filepath)) {
            $results = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('invalid-temp.json');

            if (file_exists($tempPath)) {
                $old   = json_decode(file_get_contents($tempPath), true);
                $diff  = array_diff_key($results, $old);
                $total = array_merge($diff, $old);

                File::put($tempPath, json_encode($total));

                $results = $diff;

                $response = ['render' => view('Activity.csvImporter.result.invalid', compact('results'))->render()];

                return response()->json($response);
            } else {
                File::put($tempPath, json_encode($results));
            }

            $response = ['render' => view('Activity.csvImporter.result.invalid', compact('results'))->render()];
        } else {
            $response = ['render' => '<p>No data available.</p>'];
        }

        return response()->json($response);
    }

    /**
     * Get the remaining valid data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRemainingValidData()
    {
        $filepath = $this->importManager->getFilePath(true);

        if (file_exists($filepath)) {
            $results = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('valid-temp.json');

            if (file_exists($tempPath)) {
                $old   = json_decode(file_get_contents($tempPath), true);
                $diff  = array_diff_key($results, $old);
                $total = array_merge($diff, $old);

                File::put($tempPath, json_encode($total));

                $results = $diff;

                $response = ['render' => view('Activity.csvImporter.result.valid', compact('results'))->render()];

                return response()->json($response);
            } else {
                File::put($tempPath, json_encode($results));
            }

            $response = ['render' => view('Activity.csvImporter.result.valid', compact('results'))->render()];
        } else {
            $response = ['render' => '<p>No data available.</p>'];
        }

        return response()->json($response);
    }

    /**
     * Clear all invalid Activities.
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearInvalidActivities()
    {
        if ($this->importManager->clearInvalidActivities()) {
            return response()->json('cleared');
        }

        return response()->json('error');
    }

    /**
     * Get the Csv Import status from the current User's session.
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSessionStatus()
    {
        return response()->json(['status' => $this->importManager->getSessionStatus()]);
    }

    /**
     * Cancel the CSV Uploading Process.
     * @param $activityId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($activityId)
    {
        $this->importManager->removeImportDirectory();
        $this->importManager->endImport();

        return redirect()->route('activity.result.upload-csv', $activityId);
    }

    /**
     * Get processed data from the server.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        if (!($response = $this->importManager->getData())) {
            $response = ['render' => '<p>No data available.</p>'];
        }

        return response()->json($response);
    }

    /**
     * Redirect to upload csv page in case of header mismatch.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadRedirect($activityId)
    {
        $form = $this->form->createForm($activityId);

        $this->importManager->clearSession(['import-result-status', 'filename']);

        if ($this->importManager->headersHadBeenMismatched()) {
            $this->importManager->clearSession(['header_mismatch']);
            $this->importManager->deleteFile('status.json');

            $mismatch = ['type' => 'warning', 'code' => ['message', ['message' => 'The headers in the uploaded Csv file do not match with the provided template.']]];

            return view('Activity.uploader', compact('form', 'mismatch'));
        }

        $mismatch = null;

        return view('Activity.uploader', compact('form', 'mismatch'));
    }

    /**
     * Fix file permission while on staging environment
     * @param $path
     */
    protected function fixStagingPermission($path)
    {
        // TODO: Remove this.
        shell_exec(sprintf('chmod 777 -R %s', $path));
    }
}
