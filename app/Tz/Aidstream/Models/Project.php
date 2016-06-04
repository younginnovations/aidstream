<?php namespace App\Tz\Aidstream\Models;

use App\Models\Activity\Activity;

/**
 * Class Project
 */
class Project extends Activity
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'activity_data';

    /**
     * Fillable property for mass assignment.
     * @var array
     */
    protected $fillable = [
        'identifier',
        'organization_id',
        'other_identifier',
        'title',
        'description',
        'activity_status',
        'recipient_country',
        'recipient_region',
        'participating_organization',
        'activity_date',
        'location',
        'sector',
        'default_field_values',
        'budget'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class, 'activity_id', 'id');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'activity_id', 'id');
    }

    /**
     * A Project has many document links.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentLinks()
    {
        return $this->hasMany(DocumentLink::class, 'activity_id', 'id');
    }

    /**
     * Get the Result/Outcome Documents for a Project.
     * @return array
     */
    public function resultDocuments()
    {
        return $this->filterDocumentLinks('A08');
    }

    /**
     * Get the Annual Reports for a Project.
     * @return array
     */
    public function annualReports()
    {
        return $this->filterDocumentLinks('B01');
    }

    /**
     * Filter Document Links by category code.
     * @param $code
     * @return null
     */
    protected function filterDocumentLinks($code)
    {
        foreach ($this->documentLinks as $documentLink) {
            if (getVal($documentLink->document_link, ['category', 0, 'code']) == $code) {

                return $documentLink->toArray();
            }
        }

        return null;
    }
}
