<?php namespace App\Tz\Aidstream\Models;

use App\Models\Activity\ActivityDocumentLink;

class DocumentLink extends ActivityDocumentLink
{
    protected $table = 'activity_document_links';
    protected $fillable = ['activity_id', 'document_link'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Project::class, 'activity_id');
    }
}
