<?php namespace App\Http\Controllers;

use App\Models\PerfectViewer\ActivitySnapshot;
use App\Services\Activity\ActivityManager;
use App\Services\PerfectViewer\PerfectViewerManager;
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
     * WhoIsUsingController constructor.
     *
     * @param ActivityManager      $activityManager
     * @param User                 $user
     * @param PerfectViewerManager $perfectViewerManager
     */
    function __construct(ActivityManager $activityManager, User $user, PerfectViewerManager $perfectViewerManager)
    {
        $this->activityManager      = $activityManager;
        $this->user                 = $user;
        $this->perfectViewerManager = $perfectViewerManager;
    }

    /**
     * Returns Organization count
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizations = $this->organizationQueryBuilder()->get();

        return view('who-is-using', compact('organizations'));
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

        $activityData       = $this->activityManager->getActivityData($activityId);
        $defaultFieldValues = $activityData->default_field_values;

        $recipientCountries = $this->getRecipientCountries($activityIdExists->toArray());
        $user               = $this->user->getDataByOrgIdAndRoleId($organizationIdExists[0]->org_id, '1');

        $organization                     = json_decode($organizationIdExists, true);
        $activity                         = json_decode($activityIdExists, true);
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

        return view('perfectViewer.activity-viewer', compact('organization', 'activity', 'user', 'recipientCountries', 'defaultFieldValues', 'transactions'));
    }

    /**
     * Returns data for Perfect Organization Viewer
     *
     * @param $organizationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDataForOrganization($organizationId)
    {
        $organizationIdExists = $this->organizationQueryBuilder()->where('org_slug', $organizationId)->get();

        if (count($organizationIdExists) == 0) {
            return redirect()->back()->withResponse($this->getNotFoundResponse());
        }

        $activitySnapshot               = $this->perfectViewerManager->getSnapshotWithOrgId($organizationIdExists[0]->org_id);
        $organizations                  = json_decode($organizationIdExists[0], true);
        $organizations['reporting_org'] = trimReportingOrg(json_decode(getVal($organizations, ['reporting_org']), true));
        $activities                     = json_decode($activitySnapshot, true);
        $recipientCountries             = $this->getRecipientCountries($activities);
        $user                           = $this->user->getDataByOrgIdAndRoleId($organizationIdExists[0]->org_id, '1');

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
