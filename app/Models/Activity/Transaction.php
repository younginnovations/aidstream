<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @package App\Models\Activity
 */
class Transaction extends Model
{
    protected $table = 'activity_transactions';
    protected $fillable = ['activity_id', 'transaction'];
    protected $casts = ['transaction' => 'json'];

    /**
     * transaction belongs to activity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity\Activity');
    }

    /**
     * get transactions
     * @return mixed
     */
    public function  getTransaction()
    {
        return $this->transaction;
    }
}