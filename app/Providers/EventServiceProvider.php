<?php namespace App\Providers;

use App\Services\CsvImporter\Events\TransactionCsvWasUploaded;
use App\Services\CsvImporter\Listeners\TransactionCsvUpload;
use App\Services\XmlImporter\Events\XmlWasUploaded;
use App\Services\XmlImporter\Listeners\XmlUpload;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Services\CsvImporter\Events\ActivityCsvWasUploaded' => [
            'App\Services\CsvImporter\Listeners\ActivityCsvUpload',
        ],
        'App\Services\CsvImporter\Events\ResultCsvWasUploaded'   => [
            'App\Services\CsvImporter\Listeners\ResultCsvUpload',
        ],
        XmlWasUploaded::class                                    => [
            XmlUpload::class
        ],
        TransactionCsvWasUploaded::class                         => [
            TransactionCsvUpload::class
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }

}
