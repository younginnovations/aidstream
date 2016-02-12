<?php namespace App\Migration\Migrator;

use App\Migration\Entities\OrganizationData;
use App\Models\Organization\OrganizationData as OrganizationDataModel;
use App\Migration\Migrator\Contract\MigratorContract;

class OrganizationDataMigrator implements MigratorContract
{
    /**
     * @var OrganizationData
     */
    protected $organization;

    /**
     * @var OrganizationDataModel
     */
    protected $organizationDataModel;

    public function __construct(OrganizationData $organization, OrganizationDataModel $organizationDataModel)
    {
        $this->organization          = $organization;
        $this->organizationDataModel = $organizationDataModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $organizationDataDetails = $this->organization->getData($accountIds);

        foreach ($organizationDataDetails as $organizationDetail) {
            foreach ($organizationDetail as $detail) {
                $newOrganizationData = $this->organizationDataModel->newInstance($detail);

                if (!$newOrganizationData->save()) {
                    return 'Error during OrganizationData table migration.';
                }
            }
        }

        return 'OrganizationData table migrated.';
    }
}
