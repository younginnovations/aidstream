<?php namespace App\Lite\Contracts;

use App\Models\ActivityPublished;
use Illuminate\Database\Eloquent\Collection;


/**
 * Interface PublishedFilesRepositoryInterface
 * @package App\Lite\Contracts
 */
interface PublishedFilesRepositoryInterface
{
    /**
     * Get the all XMl files.
     *
     * @return Collection
     */
    public function all();

    /**
     * Delete an XML file.
     *
     * @param $id
     * @return bool|null
     */
    public function delete($id);

    /**
     * Get the ActivityPublished model with a specific fileId.
     *
     * @param $fileId
     * @return ActivityPublished
     */
    public function findActivity($fileId);
}
