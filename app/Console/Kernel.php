<?php namespace App\Console;

use App\Console\Commands\AddTzToSystemVersions;
use App\Console\Commands\SyncPartnerOrganizations;
use App\Console\Commands\RegistrationAgencyData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Inspire',
        AddTzToSystemVersions::class,
        SyncPartnerOrganizations::class,
        Commands\RegistrationAgencyData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('backup:run')->daily(7, 14);
        $schedule->command('db:backup --database=pgsql --destination=sftp --destinationPath=`date +\%Y/%d-%m-%Y` --compression=gzip')->daily(7, 14);
    }

}
