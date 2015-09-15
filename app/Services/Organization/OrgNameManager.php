<?php
namespace app\Services\Organization;

use App\Core\Version;
use App;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class OrgNameManager
{
    protected $repo;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var Log
     */
    private $log;
    /**
     * @var Version
     */
    private $version;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    public function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->repo    = $version->getOrganizationElement()->getName()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);
    }

    public function getOrganizationNameData($id)
    {
        return $this->repo->getOrganizationNameData($id);
    }

    /**
     * write brief description
     * @param $input
     * @param $organization
     * @return bool
     */
    public function update($input, $organization)
    {
        try {
            $this->repo->update($input, $organization);
            $this->log->info(
                'Organization Name Updated',
                ['for ' => $organization['name']]
            );
            $this->log->activity(
                "organization.name_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Name could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationName' => $input,
                    'trace' => $exception->getTraceAsString()
                ]
            );
        }
        return false;
    }
    /**
     * @param $input
     * @param $organizationData
     */
    public function getStatus($organization_id)
    {
        return $this->repo->getStatus($organization_id);
    }

    /**
     * @param $input
     * @param $organizationData
     */
    public function updateStatus($input, $organizationData)
    {
        $this->repo->updateStatus($input, $organizationData);
    }

    /**
     * @param $organization_id
     */
    public function resetStatus($organization_id)
    {
        $this->repo->resetStatus($organization_id);
    }
}
