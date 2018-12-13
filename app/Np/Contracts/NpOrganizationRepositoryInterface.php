<?php namespace App\Np\Contracts;

use App\Models\Organization\OrganizationLocation;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrganizationRepositoryInterface
 * @package App\Np\Contracts
 */
interface NpOrganizationRepositoryInterface
{
    /**
     * Get all the Organisations of a municipality_id.
     *
     * @param $id
     * @return Collection
     */
    public function all($id);

    /**
     * Find an Organization by its id.
     *
     * @param $id
     * @return Organization
     */
    public function find($id);

    /**
     * Save the Organization data into the database.
     *
     * @param array $data
     * @return mixed
     */
    public function save(array $data);

    /**
     * @param       $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);


}
