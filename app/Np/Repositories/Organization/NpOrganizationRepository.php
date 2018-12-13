<?php namespace App\Np\Repositories\Organization;

use App\Np\Contracts\NpOrganizationRepositoryInterface;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationLocation;
use App\Models\SystemVersion;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrganisationRepository
 * @package App\Np\Repositories\Organisation
 */
class NpOrganizationRepository implements NpOrganizationRepositoryInterface
{
    /**
     * @var Organization
     */
    protected $organisation;

    /**
     * @var OrganizationLocation
     */
    protected $organizationLocation;

    protected $systemVersion;

    /**
     * OrganisationRepository constructor.
     * @param Organization  $organisation
     * @param OrganizationLocation $organizationLocation
     * @param SystemVersion $systemVersion
     */
    public function __construct(
        Organization $organisation,
        OrganizationLocation $organizationLocation,
        SystemVersion $systemVersion)
    {
        $this->organisation             = $organisation;
        $this->organizationLocation     = $organizationLocation;
        $this->systemVersion            = $systemVersion;
    }

    /**
     * Get all the Organizations of a municipality_id
     *
     * @param $id
     * @return Array $organization
     */
    public function all($id)
    {
        $organizationIds = $this->organizationLocation->where('municipality_id', $id)->pluck('organization_id');

        foreach($organizationIds as $orgId){
            $organizations[] = $this->find($orgId);
        }
        return $organizations;
    }

    /**
     * Find an Organization by its id.
     *
     * @param $id
     * @return Organization
     */
    public function find($id)
    {
        return $this->organisation->findOrFail($id);
    }

    /**
     * Save the Organization data into the database.
     *
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        return $this->organisation->create($data);
    }

    public function update($id, array $data)
    {
        return $this->organisation->updateorCreate(['id' => $id], $data);
    }
}
