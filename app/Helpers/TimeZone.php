<?php
use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;

/**
 * Convert date and time according to local timezone selected
 * @param        $date
 * @param string $format
 * @return string
 */
function changeTimeZone($date, $format = 'M d, Y H:i:s')
{
    $currentTZ = config('app.timezone');
    $newTZ     = Auth::user()->time_zone;
    $current   = new \DateTimeZone($currentTZ);
    $new       = new \DateTimeZone($newTZ);
    $date      = new \DateTime($date, $current);
    $result    = $date->setTimezone($new);

    return $result->format($format);
}

/**
 * convert date format
 * @param        $date
 * @param string $format
 * @return bool|string
 */
function formatDate($date, $format = 'F d, Y')
{
    if ($date != "") {
        return date($format, strtotime($date));
    }

    return "";
}

/**
 * Get the last published date for an XML file.
 * @param $file
 * @return string
 */
function lastPublishedDate($file)
{
    if ($file instanceof ActivityPublished) {
        $table    = app()->make(Activity::class);
        $column   = 'activity_workflow';
        $included = $file->extractActivityId();
    } else {
        $table    = app()->make(Organization::class);
        $column   = 'status';
        $included = $file->organization_id;
    }

    $mostRecent = 0;

    if (count($included) > 1) {
        foreach ($included as $id => $filename) {
            $model = $table->find($id);

            if ($model && ($model->$column == 3)) {
                $activityDate = strtotime($model->updated_at);

                if ($activityDate > $mostRecent) {
                    $mostRecent = $activityDate;
                }
            }
        }
    } else {
        $mostRecent = strtotime($file->updated_at);
    }

    if ($mostRecent) {
        return changeTimeZone(date('Y-m-d H:i:s', $mostRecent));
    }

    return changeTimeZone($file->updated_at);
}
