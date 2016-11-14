<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            [
                'app',
            ],
            function ($view) {
                $view->with('currentUser', auth()->user());
                $view->with('loggedInUser', auth()->user());
            }
        );
        view()->composer(
            [
                'Activity.index',
                'Activity.activityBaseTemplate',
                'settings.publishingSettings',
                'settings.defaultValues',
                'settings.activityElementsChecklist',
                'Organization.show',
                'onBoarding.welcome',
                'downloads.index',
                'settings.settings',
                'ActivityLogs.user-logs',
                'published-files',
                'documents'
            ],
            function ($view) {
                $view->with('currentUser', auth()->user());
                $view->with('loggedInUser', auth()->user());
                if (auth()->user()->userOnBoarding) {
                    $steps = auth()->user()->userOnBoarding->settings_completed_steps;
                    ($steps) ? $steps : [];
                    $view->with('completedSteps', $steps);
                }
            }
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
