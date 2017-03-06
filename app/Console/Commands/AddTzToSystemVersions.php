<?php namespace App\Console\Commands;

use App\Models\SystemVersion;
use Illuminate\Console\Command;

class AddTzToSystemVersions extends Command
{
    /**
     * Command signature.
     * @var string
     */
    protected $signature = 'enable-tz';

    /**
     * @var SystemVersion
     */
    protected $systemVersion;

    public function __construct(SystemVersion $systemVersion)
    {
        parent::__construct();
        $this->systemVersion = $systemVersion;
    }

    public function handle()
    {
        $tzVersion = $this->systemVersion->where('system_version', '=', 'Tz')->get();
        if ($tzVersion->isEmpty()) {
            $this->systemVersion->create(['system_version' => 'Tz']);

            $this->info('Tz Version enabled.');
        } else {
            $this->info('Tz Version has already been enabled.');
        }
    }
}
