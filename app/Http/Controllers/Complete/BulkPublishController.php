<?php namespace App\Http\Controllers\Complete;

use App\Services\BulkPublishManager;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class BulkPublishController
 * @package App\Http\Controllers\Complete
 */
class BulkPublishController extends Controller
{
    /**
     * @var BulkPublishManager
     */
    protected $bulkPublishManager;

    /**
     * BulkPublishController constructor.
     * @param BulkPublishManager $bulkPublishManager
     */
    public function __construct(BulkPublishManager $bulkPublishManager)
    {
        $this->middleware('auth');
        $this->middleware('auth.systemVersion');
        $this->bulkPublishManager = $bulkPublishManager;
    }

    /**
     * Bulk publish the selected activities to the registry.
     *
     * @param Request $request
     * @return mixed
     */
    public function activityBulkPublishToRegistry(Request $request)
    {
        $files = $request->get('activity_files');

        if (is_null($files)) {
            $response = [
                'type' => 'warning',
                'code' => ['message', ['message' => trans('error.select_activity_xml_files_to_be_published')]],
            ];

            return redirect()->back()->withResponse($response);
        }

        $result = $this->bulkPublishManager->bulkPublishActivity(session('org_id'), $files);

        if (getVal($result, ['status']) == false) {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => getVal($result, ['message'])]]]);
        }

        return redirect()->back()->withResponse(['type' => 'success', 'code' => ['publish_registry_publish', ['name' => '']]]);

    }

    /**
     * Publish the selected organisation data to iati registry
     *
     * @param Request $request
     * @return mixed
     */
    public function orgBulkPublishToRegistry(Request $request)
    {
        $files = $request->get('org_files');

        if (is_null($files)) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => trans('success.select_org_xml_file')]]];

            return redirect()->back()->withResponse($response);
        }

        $result = $this->bulkPublishManager->bulkPublishOrganization(session('org_id'), $files);

        if (getVal($result, ['status']) == false) {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => getVal($result, ['message'])]]]);
        }

        return redirect()->back()->withResponse(['type' => 'success', 'code' => ['publish_registry_organization', ['name' => '']]]);
    }
}

