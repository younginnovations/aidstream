<?php namespace App\Tz\Aidstream\Repositories\DocumentLink;

interface DocumentLinkRepositoryInterface
{
    
    public function create($documentLink);
    
    public function findByProjectId($projectId);
    
    public function update($projectId, $request);
}
