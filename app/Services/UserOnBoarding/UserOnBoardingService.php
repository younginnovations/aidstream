<?php namespace App\Services\UserOnBoarding;

use App\Models\Organization\Organization;
use App\Models\UserOnBoarding;
use App\User;
use Exception;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Psr\Log\LoggerInterface;

/**
 * Class UserOnBoardingService
 * @package App\Services\UserOnBoarding
 */
class UserOnBoardingService
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var UserOnBoarding
     */
    protected $userOnBoarding;
    /**
     * @var User
     */
    protected $users;

    /**
     * UserOnBoardingService constructor.
     * @param LoggerInterface $logger
     * @param Log             $dbLogger
     * @param UserOnBoarding  $userOnBoarding
     * @param User            $users
     */
    public function __construct(LoggerInterface $logger, Log $dbLogger, UserOnBoarding $userOnBoarding, User $users)
    {
        $this->logger         = $logger;
        $this->dbLogger       = $dbLogger;
        $this->userOnBoarding = $userOnBoarding;
        $this->users          = $users;
    }

    /**
     * Store publisher and api key of settings.
     * @param Organization $organization
     * @param              $publisherId
     * @param              $apiId
     * @param              $publisherIdStatus
     * @param              $apiIdStatus
     * @return bool
     */
    public function storePublisherAndApiKey(Organization $organization, $publisherId, $apiId, $publisherIdStatus, $apiIdStatus)
    {
        try {

            $publish_files = (!is_null($organization->settings)) ? $organization->settings->registry_info[0]['publish_files'] : 'no';
            $registry_info = [
                0 => [
                    'publisher_id'        => $publisherId,
                    'api_id'              => $apiId,
                    'publish_files'       => $publish_files,
                    'publisher_id_status' => $publisherIdStatus,
                    'api_id_status'       => $apiIdStatus
                ]
            ];

            if (is_null($organization->settings)) {
                $settings = $organization->settings()->create(['registry_info' => $registry_info]);
            } else {
                $settings                = $organization->settings;
                $settings->registry_info = $registry_info;
                $settings->save();
            }
            $this->storeCompletedSettingsSteps(1);

            $this->logger->info('Publisher id and api key updated successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => Auth::user()->organization->name,
                    'organization_id' => Auth::user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['organization' => $organization]);
        }

        return false;
    }

    /**
     * Store publishing type of settings.
     * @param Organization $organization
     * @param              $publishingType
     * @return bool
     */
    public function storePublishingType(Organization $organization, $publishingType)
    {
        try {
            $publishingType = ($publishingType != 'unsegmented') ? 'segmented' : 'unsegmented';

            if (is_null($organization->settings)) {
                $settings = $organization->settings()->create(['publishing_type' => $publishingType]);
            } else {
                $settings                  = $organization->settings;
                $settings->publishing_type = $publishingType;
                $settings->save();
            }
            $this->storeCompletedSettingsSteps(2);
            $this->logger->info('Publishing Type updated successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => Auth::user()->organization->name,
                    'organization_id' => Auth::user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['organization' => $organization]);
        }

        return false;
    }

    /**
     * Store automatic publish to registry settings.
     * @param Organization $organization
     * @param              $publishFiles
     * @return bool
     */
    public function storePublishFiles(Organization $organization, $publishFiles)
    {
        try {
            $publisherId       = (!is_null($organization->settings)) ? $organization->settings->registry_info[0]['publisher_id'] : '';
            $apiId             = (!is_null($organization->settings)) ? $organization->settings->registry_info[0]['api_id'] : '';
            $publisherIdStatus = (!is_null($organization->settings)) ? $organization->settings->registry_info[0]['publisher_id_status'] : '';
            $apiIdStatus       = (!is_null($organization->settings)) ? $organization->settings->registry_info[0]['api_id_status'] : '';
            $publish_files     = $publishFiles;
            $registry_info     = [
                0 => [
                    'publisher_id'        => $publisherId,
                    'api_id'              => $apiId,
                    'publish_files'       => $publish_files,
                    'publisher_id_status' => $publisherIdStatus,
                    'api_id_status'       => $apiIdStatus
                ]
            ];

            if (is_null($organization->settings)) {
                $settings = $organization->settings()->create(['registry_info' => $registry_info]);
            } else {
                $settings                = $organization->settings;
                $settings->registry_info = $registry_info;
                $settings->save();
            }
            $this->storeCompletedSettingsSteps(3);

            $this->logger->info('Automatic publish to registry settings updated successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => Auth::user()->organization->name,
                    'organization_id' => Auth::user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['organization' => $organization]);

            return false;
        }
    }

    /**
     * Store activity element checklist.
     * @param              $default_field_groups
     * @param Organization $organization
     * @return bool
     */
    public function storeActivityElementsChecklist($default_field_groups, Organization $organization)
    {
        try {
            if (is_null($organization->settings)) {
                $settings = $organization->settings()->create(['default_field_groups' => $default_field_groups]);
            } else {
                $settings                       = $organization->settings;
                $settings->default_field_groups = $default_field_groups;
                $settings->save();
            }

            $this->storeCompletedSettingsSteps(4);

            $this->logger->info('Activity Elements Checklist updated successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => Auth::user()->organization->name,
                    'organization_id' => Auth::user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['organization' => $organization]);

            return false;
        }
    }

    /**
     * Store default values of settings.
     * @param              $default_values_request
     * @param Organization $organization
     * @return bool
     */
    public function storeDefaultValues($default_values_request, Organization $organization)
    {
        try {
            $settings = $organization->settings;

            $default_collaboration_type = $this->checkAndGetDefaultValues($default_values_request, $settings, 'default_collaboration_type');
            $default_flow_type          = $this->checkAndGetDefaultValues($default_values_request, $settings, 'default_flow_type');
            $default_finance_type       = $this->checkAndGetDefaultValues($default_values_request, $settings, 'default_finance_type');
            $default_aid_type           = $this->checkAndGetDefaultValues($default_values_request, $settings, 'default_aid_type');
            $default_tied_status        = $this->checkAndGetDefaultValues($default_values_request, $settings, 'default_tied_status');
            $humanitarian               = $this->checkAndGetDefaultValues($default_values_request, $settings, 'humanitarian');

            $default_values = [
                0 => [
                    'default_currency'           => $default_values_request->get('default_currency'),
                    'default_language'           => $default_values_request->get('default_language'),
                    'default_hierarchy'          => $default_values_request->get('default_hierarchy'),
                    'linked_data_uri'            => $default_values_request->get('linked_data_uri'),
                    'default_collaboration_type' => $default_collaboration_type,
                    'default_flow_type'          => $default_flow_type,
                    'default_finance_type'       => $default_finance_type,
                    'default_aid_type'           => $default_aid_type,
                    'default_tied_status'        => $default_tied_status,
                    'humanitarian'               => $humanitarian
                ]
            ];

            if (is_null($organization->settings)) {
                $organization->settings()->create(['default_field_values' => $default_values]);
            } else {

                $settings->default_field_values = $default_values;
                $settings->save();
            }

            $this->storeCompletedSettingsSteps(5);

            $this->logger->info('Default Values updated successfully.');
            $this->dbLogger->activity(
                "activity.settings_updated",
                [
                    'organization'    => Auth::user()->organization->name,
                    'organization_id' => Auth::user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception, ['organization' => $organization]);

            return false;
        }
    }

    /**
     * Checks and returns if the field exists in array
     * Returns its original value if not found
     * @param $default_values_request
     * @param $settings
     * @param $field
     * @return string
     */
    public function checkAndGetDefaultValues($default_values_request, $settings, $field)
    {
        if (!is_null($settings)) {
            (!array_key_exists($field, $default_values_request->all())) ? $field = getVal(
                $settings->default_field_values,
                [0, sprintf('%s', $field)]
            ) : $field = $default_values_request->get(
                sprintf('%s', $field)
            );

            return $field;
        }

        return '';
    }

    /**
     * End the user On Boarding.
     */
    public function completeTour()
    {
        $userOnBoarding                 = auth()->user()->userOnBoarding;
        $userOnBoarding->completed_tour = true;
        $userOnBoarding->save();
        Session::forget('first_login');
    }

    /**
     * Store completed steps of settings user onboarding.
     * @param $step
     */
    public function storeCompletedSettingsSteps($step)
    {
        $userOnBoarding = auth()->user()->userOnBoarding;
        $this->storeSteps($step, $userOnBoarding);
        $this->settingStepsForOtherAdministrator($step);
    }

    /**
     * Store steps for the other admin user of same organisation.
     * @param $step
     */
    protected function settingStepsForOtherAdministrator($step)
    {
        $users = $this->users->where('org_id', session('org_id'))->get();
        foreach ($users as $user) {
            if ($user->id != session('user_id')) {
                $userOnBoarding = $user->userOnboarding;
                $this->storeSteps($step, $userOnBoarding);
            }
        }
    }

    /**
     * Store steps completed.
     * @param $step
     * @param $userOnBoarding
     */
    protected function storeSteps($step, $userOnBoarding)
    {
        $completedSteps                           = $userOnBoarding->settings_completed_steps;
        $completedSteps[]                         = $step;
        $userOnBoarding->settings_completed_steps = array_unique($completedSteps);
        $userOnBoarding->save();
    }

    /**
     * Returns if all the steps are completed.
     * @return bool
     */
    public function isAllStepsCompleted()
    {
        $completedSteps = count($this->getCompletedSettingsSteps());
        $status         = ($completedSteps == 5) ? true : false;

        return $status;
    }


    /**
     * Get all the completed steps of the logged in user.
     * @return array|null
     */
    public function getCompletedSettingsSteps()
    {
        $userOnBoarding = auth()->user()->userOnBoarding;

        return ($userOnBoarding) ? (array) $userOnBoarding->settings_completed_steps : null;
    }

    /**
     * Create a new On boarding for newly created user.
     * @param $userId
     * @param $completedSteps
     * @return UserOnBoarding
     */
    public function create($userId, $completedSteps)
    {
        return $this->userOnBoarding->create(['has_logged_in_once' => false, 'settings_completed_steps' => $completedSteps, 'user_id' => $userId]);
    }

    /**
     * Store the closed dashboard hints.
     * @param $status
     */
    public function storeHintStatus($status)
    {
        $userOnBoarding                 = auth()->user()->userOnBoarding;
        $userOnBoarding->display_hints  = $status;
        $userOnBoarding->completed_tour = 1;
        $userOnBoarding->save();

        return $userOnBoarding;
    }
}