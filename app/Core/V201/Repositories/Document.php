<?php namespace App\Core\V201\Repositories;

use App\Models\Document as DocumentModal;

/**
 * Class Document
 * @package App\Core\V201\Repositories
 */
class Document
{
    /**
     * @var DocumentModal
     */
    protected $document;

    /**
     * @param DocumentModal $document
     */
    function __construct(DocumentModal $document)
    {
        $this->document = $document;
    }

    /**
     * return all organization documents
     * @param $orgId
     * @return mixed
     */
    public function getDocuments($orgId)
    {
        return $this->document->where('org_id', $orgId)->where('filename', '<>', 'NULL')->get();
    }

    /**
     * return particular document
     * @param $orgId
     * @param $url
     * @param $filename
     * @return static
     */
    public function getDocument($orgId, $url, $filename)
    {
        $data = ['url' => $url, 'org_id' => $orgId];
        !$filename ?: $data['filename'] = $filename;

        return $this->document->firstOrNew($data);
    }

    /**
     * return document by id
     * @param $id
     * @return static
     */
    public function getDocumentById($id)
    {
        return $this->document->find($id);
    }

    /**
     * save document
     * @param DocumentModal $document
     * @return bool
     */
    public function store(DocumentModal $document)
    {
        return $document->save();
    }
}
