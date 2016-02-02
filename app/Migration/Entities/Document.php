<?php namespace App\Migration\Entities;

use App\Migration\MigrateDocuments;

/**
 * Class Document
 * @package App\Migration\Entities
 */
class Document
{
    /**
     * @var MigrateDocuments
     */
    protected $document;

    /**
     * Document constructor.
     * @param MigrateDocuments $document
     */
    public function __construct(MigrateDocuments $document)
    {
        $this->document = $document;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $orgIds  = ['2', '100', '9']; // get organizationIds //
        $docData = [];

        foreach ($orgIds as $id) {
            $docData[] = $this->document->docDataFetch($id);
        }

        return $docData;
    }
}
