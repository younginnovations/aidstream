<?php namespace App\Http\Controllers\Complete\Xml;

use App\Http\Controllers\Controller;
use App\Http\Requests\Xml\XmlUploadRequest;
use App\Services\XmlImporter\XmlImportManager;
use Illuminate\Support\Facades\Session;

/**
 * Class XmlImportController
 * @package App\Http\Controllers\Complete\Xml
 */
class XmlImportController extends Controller
{
    /**
     * @var XmlImportManager
     */
    protected $xmlImportManager;

    /**
     * XmlImportController constructor.
     * @param XmlImportManager $xmlImportManager
     */
    public function __construct(XmlImportManager $xmlImportManager)
    {
        $this->middleware('auth');
        $this->xmlImportManager = $xmlImportManager;
    }

    /**
     * Show the form to upload xml file.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('xmlImport.index');
    }

    /**
     * Store the Xml file and start import process.
     *
     * @param XmlUploadRequest $request
     * @return mixed
     */
    public function store(XmlUploadRequest $request)
    {
        $file = $request->file('xml_file');

        if ($this->xmlImportManager->store($file)) {
            $userId = auth()->user()->id;
            $this->xmlImportManager->startImport($file->getClientOriginalName(), $userId, session('org_id'));
        }

        session(['xml_import_status' => 'started']);

        return redirect()->route('activity.index');
    }

    /**
     * Check the Xml Import status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        $completedActivity = $this->xmlImportManager->loadJsonFile('xml_completed_status.json');
        $schemaError       = $this->xmlImportManager->loadJsonFile('schema_error.json');

        list($totalActivities, $currentActivityCount, $failed, $success, $response) = [0, 0, 0, 0, ''];

        if ($schemaError) {
            $response = ['error' => 'true'];
        } elseif ($completedActivity) {
            $totalActivities      = getVal($completedActivity, ['total_activities']);
            $currentActivityCount = getVal($completedActivity, ['current_activity_count']);
            $failed               = getVal($completedActivity, ['failed']);
            $success              = getVal($completedActivity, ['success']);

            $response = ['totalActivities' => $totalActivities, 'currentActivityCount' => $currentActivityCount, 'failed' => $failed, 'success' => $success];
        }

        return response()->json($response);
    }

    /**
     * Check if the import process is complete.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function isCompleted()
    {
        $completedActivity = $this->xmlImportManager->loadJsonFile('xml_completed_status.json');

        $status = ($completedActivity) ? 'incomplete' : 'file not found';

        if ($completedActivity) {
            $totalActivities      = getVal($completedActivity, ['total_activities']);
            $currentActivityCount = getVal($completedActivity, ['current_activity_count']);
            if ($currentActivityCount === $totalActivities) {
                $status = 'completed';
            }
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Complete the Xml Import process.
     */
    public function complete()
    {
        session()->forget('xml_import_status');
        Session::save();

        $this->xmlImportManager->removeTemporaryXmlFolder();
    }

    public function schemaErrors()
    {
        $error = $this->xmlImportManager->loadJsonFile('schema_error.json');
        if ($error) {
            $filename = getVal($error, ['filename']);
            $version  = getVal($error, ['version']);
            $this->xmlImportManager->parseXmlErrors($filename, $version);
            $this->complete();
        }

        return view('xmlImport.schemaError');
    }
}
