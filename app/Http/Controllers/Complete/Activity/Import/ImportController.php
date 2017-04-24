<?php namespace App\Http\Controllers\Complete\Activity\Import;

use App\Http\Requests\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use App\Services\CsvImporter\ImportManager;
use App\Services\Organization\OrganizationManager;
use App\Core\V201\Requests\Activity\ImportActivity;
use App\Services\FormCreator\Activity\ImportActivity as ImportActivityForm;


/**
 * Class ImportController
 * @package App\Http\Controllers\Complete\Activity\Import
 */
class ImportController extends Controller
{
    /**
     * @var ImportActivityForm
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
     * Basic Activity Template file path.
     */
    const BASIC_ACTIVITY_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/basic.csv';

    /**
     * Activity with Transactions Template file path.
     */
    const TRANSACTION_ACTIVITY_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/transaction.csv';

    /**
     * Activity with Other Fields file path.
     */
    const OTHER_FIELDS_ACTIVITY_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/other_fields.csv';

    /**
     * Activity with Other Fields and Transaction file path.
     */
    const OTHERS_FIELDS_TRANSACTION_ACTIVITY_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/other_fields_transaction.csv';

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

    /**
     * ImportController constructor.
     * @param ImportActivityForm  $form
     * @param OrganizationManager $organizationManager
     * @param ImportManager       $importManager
     */
    public function __construct(ImportActivityForm $form, OrganizationManager $organizationManager, ImportManager $importManager)
    {
        $this->middleware('auth');
        $this->middleware('auth.systemVersion');
        $this->form                = $form;
        $this->organizationManager = $organizationManager;
        $this->importManager       = $importManager;
        $this->userId              = $this->getUserId();
    }

    /**
     * Download the Activity Template.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadActivityTemplate(Request $request)
    {
        $type = $request->get('type');

        if ($type == 'basic') {
            $path = self::BASIC_ACTIVITY_TEMPLATE_PATH;
        }

        if ($type == 'transaction') {
            $path = self::TRANSACTION_ACTIVITY_TEMPLATE_PATH;
        }

        if ($type == 'others') {
            $path = self::OTHER_FIELDS_ACTIVITY_TEMPLATE_PATH;
        }

        if ($type == 'others-transaction') {
            $path = self::OTHERS_FIELDS_TRANSACTION_ACTIVITY_TEMPLATE_PATH;
        }

        return response()->download(app_path(sprintf($path, session('version'))));
    }

    /**
     * Show the form to upload the Activity Csv.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadActivityCsv()
    {
        if (session()->has('header_mismatch') && (session()->get('header_mismatch') == true)) {

            return redirect()->route('activity.upload-redirect');
        }

        $organization = $this->organizationManager->getOrganization(session('org_id'));

        $this->importManager->refreshSessionIfRequired();

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => trans('global.activity')]]];

            return redirect('/settings')->withResponse($response);
        }

        if (session()->has('import-status') && session()->get('import-status') == 'Complete') {
            $importing = true;
        }

        $form = $this->form->createForm();

        return view('Activity.uploader', compact('form', 'importing'));
    }

    /**
     * Import Activities into the database.
     * @param ImportActivity $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities(ImportActivity $request)
    {
        $file = $request->file('activity');

        if ($this->importManager->isCsvFileEmpty($file)) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => trans('error.no_data_available')]]];

            return redirect()->to('import-activity')->withResponse($response);
        }

        $this->importManager->clearOldImport();

        if ($this->importManager->storeCsv($file)) {
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $this->importManager->startImport($filename)
                                ->fireCsvUploadEvent($filename);

            $this->fixPermission(storage_path('csvImporter/tmp'));

            $response = null;

            if (!$this->importManager->isInUTF8Encoding($filename)) {
                $response = ['type' => 'warning', 'code' => ['encoding_error', ['message' => trans('error.something_is_not_right')]]];
            }

            return redirect()->route('activity.import-status')->withResponse($response);
        }

        $response = ['type' => 'danger', 'code' => ['csv_header_mismatch', ['message' => trans('error.something_is_not_right')]]];

        return redirect()->to('import-activity')->withResponse($response);
    }

    /**
     * Import validated activities into the database.
     * @param Request $request
     * @return mixed
     */
    public function importValidatedActivities(Request $request)
    {
        $activities = $request->get('activities');

        if ($activities) {
            $this->importManager->create($activities);
            $this->importManager->endImport();

            return redirect()->route('activity.index')->withResponse(['type' => 'success', 'code' => ['message', ['message' => trans('success.activities_successfully_imported')]]]);
        } else {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => trans('error.select_activities_to_be_imported')]]]);
        }
    }

    /**
     * Show the status page for the Csv Import process.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status()
    {
        if (session()->has('import-status') && session()->get('import-status') == 'Complete') {
            $data = $this->importManager->getData();
        } elseif (!session()->has('import-status')) {
            return redirect()->route('activity.upload-csv')->withResponse(['type' => 'success', 'code' => ['message', ['message' => trans('success.no_on_going_process')]]]);
        }

        return view('Activity.csvImporter.status', compact('data'));
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

            return response()->json(json_encode(['status' => 'Error', 'message' => trans('error.header_mismatch')]));
        }

        if ($result = $this->importManager->importIsComplete()) {
            return response()->json($result);
        }

        return response()->json(json_encode(['status' => 'Processing']));
    }

    /**
     * Get the remaining invalid data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRemainingInvalidData()
    {
        $filepath = $this->importManager->getFilePath(false);
        $this->fixPermission($this->importManager->getTemporaryFilepath());

        if (file_exists($filepath)) {
            $activities = $this->importManager->fetchData($filepath, 'invalid-temp.json');

            $response = ['render' => view('Activity.csvImporter.invalid', compact('activities'))->render()];
        } else {
            $response = ['render' => sprintf('<p>%s</p>', trans('error.data_not_available'))];
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
            $activities = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('valid-temp.json');

            if (file_exists($tempPath)) {
                $old   = json_decode(file_get_contents($tempPath), true);
                $diff  = array_diff_key($activities, $old);
                $total = array_merge($diff, $old);

                File::put($tempPath, json_encode($total));

                $activities = $diff;

                $response = ['render' => view('Activity.csvImporter.valid', compact('activities'))->render()];

                return response()->json($response);
            } else {
                File::put($tempPath, json_encode($activities));
            }

            $response = ['render' => view('Activity.csvImporter.valid', compact('activities'))->render()];
        } else {
            $response = ['render' => sprintf('<p>%s</p>', trans('error.data_not_available'))];
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
     */
    public function cancel()
    {
        $this->importManager->removeImportDirectory();
        $this->importManager->clearSession(['import-status', 'filename']);

        return redirect()->route('activity.upload-csv');
    }

    /**
     * Get processed data from the server.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        if (!($response = $this->importManager->getData())) {
            $response = ['render' => sprintf('<p>%s</p>', trans('error.data_not_available'))];
        }

        return response()->json($response);
    }

    /**
     * Redirect to upload csv page in case of header mismatch.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadRedirect()
    {
        $form = $this->form->createForm();

        $this->importManager->clearSession(['import-status', 'filename']);

        if ($this->importManager->headersHadBeenMismatched()) {
            $this->importManager->clearSession(['header_mismatch']);
            $this->importManager->deleteFile('status.json');

            $mismatch = ['type' => 'warning', 'code' => ['message', ['message' => trans('error.header_mismatch')]]];

            return view('Activity.uploader', compact('form', 'mismatch'));
        }

        $mismatch = null;

        return view('Activity.uploader', compact('form', 'mismatch'));
    }

    /**
     * Fix file permission while on staging environment
     * @param $path
     */
    protected function fixPermission($path)
    {
        shell_exec(sprintf('chmod 777 -R %s', $path));
    }

    /**
     * Get the currently logged in User's Id.
     * @return null
     */
    protected function getUserId()
    {
        if (auth()->check()) {
            return auth()->user()->id;
        }

        return null;
    }

    /**
     *  Returns texts used in csv importer
     * @return string
     */
    public function getLocalisedCsvFile()
    {
        $currentLanguage = ($language = (Cookie::get('language'))) ? $language : 'en';

        return file_get_contents(sprintf(resource_path('lang/%s/csvImporter.json'), $currentLanguage));
    }
}
