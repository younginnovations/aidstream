<?php namespace App\Http\Controllers;

use App\Models\Organization\Organization;
use App\Services\Activity\ActivityManager;

/**
 * Class WhoIsUsingController
 * @package App\Http\Controllers
 */
class WhoIsUsingController extends Controller
{

    function __construct(ActivityManager $activityManager)
    {
        $this->activityManager = $activityManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organizationCount = Organization::count();

        return view('who-is-using', compact('organizationCount'));
    }

    /**
     * return organization list
     * @param int $page
     * @param int $count
     * @return mixed
     */
    public function listOrganization($page = 0, $count = 20)
    {
        $skip                  = $page * $count;
        $data['next_page']     = Organization::count() > ($skip + $count);
        $data['organizations'] = Organization::select('name', 'logo_url')->skip($skip)->take($count)->get();

        return $data;
    }


    /**
     * @param $organizationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDataForOrganization($organizationId)
    {
        $data             = $this->activityManager->getDataForOrganization($organizationId);
        $transaction      = $this->mergeTransaction($data);
        $recipientRegion  = $this->mergeRecipientRegion($data);
        $recipientCountry = $this->mergeRecipientCountry($data);
        $sector           = $this->mergeSector($data);
        $activityStatus   = $this->mergeActivityStatus($data);
        $activityName     = $this->getActivityName($data);

        $final_data = $this->getDataMerge($transaction, $recipientRegion, $recipientCountry, $sector, $activityName, $activityStatus);

        return view('who-is-using-organization', compact('final_data'));
    }

    /**
     * @param $data
     * @return array
     */
    protected function mergeTransaction($data)
    {
        $arrays = [];
        foreach ($data as $key => $datum) {
            foreach ($datum->activity_data['transaction'] as $index => $value) {
                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + $value;
                } else {
                    $arrays[$index] = $value;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param $data
     * @return array
     */
    protected function mergeRecipientRegion($data)
    {
        $arrays = [];
        foreach ($data as $key => $datum) {
            foreach ($datum->activity_data['recipient_region'] as $index => $value) {
                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + $value;
                } else {
                    $arrays[$index] = $value;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param $data
     * @return array
     */
    protected function mergeRecipientCountry($data)
    {
        $arrays = [];
        foreach ($data as $key => $datum) {
            foreach ($datum->activity_data['recipient_country'] as $index => $value) {
                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + $value;
                } else {
                    $arrays[$index] = $value;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param $data
     * @return array
     */
    protected function mergeSector($data)
    {
        $arrays = [];
        foreach ($data as $key => $datum) {
            foreach ($datum->activity_data['sector'] as $index => $value) {
                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + $value;
                } else {
                    $arrays[$index] = $value;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param $data
     * @return array
     */
    protected function mergeActivityStatus($data)
    {
        $arrays = [];
        foreach ($data as $key => $datum) {
            $index = $datum->activity_data['activity_status'];
            if ($index != null) {
                if (array_key_exists($index, $arrays)) {
                    $arrays[$index] = $arrays[$index] + 1;
                } else {
                    $arrays[$index] = 1;
                }
            }
        }

        return $arrays;
    }

    /**
     * @param $data
     * @return array
     */
    protected function getActivityName($data)
    {
        $arrays = [];

        foreach ($data as $key => $datum) {
            $arrays['title'][]      = $datum->activity_data['title'];
            $arrays['identifier'][] = $datum->activity_data['identifier'];
        }

        return $arrays;
    }

    /**
     * @param $transaction
     * @param $recipientRegion
     * @param $recipientCountry
     * @param $sector
     * @param $activityName
     * @param $activityStatus
     * @return array
     */
    protected function getDataMerge($transaction, $recipientRegion, $recipientCountry, $sector, $activityName, $activityStatus)
    {
        return [
            'transaction'       => $transaction,
            'recipient_region'  => $recipientRegion,
            'recipient_country' => $recipientCountry,
            'sector'            => $sector,
            'activity_status'   => $activityStatus,
            'activity_name'     => $activityName
        ];
    }
}
