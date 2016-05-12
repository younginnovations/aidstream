<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class OrganizationPublished
 * @package App\Models
 */
class OrganizationPublished extends Model
{
    protected $table = "organization_published";
    protected $fillable = ['filename', 'published_to_register', 'organization_id'];

    /** Returns status of organization data that has been published
     * @param $orgId
     * @return mixed
     */
    public function statusOfOrganizationDataPublished($orgId)
    {
        return DB::table('organizations')
                 ->join('organization_published', 'organization_published.organization_id', '=', 'organizations.id')
                 ->select(DB::raw('count(organization_published.published_to_register) as organizationDataPublished'))
                 ->where('organizations.id', $orgId)
                 ->where('organization_published.published_to_register', 1)
                 ->get();
    }
}
