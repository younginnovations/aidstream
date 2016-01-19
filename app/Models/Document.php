<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Document
 * @package App\Models
 */
class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = ['filename', 'url', 'activities', 'org_id'];
    protected $casts = ['activities' => 'json'];

    /**
     * document belongs to organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function organization()
    {
        return $this->belongsTo('App\Models\Organization\Organization', 'org_id');
    }
}
