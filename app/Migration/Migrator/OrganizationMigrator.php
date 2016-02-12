<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Organization;
use App\Migration\Migrator\Contract\MigratorContract;
use App\Models\Organization\Organization as OrganizationModel;

/**
 * Class OrganizationMigrator
 * @package App\Migration\Migrator
 */
class OrganizationMigrator implements MigratorContract
{
    /**
     * @var OrganizationModel
     */
    protected $organizationModel;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * OrganizationMigrator constructor.
     * @param Organization      $organization
     * @param OrganizationModel $organizationModel
     */
    public function __construct(Organization $organization, OrganizationModel $organizationModel)
    {
        $this->organization      = $organization;
        $this->organizationModel = $organizationModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $organizationDetail = $this->organization->getData($accountIds);

        foreach ($organizationDetail as $detail) {
            $organization = $this->organizationModel->newInstance($detail);

            if (!$organization->save()) {
                return 'Error during Organization table migration.';
            }
        }

        return 'Organizations table migrated.';
    }
}
