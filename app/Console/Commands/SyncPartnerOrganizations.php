<?php

namespace App\Console\Commands;

use App\Models\Organization\Organization;
use App\Services\Activity\ParticipatingOrganizationManager;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use function PHPSTORM_META\type;

class SyncPartnerOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partners:create {--clean} {--trace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var ParticipatingOrganizationManager
     */
    protected $participatingOrganizationManager;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ParticipatingOrganizationManager $participatingOrganizationManager, DatabaseManager $databaseManager)
    {
        parent::__construct();
        $this->participatingOrganizationManager = $participatingOrganizationManager;
        $this->databaseManager                  = $databaseManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $organizations = Organization::all();
            $progress      = $this->output->createProgressBar($organizations->count());

            $this->databaseManager->beginTransaction();

            foreach ($organizations as $organization) {
                foreach ($organization->activities as $activity) {
                    $this->participatingOrganizationManager->managePartnerOrganizations($activity);
                }
                $progress->advance();
            }
            $this->databaseManager->commit();

            $progress->finish();

            $this->info('Partner Organizations sync complete.');
        } catch (\Exception $exception) {
            $this->databaseManager->rollback();

            if ($this->option('trace')) {
                $this->error(sprintf("Error: %s", $exception->getMessage()));
                $this->error(sprintf("Trace: \n%s", $exception->getTraceAsString()));
            } else {
                $this->error(sprintf("Error: %s", $exception->getMessage()));
            }
        }
    }
}
