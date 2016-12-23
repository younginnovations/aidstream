<?php

namespace App\Models\PerfectViewer;

use Illuminate\Database\Eloquent\Model;

class OrganizationSnapshot extends Model
{
    protected $table = 'organization_snapshots';

    protected $fillable = ['org_id', 'transaction_totals', 'published_to_registry', 'org_slug'];

    protected $casts = [
        'transaction_totals' => 'json',
    ];
}
