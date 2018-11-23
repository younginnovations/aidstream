<?php namespace App\Np\Contracts;


/**
 * Interface DocumentLinkRepositoryInterface
 * @package App\Np\Contracts
 */
interface NpDocumentLinkRepositoryInterface
{
    /**
     * Returns all documentLinks of provided activity.
     *
     * @param $activityId
     * @return mixed
     */
    public function all($activityId);

    /**
     * Saves the document link data into database.
     *
     * @param array $data
     * @param       $activityId
     * @return mixed
     */
    public function save(array $data, $activityId);

    /**
     * Find the details of the provided id of document link.
     *
     * @param $documentLinkId
     * @return mixed
     */
    public function find($documentLinkId);


    /**
     * Update the details of the document link
     *
     * @param array $data
     * @param       $activityId
     * @return mixed
     */
    public function update(array $data, $activityId);
}
