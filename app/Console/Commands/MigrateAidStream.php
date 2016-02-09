<?php namespace App\Console\Commands;

use App\Migration\Migrator\ActivityMigrator;
use App\Migration\Entities\Activity;
use App\Migration\Migrator\DocumentMigrator;
use App\Migration\Migrator\OrganizationDataMigrator;
use App\Migration\Migrator\OrganizationMigrator;
use App\Migration\Migrator\SettingsMigrator;
use App\Migration\Migrator\UserMigrator;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MigrateAidStream
 * @package App\Console\Commands
 */
class MigrateAidStream extends Command
{
    /**
     * @var ActivityMigrator
     */
    protected $activityMigrator;

    /**
     * @var UserMigrator
     */
    protected $userMigrator;

    /**
     * @var OrganizationMigrator
     */
    protected $organizationMigrator;

    /**
     * @var DocumentMigrator
     */
    protected $documentMigrator;

    /**
     * @var SettingsMigrator
     */
    protected $settingsMigrator;

    /**
     * @var Activity
     */
    protected $activity;

    /**
     * Name for the command.
     *
     * @var string
     */
    protected $name = 'migrate-aidstream';

    /**
     * Description of the command.
     *
     * @var string
     */
    protected $description = 'Migrate Aidstream';

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var OrganizationDataMigrator
     */
    protected $organizationDataMigrator;

    /**
     * MigrateAidStream constructor.
     * @param ActivityMigrator         $activityMigrator
     * @param UserMigrator             $userMigrator
     * @param OrganizationMigrator     $organizationMigrator
     * @param DocumentMigrator         $documentMigrator
     * @param SettingsMigrator         $settingsMigrator
     * @param OrganizationDataMigrator $organizationDataMigrator
     * @param DatabaseManager          $databaseManager
     */
    public function __construct(
        ActivityMigrator $activityMigrator,
        UserMigrator $userMigrator,
        OrganizationMigrator $organizationMigrator,
        DocumentMigrator $documentMigrator,
        SettingsMigrator $settingsMigrator,
        OrganizationDataMigrator $organizationDataMigrator,
        DatabaseManager $databaseManager
    ) {
        parent::__construct();
        $this->activityMigrator         = $activityMigrator;
        $this->userMigrator             = $userMigrator;
        $this->organizationMigrator     = $organizationMigrator;
        $this->documentMigrator         = $documentMigrator;
        $this->settingsMigrator         = $settingsMigrator;
        $this->databaseManager          = $databaseManager;
        $this->organizationDataMigrator = $organizationDataMigrator;
    }

    /**
     * Fire the artisan command.
     */
    public function fire()
    {
        try {
            $orgId    = $this->option('orgId') ? [$this->option('orgId')] : null;
            $argument = $this->argument('table');

            $this->info('Running the migrations');

            $this->databaseManager->beginTransaction();
            $this->beginMigration($argument);
            $this->databaseManager->commit();
        } catch (Exception $exception) {
            $this->rollback($exception);
        }
    }

    /**
     * Migrate all tables' data into the new database.
     */
    protected function migrateAll()
    {
        $response   = [];
        $response[] = $this->organizationMigrator->migrate();
        $response[] = $this->userMigrator->migrate();
        $response[] = $this->documentMigrator->migrate();
        $response[] = $this->settingsMigrator->migrate();
        $response[] = $this->activityMigrator->migrate();
        $response[] = $this->organizationDataMigrator->migrate();

        return implode("\n", $response);
    }

    /**
     * Migrate Users table data into the new database.
     * @return mixed|string
     */
    protected function migrateUser()
    {
        return $this->userMigrator->migrate();
    }

    /**
     * Migrate Organizations table data into the new database.
     * @return mixed|string
     */
    protected function migrateOrganization()
    {
        return $this->organizationMigrator->migrate();
    }

    /**
     * Migrate Documents table data into the new database.
     * @return string
     */
    protected function migrateDocument()
    {
        return $this->documentMigrator->migrate();
    }

    /**
     * Migrate Settings table data into the new database.
     * @return string
     */
    protected function migrateSettings()
    {
        return $this->settingsMigrator->migrate();
    }

    /**
     * Migrate Activities table data into the new database.
     * @return string
     */
    protected function migrateActivity()
    {
        return $this->activityMigrator->migrate();
    }

    /**
     * Migrate OrganizationData table data into the new database.
     * @return string
     */
    protected function migrateOrganizationData()
    {
        return $this->organizationDataMigrator->migrate();
    }

    /**
     * Get the command options
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['orgId', null, InputOption::VALUE_OPTIONAL, 'Run the migration for an Organization with a specific orgId', null]
        ];
    }

    /**
     * Get the command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['table', InputArgument::REQUIRED, "The table you want to migrate. 'all' if all tables are to be migrated."],
        ];
    }

    /**
     * Start the migrations.
     * @param $argument
     */
    protected function beginMigration($argument)
    {
        if ($argument == 'all') {
            $this->info($this->migrateAll());
        } else {
            $method = sprintf('migrate%s', $argument);
            $this->triggerSpecific($method);
        }
    }

    /**
     * Run migrations for a specific table.
     * @param $method
     */
    protected function triggerSpecific($method)
    {
        if (!method_exists($this, $method)) {
            $this->error('The table you specified does not exist');
        } else {
            $this->info($this->$method());
        }
    }

    /**
     * Roll the migrations back.
     * @param $exception
     */
    protected function rollback($exception)
    {
        $this->databaseManager->rollback();
        $this->warn('Rolling the migrations back.');
        $this->error($exception->getMessage());
    }
}
