<?php namespace App\Core\V201\Element\Activity;

use App\Models\Activity\Activity;

class Identifier
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Identifier";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\IatiIdentifierRepository');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $identifiers  = (array) $activity->identifier;
        foreach ($identifiers as $identifier) {
            $activityData[] = [
                '@value' => $identifier['iati_identifier_text']
            ];
        }

        return $activityData;
    }
}
