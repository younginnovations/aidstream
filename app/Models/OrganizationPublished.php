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
    protected $fillable = ['filename', 'published_to_register', 'organization_id', 'published_org_data'];

    protected $casts = ['published_org_data' => 'json'];

}
