<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RegistrationAgencyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:regAgency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add regsistration agency';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currentFilePath    = public_path('data/org.json');
        $newFilePath        = public_path('data/new-org.json');
        
        try{
            $regAgency      = file_get_contents('http://org-id.guide/download.json');
            File::put( $newFilePath, $regAgency);
            File::delete(public_path('data/org.json'));
            rename($newFilePath, $currentFilePath);
        }

        catch(exception $e){
            Log::info('Error while importing json file');
        }
    }
}
