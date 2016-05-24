<?php namespace App\Tz\Aidstream\Models;

use App\Models\Activity\ActivityResult;

class Result extends ActivityResult
{
    protected $table = 'activity_results';

    protected $fillable = [
        'activity_id',
        'result'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Project::class, 'activity_id');
    }
}
