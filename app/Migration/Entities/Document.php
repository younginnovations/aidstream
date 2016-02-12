<?php namespace App\Migration\Entities;

use App\Migration\MigrateDocuments;
use App\Migration\Migrator\Data\DocumentQuery;

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
     * @var DocumentQuery
     */
    protected $documentQuery;

    /**
     * Document constructor.
     * @param MigrateDocuments $document
     */
    public function __construct(MigrateDocuments $document, DocumentQuery $documentQuery)
    {
        $this->document      = $document;
        $this->documentQuery = $documentQuery;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->documentQuery->executeFor($accountIds);
    }
}
