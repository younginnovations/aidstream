<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ActivityScope
 * @package App\Core\V201\Forms\Activity
 */
class ActivityScope extends Form
{
    protected $showFieldErrors = true;

    /**
     * builds activity scope form
     */
    public function buildForm()
    {
        $activityScopeCodeList = file_get_contents(
            app_path("Core/V201/Codelist/" . config('app.locale') . "/Activity/ActivityScope.json")
        );
        $activityScopeCodes    = json_decode($activityScopeCodeList, true);
        $activityScope         = $activityScopeCodes['ActivityScope'];
        $activityScopeCode     = [];

        foreach ($activityScope as $activity) {
            $activityScopeCode[$activity['code']] = $activity['code'] . ' - ' . $activity['name'];
        }

        $this
            ->add(
                'activity_scope',
                'select',
                [
                    'choices' => $activityScopeCode,
                    'label'   => 'Activity scope'
                ]
            );
    }
}
