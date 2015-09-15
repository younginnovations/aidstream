<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\Writer;

class NameRepository
{
    /**
     * @var OrganizationData
     */
    private $org;
    /**
     * @var DB
     */
    private $database;
    /**
     * @var Log
     */
    private $log;

    /**
     * @param OrganizationData $org
     * @param DB $database
     * @param Log $log
     */
    function __construct(OrganizationData $org, DatabaseManager $database, Writer $log)
    {
        $this->org = $org;
        $this->database = $database;
        $this->log = $log;
    }

    /**
     * @param $input
     * @param $organizationData
     */
    public function update($input, $organizationData)
    {
        try{
            $this->database->beginTransaction();
            $organizationData->name = json_encode($input['name']);
            $organizationData->save();
            $this->database->commit();
            $this->log->info('Organization Name Updated',
                ['for ' => $organizationData['name']]);
        } catch (Exception $exception) {
            $this->database->rollback();

            $this->log->error(
                sprintf('Organization Name could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationName' => $input,
                    'trace' => $exception->getTraceAsString()
                ]
            );
        }
    }

    public function getOrganizationData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first();
    }

    public function getOrganizationNameData($organization_id)
    {
        return json_decode($this->org->where('organization_id', $organization_id)->first()->name, true);
    }

}