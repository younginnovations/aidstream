<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ActivityStatus
 * @package App\Core\V201\Forms\Activity
 */
class ActivityStatus extends Form
{
    protected $showFieldErrors = true;

    /**
     * builds activity status form
     */
    public function buildForm()
    {
        $activityStatusCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/ActivityStatus.json")
        );
        $activityStatusCodes    = json_decode($activityStatusCodeList, true);
        $activityStatus         = $activityStatusCodes['ActivityStatus'];
        $activityStatusCode     = [];

        foreach ($activityStatus as $activity) {
            $activityStatusCode[$activity['code']] = $activity['code'] . ' - ' . $activity['name'];
        }

        $this
            ->add(
                'activity_status',
                'select',
                [
                    'choices' => $activityStatusCode,
                    'label'   => 'Activity status'
                ]
            );
    }
}
