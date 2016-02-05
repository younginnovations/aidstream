<?php namespace App\Http\Controllers\Complete;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\DocumentManager;

/**
 * Class DocumentController
 * @package App\Http\Controllers\Complete
 */
class DocumentController extends Controller
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;
    /**
     * @var mixed
     */
    protected $orgId;

    /**
     * @param DocumentManager $documentManager
     */
    function __construct(DocumentManager $documentManager)
    {
        $this->middleware('auth');
        $this->documentManager = $documentManager;
        $this->orgId           = session('org_id');
    }

    /**
     * list organization documents
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $documents = $this->getDocuments();

        return view('documents', compact('documents'));
    }

    /**
     * save document
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $file     = $request->file('file');
            $filename = $file->getClientOriginalName();
            $url      = url(sprintf('uploads/files/documents/%s/%s', $this->orgId, $filename));
            $document = $this->documentManager->getDocument($this->orgId, $url, $filename);
            if ($document->exists) {
                return ['status' => 'danger', 'message' => 'Document already exists.'];
            }
            Storage::put(sprintf('documents/%s/%s', $this->orgId, $filename), File::get($file));
            $this->documentManager->store($document);
        } catch (\Exception $e) {
            return ['status' => 'danger', 'message' => 'Failed to upload Document. Error: ' . $e->getMessage()];
        }

        return ['status' => 'success', 'message' => 'Uploaded Successfully.', 'data' => $this->getDocuments()];
    }

    /**
     * return organization documents
     * @return mixed
     */
    public function getDocuments()
    {
        $documents = $this->documentManager->getDocuments($this->orgId);

        return $documents->toArray();
    }
}
