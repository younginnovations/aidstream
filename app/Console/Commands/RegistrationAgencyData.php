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
        $content = $this->getRegistrationAgencyData();
        try{
            if($content['status'] == 200){
                File::put( $newFilePath, $content['data']);
                File::delete(public_path('data/org.json'));
                rename($newFilePath, $currentFilePath);

                echo "Imported Successfully";
            } else {
                throw new \Exception("Import Failed");
            }
            
        }
        catch(exception $e){
            echo $e->getMessage();
        }
    }

    public function getRegistrationAgencyData()
    {
        $url = 'http://org-id.guide/download.json';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $content = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        return [
            'data' => $content,
            'status' => $statusCode
        ];
    }
}
