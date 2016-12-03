<?php namespace App\Models\PerfectViewer;

use Illuminate\Database\Eloquent\Model;
/**
 * Class ActivitySnapshot
 * @package App\Models\PerfectActivity
 */
class ActivitySnapshot extends Model
{
    /**
     * @var string
     */
    protected $table = 'activity_snapshots';

    /**
     * @var array
     */
    protected $fillable = ['org_id', 'activity_id', 'published_data', 'activity_in_registry', 'filename'];

    /**
     * @var array
     */
    protected $casts = [
        'published_data' => 'json'
    ];

}
