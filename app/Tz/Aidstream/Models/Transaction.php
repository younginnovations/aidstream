<?php namespace App\Tz\Aidstream\Models;

use App\Models\Activity\Transaction as ActivityTransactions;

class Transaction extends ActivityTransactions
{
    protected $table = 'activity_transactions';
    protected $fillable = ['activity_id', 'transaction'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Project::class, 'project_id', 'activity_id');
    }
}
