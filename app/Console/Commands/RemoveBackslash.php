<?php

namespace App\Console\Commands;

use App\Models\Organization\Organization;
use App\Models\PerfectViewer\OrganizationSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveBackslash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:backslash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var OrganizationSnapshot
     */
    private $organizationSnapshot;
    /**
     * @var Organization
     */
    private $organization;

    /**
     * Create a new command instance.
     * @param OrganizationSnapshot $organizationSnapshot
     * @param Organization         $organization
     */
    public function __construct(OrganizationSnapshot $organizationSnapshot, Organization $organization)
    {
        parent::__construct();
        $this->organizationSnapshot = $organizationSnapshot;
        $this->organization         = $organization;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->fixOrgSlug();
        $this->fixOrgName();
    }

    protected function fixOrgSlug()
    {
        $organisations = DB::table('organization_snapshots')->get();
        foreach ($organisations as $index => $value) {
            $org_slug     = str_replace('/', '-', $value->org_slug);
            $organisation = $this->organizationSnapshot->findOrFail($value->id);
            $organisation->update(['org_slug' => $org_slug]);
            dump($value->id, $value->org_slug, 'updated');
        }
    }

    protected function fixOrgName()
    {
        $organisations = DB::table('organizations')->get();

        foreach ($organisations as $index => $value) {
            if ($value->reporting_org) {
                if (strlen(getVal(json_decode($value->reporting_org, true), [0, 'narrative', 0, 'narrative'], '')) < 255) {
                    $name                                          = str_replace('\\', '', $value->name);
                    $reporting_org                                 = str_replace('\\', '', json_decode($value->reporting_org, true));
                    $reporting_org[0]['narrative'][0]['narrative'] = str_replace('\\', '', getVal(json_decode($value->reporting_org, true), [0, 'narrative', 0, 'narrative'], ''));
                    $organisation                                  = $this->organization->findOrFail($value->id);
                    $organisation->update(['reporting_org' => $reporting_org, 'name' => $name]);
                    dump($value->id, getVal(json_decode($value->reporting_org, true), [0, 'narrative', 0, 'narrative'], ''), 'updated');
                }
            }
        }
    }
}
