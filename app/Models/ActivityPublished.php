<?php namespace App\Models;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class ActivityPublished
 * @package App\Models
 */
class ActivityPublished extends Model
{
    protected $table = "activity_published";
    protected $fillable = ['published_activities', 'filename', 'published_to_register', 'organization_id'];

    protected $casts = [
        'published_activities' => 'json'
    ];

    /** Returns total no of activities published of given organization
     * @param $orgId
     * @return mixed
     */
    public function getNoOfActivitiesPublished($orgId)
    {
        return DB::table('organizations')
                 ->join('activity_published', 'activity_published.organization_id', '=', 'organizations.id')
                 ->select(DB::raw('count(activity_published.published_to_register) as NoOfPublishedActivities'))
                 ->where('organizations.id', $orgId)
                 ->where('activity_published.published_to_register', 1)
                 ->get();
    }

    /**
     * Extract the Activity Ids from the activity-xml filenames included with in the Activity Xml file.
     * @return array
     */
    public function extractActivityId()
    {
        $activityIds = [];

        if ($this->published_activities) {
            foreach ($this->published_activities as $publishedActivity) {
                $pieces                    = explode('-', $this->getFilename('.', $publishedActivity));
                $activityIds[end($pieces)] = $publishedActivity;
            }
        }

        return $activityIds;
    }

    protected function getFilename($delimiter, $file)
    {
        $file = explode($delimiter, $file);

        return reset($file);
    }

    /**
     * An ActivityPublished record belongs to an Organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
