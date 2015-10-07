<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class RecipientCountry
 * @package App\Core\V201\Repositories\Activity
 */
class RecipientCountry
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @param Activity $activity
     */
    function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * update recipient country
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->recipient_country = $activityDetails['recipient_country'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getRecipientCountryData($activityId)
    {
        return $this->activity->findOrFail($activityId)->recipient_country;
    }
}
