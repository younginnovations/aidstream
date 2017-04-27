<?php namespace App\Services\Settings\Queue;


use App\Jobs\Job;
use App\Models\Settings;
use App\Services\Settings\ChangeHandler;
use App\User;
use Exception;
use Illuminate\Contracts\Mail\Mailer;

/**
 * Class PublisherIdChanger
 * @package App\Services\Settings\Queue
 */
class PublisherIdChanger extends Job
{
    /**
     * Filename for the status of publisher id change.
     */
    const PUBLISHER_ID_CHANGED_FILENAME = 'status.json';
    /**
     * @var
     */
    protected $organizationId;
    /**
     * @var
     */
    protected $publisherId;
    /**
     * @var
     */
    protected $apiKey;
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var
     */
    protected $filePath;

    /**
     * @var
     */
    protected $mailer;
    /**
     * @var
     */
    private $userId;
    /**
     * @var
     */
    protected $changes;

    /**
     * PublisherIdChanger constructor.
     * @param $organizationId
     * @param $userId
     * @param $publisherId
     * @param $apiKey
     * @param $settings
     * @param $filePath
     * @param $changes
     */
    public function __construct($organizationId, $userId, $publisherId, $apiKey, $settings, $filePath, $changes)
    {
        $this->organizationId = $organizationId;
        $this->publisherId    = $publisherId;
        $this->apiKey         = $apiKey;
        $this->settings       = $settings;
        $this->filePath       = $filePath;
        $this->userId         = $userId;
        $this->changes        = $changes;
    }

    /**
     * Handle the change process of publisher Id.
     */
    public function handle()
    {
        try {
            $response['status'] = 'Processing';
            $this->writeFile($response);
            $changeHandler      = app()->make(ChangeHandler::class);
            $response           = $changeHandler->handle($this->organizationId, $this->publisherId, $this->apiKey, $this->settings);
            $response['status'] = ($response['status'] == true) ? 'Completed' : false;
            $this->writeFile($response);
            $this->prepareAndSendMail($response);
            $this->delete();
        } catch (Exception $exception) {
            $this->writeFile(['status' => 'false', 'message' => $exception->getMessage()]);
        }

    }

    /**
     * Prepare and send mail to the user who changed publisher id.
     *
     * @param $response
     */
    public function prepareAndSendMail($response)
    {
        $this->mailer = $this->mailer();

        $view = 'emails.publisherIdChanged';
        $user = $this->user();

        $callback        = function ($message) use ($user) {
            $message->subject('Publisher Id Changed');
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($user->email);
        };
        $data            = $user->toArray();
        $data['changes'] = $this->changes;
        $data['status']  = (getVal($response, ['status']) == true) ? true : getVal($response, ['message']);
        $this->mailer->send($view, $data, $callback);
    }

    /**
     * Store the status in json.
     *
     * @param $response
     */
    protected function writeFile($response)
    {
        $this->createDirectoryIfNotExisted();
        file_put_contents(sprintf('%s/%s', $this->filePath, self::PUBLISHER_ID_CHANGED_FILENAME), json_encode($response));
    }

    /**
     * Create the directory if not existed.
     */
    protected function createDirectoryIfNotExisted()
    {
        if (!file_exists($this->filePath)) {
            mkdir($this->filePath, 0777, true);
        }

        shell_exec(sprintf('chmod 777 -R %s', $this->filePath));
    }

    /**
     * @return mixed
     */
    protected function mailer()
    {
        return app()->make(Mailer::class);
    }

    /**
     * @return mixed
     */
    protected function user()
    {
        return User::findOrFail($this->userId);
    }
}

