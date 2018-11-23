<?php namespace App\Http\Controllers\Np\PublishedFiles;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Lite\LiteController;
use App\Np\Services\PublishedFiles\PublishedFilesService;
use Illuminate\Support\Facades\Session;

/**
 * Class PublishedFilesController
 * @package App\Http\Controllers\Np\PublishedFiles
 */
class PublishedFilesController extends LiteController
{
    /**
     * @var PublishedFilesService
     */
    protected $publishedFilesService;


    /**
     * PublishedFilesController constructor.
     * @param PublishedFilesService $publishedFilesService
     */
    public function __construct(PublishedFilesService $publishedFilesService)
    {
        $this->middleware('auth');
        $this->publishedFilesService = $publishedFilesService;
    }

    /**
     * List all the published files for the current Organization.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $publishedFiles = $this->publishedFilesService->all();

        return view('np.publishedFiles.index', compact('publishedFiles'));
    }

    /**
     * Delete an XML file.
     *
     * @param         $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $file = $this->getActivityPublishedFile($id);

        if (Gate::denies('ownership', $file)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $file);

        if (Session::has('publisherIdChange') || isPublisherIdBeingChanged()) {
            $response = ['type' => 'success', 'code' => ['message', ['message' => trans('success.publisher_id_changing')]]];

            return redirect()->to('publishing-settings')->withResponse($response);
        }

        if (!$this->publishedFilesService->delete($id)) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => trans('lite/global.delete_unsuccessful', ['type' => 'File'])]]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => trans('lite/global.deleted_successfully', ['type' => 'File'])]]];
        }

        return redirect()->back()->withResponse($response);
    }

    /**
     * Publish multiple files at once.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulkPublish(Request $request)
    {
        $organization = auth()->user()->organization;

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('publish_activity', $organization);

        if (!$request->get('activity_files')) {
            return redirect()->back()->withResponse(
                [
                    'type' => 'warning',
                    'code' => ['message', ['message' => trans('error.select_activity_xml_files_to_be_published')]]
                ]
            );
        }

        if (!$this->publishedFilesService->publish($request->except('_token'))) {
            return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['message', ['message' => trans('lite/global.bulk_publish_unsuccessful')]]]);
        }

        return redirect()->back()->withResponse(['type' => 'success', 'code' => ['message', ['message' => trans('lite/global.bulk_publish_successful')]]]);
    }
}
