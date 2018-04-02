<?php namespace App\Http\Controllers\Complete;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
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
     * Allowed extensions for documents.
     * @var array
     */
    protected $allowedExtensions = ['doc', 'docx', 'pdf', 'jpeg', 'jpg', 'ppt', 'pptx', 'png', 'xls', 'bmp', 'xlsx'];

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
            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, $this->allowedExtensions)) {
                return ['status' => 'warning', 'message' => trans('error.file_type_not_allowed')];
            }

            $filename  = str_replace(' ', '-', preg_replace('/\s+/', ' ', $file->getClientOriginalName()));
            $extension = substr($filename, stripos($filename, '.'));
            $filename  = sprintf('%s-%s%s', substr($filename, 0, stripos($filename, '.')), date('Ymdhms'), $extension);
            $url       = url(sprintf('files/documents/%s', $filename));
            $document  = $this->documentManager->getDocument($this->orgId, $url, $filename);
            if ($document->exists) {
                return ['status' => 'danger', 'message' => trans('error.document_already_exists')];
            }
            Storage::put(sprintf('%s/%s', 'documents', $filename), File::get($file));
            $this->documentManager->store($document);
        } catch (\Exception $e) {
            return ['status' => 'danger', 'message' => trans('error.failed_to_upload_document') . ' ' . trans('global.error') . ': ' . $e->getMessage()];
        }

        return ['status' => 'success', 'message' => trans('success.uploaded_successfully'), 'data' => $this->getDocuments()];
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

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $document = $this->documentManager->getDocumentById($id);

        if (Gate::denies('ownership', $document)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $response = ($document->delete()) ? ['type' => 'success', 'code' => ['deleted', ['name' => trans('global.document')]]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => trans('global.document')]]
        ];

        return redirect()->back()->withResponse($response);
    }
}
