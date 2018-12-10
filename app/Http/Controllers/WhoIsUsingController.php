<?php namespace App\Http\Controllers;

use App\Http\Controllers\Complete\Activity\RecipientCountryController;
use App\Http\Requests\Request;
use App\Lite\Services\Activity\ActivityService;
use App\Models\PerfectViewer\ActivitySnapshot;
use App\Services\Activity\ActivityManager;
use App\Services\PerfectViewer\PerfectViewerManager;
use App\Services\SettingsManager;
use Illuminate\Session\SessionManager;
use App\Models\Settings;
use App\User;

/**
 * Class WhoIsUsingController
 * @package App\Http\Controllers
 */
class WhoIsUsingController extends Controller
{

    /**
     * @var ActivitySnapshot
     */
    protected $perfectViewerManager;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var ActivityService
     */
    protected $activityService;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * Organization ID
     *
     * @var Integer
     */
    protected $organization_id;

    /**
     * WhoIsUsingController constructor.
     *
     * @param ActivityManager $activityManager
     * @param User $user
     * @param PerfectViewerManager $perfectViewerManager
     */
    function __construct(
        ActivityManager             $activityManager, 
        User                        $user, 
        PerfectViewerManager        $perfectViewerManager,
        SettingsManager             $settingsManager,
        SessionManager              $sessionManager,
        Settings                    $settings
    ) {
        $this->activityManager      = $activityManager;
        $this->user                 = $user;
        $this->perfectViewerManager = $perfectViewerManager;
        $this->settingsManager      = $settingsManager;
        $this->sessionManager       = $sessionManager;
        $this->settings             = $settings;
    }

    /**
     * Returns Organization count
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (isTzSubDomain()) {
            list($organizations, $isTz,$isNp) = [$this->organizationQueryBuilder()->where('system_version_id', config('system-version.Tz.id'))->get(), true,false];
        }elseif(isNpSubDomain()){
            list($organizations, $isNp,$isTz) = [$this->organizationQueryBuilder()->where('system_version_id', config('system-version.Np.id'))->get(), true,false];
        }
         else {
            list($organizations, $isTz, $isNp) = [$this->organizationQueryBuilder()->get(), false,false];
        }

        return view('who-is-using', compact('organizations', 'isTz', 'isNp'));
    }

    /**
     * Returns query of organizations published on Aidstream.
     *
     * @return mixed
     */
    public function organizationQueryBuilder()
    {
        return $this->perfectViewerManager->organizationQueryBuilder();
    }

    /**
     * Returns Activity Snapshot Query Builder
     *
     * @return ActivitySnapshot
     */
    protected function activityQueryBuilder()
    {
        return $this->perfectViewerManager->activityQueryBuilder();
    }

    /**
     * Returns data for Perfect Activity Viewer
     *
     * @param $orgId
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showActivity($orgId, $activityId)
    {
        $organizationIdExists = $this->organizationQueryBuilder()->where('org_slug', $orgId)->get();
        if (count($organizationIdExists) == 0) {
            return redirect()->back()->withResponse($this->getNotFoundResponse());
        }

        $activityIdExists = $this->activityQueryBuilder()->where('activity_id', $activityId)->get();
        if (count($activityIdExists) == 0) {
            return redirect()->back()->withResponse($this->getNotFoundResponse());
        }

        $activityData = $this->activityManager->getActivityData($activityId);
        $this->organization_id = $organizationIdExists[0]['id'];
        $filename                = $this->getPublishedActivityFilename($this->organization_id, $activityData);
        $activityPublishedStatus = $this->getPublishedActivityStatus($filename, $this->organization_id);
        $message                 = $this->getMessageForPublishedActivity($activityPublishedStatus, $filename, $activityData->organization);

        $defaultFieldValues = $activityData->default_field_values;

        $recipientCountries = $this->getRecipientCountries($activityIdExists->toArray());
        $user = $this->user->getDataByOrgIdAndRoleId($organizationIdExists[0]->org_id, '1');

        $organization = json_decode($organizationIdExists, true);
        $activity = json_decode($activityIdExists, true);
        $organization[0]['reporting_org'] = trimReportingOrg(json_decode(getVal($organization, [0, 'reporting_org']), true));

        $activity = $this->filterDescription($activity);

        $transactions = array_reverse(
            array_sort(
                getVal($activity, [0, 'published_data', 'transactions'], []),
                function ($value) {
                    return getVal($value, ['transaction', 'transaction_date', 0, 'date'], '');
                }
            )
        );

        return view('perfectViewer.activity-viewer', compact('organization', 'activity', 'user', 'recipientCountries', 'activityPublishedStatus', 'defaultFieldValues', 'transactions'));
    }

    /** Returns the filename that is generated when activity is published based on publishing type.
     * @param $organization_id
     * @param $activity
     * @return string
     */
    public function getPublishedActivityFilename($organization_id, $activity)
    {
        $settings       = $this->settings->where('organization_id', $organization_id)->first();

        $publisherId    = $settings->registry_info[0]['publisher_id'];
        $publishingType = $settings->publishing_type;

        if ($publishingType != "segmented") {
            $endName = 'activities';
        } else {
            $activityElement = $this->activityManager->getActivityElement();
            $xmlService      = $activityElement->getActivityXmlService();
            $endName         = $xmlService->segmentedXmlFile($activity);
        }
        $filename = sprintf('%s' . '-' . '%s.xml', $publisherId, $endName);

        return $filename;
    }

    /** Returns according to published to registry status of the activity.
     * @param $filename
     * @param $organization_id
     * @return string
     */
    public function getPublishedActivityStatus($filename, $organization_id)
    {
        $activityPublished   = $this->activityManager->getActivityPublishedData($filename, $organization_id);
        $settings            = $this->settings->where('organization_id', $organization_id)->first();
        $autoPublishSettings = $settings->registry_info[0]['publish_files'];
        $status              = 'Unlinked';

        if ($activityPublished) {
            if ($autoPublishSettings == "no") {
                ($activityPublished->published_to_register == 0) ? $status = "Unlinked" : $status = "Linked";
            } else {
                ($activityPublished->published_to_register == 0) ? $status = "unlinked" : $status = "Linked";
            }
        }

        return $status;
    }

    /** Returns message according to the status of the activity
     * @param $status
     * @param $filename
     * @return string
     */
    protected function getMessageForPublishedActivity($status, $filename, $organization)
    {
        $publisherId = getVal($organization->settings->toArray(), ['registry_info', 0, 'publisher_id'], null);
        $link        = $publisherId ? "<a href='https://iatiregistry.org/publisher/" . $publisherId . "' target='_blank'>IATI registry</a>" : "IATI Registry";

        if ($status == "Unlinked") {
            $message = trans('error.activity_not_published_to_registry');
        } elseif ($status == "Linked") {
            $message = trans('success.activity_published_to_registry', ['link' => $link]) . ' ' . "<a href='/files/xml/$filename'>$filename</a>";
        } else {
            $message = trans('error.republish_activity');
        }

        return $message;
    }

    /**
     * Returns data for Perfect Organization Viewer
     *
     * @param         $organizationId
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showOrganization($organizationId, Request $request)
    {
        $queryParamPublished = $request->input('published');
        $organizationIdExists = $this->organizationQueryBuilder()->where('org_slug', $organizationId)->get();

        if (count($organizationIdExists) == 0) {
            return redirect()->back()->withResponse($this->getNotFoundResponse());
        }

        $activitySnapshot = $this->perfectViewerManager->getSnapshotWithOrgId($organizationIdExists[0]->org_id);
        $organizations = json_decode($organizationIdExists[0], true);

        $organizations['reporting_org'] = trimReportingOrg(json_decode(getVal($organizations, ['reporting_org']), true));
        $allActivities = json_decode($activitySnapshot, true);
        $recipientCountries = $this->getRecipientCountries($allActivities);
        $user = $this->user->getDataByOrgIdAndRoleId($organizationIdExists[0]->org_id, '1');
        $activities = [];

        if ($queryParamPublished) {
            foreach ($allActivities as $key => $item) {
                if (getVal($item, ['activity_in_registry'], null)) {
                    $activities[] = $item;
                }
            }
        } else {
            $activities = $allActivities;
        }

        return view('perfectViewer.organization-viewer', compact('activities', 'organizations', 'user', 'recipientCountries'));
    }

    /**
     * Provides Recipient Countries
     *
     * @param $activities
     * @return array
     */
    protected function getRecipientCountries(array $activities)
    {
        $recipientCountries = [];
        foreach ($activities as $index => $activity) {
            if (getVal($activity, ['published_data', 'recipient_country'], null)) {
                foreach (getVal($activity, ['published_data', 'recipient_country'], []) as $country) {
                    $recipientCountries[] = getVal($country, ['country_code'], '');
                }
            } else {
                foreach (getVal($activity, ['published_data', 'transactions'], []) as $tranIndex => $transaction) {
                    $recipientCountries[] = getVal($transaction, ['transaction', 'recipient_country', 0, 'country_code'], '');
                }
            }
        }

        $recipientCountries = array_unique($recipientCountries);

        return $recipientCountries;
    }

    /**
     * Provides a Description of an activity
     *
     * @param $description
     * @return string
     */
    protected function getDescription($description)
    {
        if (is_array($description)) {
            foreach ($description as $index => $value) {
                if (getVal($value, ['type'], 0) == 1) {
                    return getVal($value, ['narrative', 0, 'narrative'], '');
                }
                if (getVal($value, ['type'], 0) == 2) {
                    return getVal($value, ['narrative', 0, 'narrative'], '');
                }
                if (getVal($value, ['type'], 0) == 3) {
                    return getVal($value, ['narrative', 0, 'narrative'], '');
                }
                if (getVal($value, ['type'], 0) == 4) {
                    return getVal($value, ['narrative', 0, 'narrative'], '');
                }
            }
        }

        return '';
    }

    /**
     * Filters Description
     *
     * @param $activities
     * @return mixed
     */
    protected function filterDescription($activities)
    {
        foreach ($activities as $index => $value) {
            $activities[$index]['published_data']['description'] = $this->getDescription(getVal($activities, [$index, 'published_data', 'description'], ''));
        }

        return $activities;
    }

    /**
     * Returns a response message for 404 exception.
     *
     * @return array
     */
    protected function getNotFoundResponse()
    {
        return ['type' => 'danger', 'messages' => ['The requested resource could not be found. Please contact support.']];
    }

}
