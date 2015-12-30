<?php namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\OrganizationData;
use Illuminate\Contracts\Auth\Guard;
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

    /**
     * write brief description
     * @param $id
     * @return model
     */
    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);
    }

    /**
     * write brief description
     * @param $id
     * @return model
     */
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
    public function update(array $input, OrganizationData $organization)
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
                    'trace'            => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

}
