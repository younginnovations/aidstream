<?php namespace App\Console\Commands;

use App\Models\Organization\Organization;
use App\Services\Activity\ParticipatingOrganizationManager;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Excel;

/**
 * Class SyncPartnerOrganizations
 * @package App\Console\Commands
 */
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
     * @var null
     */
    protected $rows = null;

    /**
     * @var string
     */
    protected $filename = 'foundapi.csv';

    /**
     * @var Excel
     */
    protected $excel;

    /**
     * Create a new command instance.
     *
     * @param ParticipatingOrganizationManager $participatingOrganizationManager
     * @param DatabaseManager                  $databaseManager
     */
    public function __construct(ParticipatingOrganizationManager $participatingOrganizationManager, DatabaseManager $databaseManager, Excel $excel)
    {
        parent::__construct();
        $this->participatingOrganizationManager = $participatingOrganizationManager;
        $this->databaseManager                  = $databaseManager;
        $this->excel                            = $excel;
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
            $cleanUpNeeded = $this->option('clean');

            $data = $cleanUpNeeded ? $this->excel->load(storage_path($this->filename))->get() : null;

            foreach ($organizations as $organization) {
                $this->databaseManager->beginTransaction();
                foreach ($organization->activities as $activity) {
                    if ($participatingOrganizationData = $this->participatingOrganizationManager->managePartnerOrganizations($activity, null, $data)) {
                        $this->update($activity, $participatingOrganizationData);
                    }
                }
                $this->databaseManager->commit();
                $progress->advance();
            }

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

    /**
     * Update Activity.
     *
     * @param $activity
     * @param $participatingOrganizationData
     */
    protected function update($activity, $participatingOrganizationData)
    {
        $activity->participating_organization = $participatingOrganizationData;
        $activity->activity_workflow = 0;

        $activity->save();
    }
}
