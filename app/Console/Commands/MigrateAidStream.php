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
            $argument = $this->argument('table');
            $country  = $this->option('country');

            $this->info('Running the migrations');

            $this->databaseManager->beginTransaction();
            $this->beginMigration($argument, $country);

            $this->databaseManager->commit();
        } catch (Exception $exception) {
            $this->rollback($exception);
        }
    }

    /**
     * Migrate all tables' data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateAll(array $accountIds)
    {
        $response   = [];
        $response[] = $this->organizationMigrator->migrate($accountIds);
        $response[] = $this->userMigrator->migrate($accountIds);
        $response[] = $this->documentMigrator->migrate($accountIds);
        $response[] = $this->settingsMigrator->migrate($accountIds);
        $response[] = $this->activityMigrator->migrate($accountIds);
        $response[] = $this->organizationDataMigrator->migrate($accountIds);

        return implode("\n", $response);
    }

    /**
     * Migrate Users table data into the new database.
     * @param array $accountIds
     * @return mixed|string
     */
    protected function migrateUser(array $accountIds)
    {
        return $this->userMigrator->migrate($accountIds);
    }

    /**
     * Migrate Organizations table data into the new database.
     * @param array $accountIds
     * @return mixed|string
     */
    protected function migrateOrganization(array $accountIds)
    {
        return $this->organizationMigrator->migrate($accountIds);
    }

    /**
     * Migrate Documents table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateDocument(array $accountIds)
    {
        return $this->documentMigrator->migrate($accountIds);
    }

    /**
     * Migrate Settings table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateSettings(array $accountIds)
    {
        return $this->settingsMigrator->migrate($accountIds);
    }

    /**
     * Migrate Activities table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateActivity(array $accountIds)
    {
        return $this->activityMigrator->migrate($accountIds);
    }

    /**
     * Migrate OrganizationData table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateOrganizationData(array $accountIds)
    {
        return $this->organizationDataMigrator->migrate($accountIds);
    }

    /**
     * Get the command options
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['country', null, InputOption::VALUE_OPTIONAL, 'Run the migration for an Organization of a specific country.', null]
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
     * @param $country
     */
    protected function beginMigration($argument, $country)
    {
        $accountIds = $this->getAccountIdsFor($country);

        if ($argument == 'all') {
            $this->info($this->migrateAll($accountIds));
        } else {
            $method = sprintf('migrate%s', $argument);
            $this->triggerSpecific($method, $accountIds);
        }
    }

    /**
     * Run migrations for a specific table.
     * @param       $method
     * @param array $accountIds
     */
    protected function triggerSpecific($method, array $accountIds)
    {
        if (!method_exists($this, $method)) {
            $this->error('The table you specified does not exist');
        } else {
            $this->info($this->$method($accountIds));
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

    /**
     * Get accountIds of a specific country from the old database.
     * @param null $country
     * @return array
     */
    protected function getAccountIdsFor($country = null)
    {
        $accountIds = [];

        $builder = $this->databaseManager->connection('mysql')->table('account')->select('id');

        if ($country) {
            $countryName = implode(' ', explode('-', $country));
            $builder->where('address', 'like', '%' . $countryName . '%');
        }

        $accounts = $builder->get();

        array_walk(
            $accounts,
            function ($value, $index) use (&$accountIds) {
                $accountIds[] = $value->id;
            }
        );

        return $accountIds;

    }
}
