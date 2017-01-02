<?php namespace App\Lite\Contracts;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrganizationRepositoryInterface
 * @package App\Lite\Contracts
 */
interface OrganisationRepositoryInterface
{
    /**
     * Get all the Organisations of the current Organization.
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

    /**
     * Upgrade AidStream to Core.
     *
     * @param Organization $organization
     * @return bool|int
     */
    public function upgradeSystem(Organization $organization);
}
