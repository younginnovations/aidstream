<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

/**
 * Class RecipientCountryManager
 * @package App\Services\Activity
 */
class RecipientCountryManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $log;
    /**
     * @var Version
     */
    protected $version;
    protected $recipientCountryRepo;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->recipientCountryRepo = $version->getActivityElement()->getRecipientCountry()->getRepository();
        $this->auth                 = $auth;
        $this->log                  = $log;
    }

    /**
     * update Recipient Country
     * @param array    $input
     * @param Activity $activity
     * @return bool
     */
    public function update(array $input, Activity $activity)
    {
        try {
            $this->recipientCountryRepo->update($input, $activity);
            $this->log->info(
                'Recipient Country  Updated!',
                ['for ' => $activity->recipient_country]
            );
            $this->log->activity(
                "activity.recipient_country_updated",
                [
                    'recipientCountry' => $input['recipient_country'],
                    'organization'     => $this->auth->user()->organization->name,
                    'organization_id'  => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Recipient Country could not be updated due to %s', $exception->getMessage()),
                [
                    'RecipientCountry' => $input,
                    'trace'            => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getRecipientCountryData($id)
    {
        return $this->recipientCountryRepo->getRecipientCountryData($id);
    }

}
