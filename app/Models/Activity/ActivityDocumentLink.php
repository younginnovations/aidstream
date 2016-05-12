<?php namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityDocumentLink
 * @package App\Models\Activity
 */
class ActivityDocumentLink extends Model
{
    protected $fillable = [
        'activity_id',
        'document_link'
    ];

    protected $casts = [
        'document_link' => 'json'
    ];

    /**
     * document link belongs to activity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity\Activity');
    }
}
