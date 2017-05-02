<?php namespace App\Http\Controllers\Complete;


use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Services\Settings\ChangeHandler;
use App\Services\Settings\Queue\PublisherIdChanger;
use App\Services\SettingsManager;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Psr\Log\LoggerInterface;

/**
 * Class PublisherIdChangeController
 * @package App\Http\Controllers\Complete
 */
class PublisherIdChangeController extends Controller
{
    use DispatchesJobs;

    /**
     *
     */
    const PUBLISHER_ID_CHANGED_DIR = 'publisherIdChanged';
    /**
     * Filename for the status of publisher id change.
     */
    const PUBLISHER_ID_CHANGED_FILENAME = 'status.json';

    /**
     * @var ChangeHandler
     */
    protected $changeHandler;

    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * PublisherIdChangeController constructor.
     * @param ChangeHandler   $changeHandler
     * @param SettingsManager $settingsManager
     * @param LoggerInterface $logger
     */
    public function __construct(ChangeHandler $changeHandler, SettingsManager $settingsManager, LoggerInterface $logger)
    {
        $this->changeHandler   = $changeHandler;
        $this->settingsManager = $settingsManager;
        $this->logger          = $logger;
    }

    /**
     * Check if the publisher id is changed.
     * If yes, then details for modal window is returned.
     *
     * @param Request $request
     * @return bool|string
     */
    public function publisherIdChanged(Request $request)
    {
        $currentUser    = auth()->user();
        $publisherId    = trim($request->get('publisherId'));
        $apiKey         = trim($request->get('apiKey'));
        $organizationId = session('org_id');
        $changes        = [];
        $dbSettings     = $this->settingsManager->getSettings($organizationId)->toArray();
        $organization   = $this->changeHandler->getOrganization($organizationId);
        $oldPublisherId = getVal($dbSettings, ['registry_info', 0, 'publisher_id']);
        $inputApiKey    = false;
        $isCorrect      = false;
        $isUnique       = true;
        $isAuthorized   = true;

        if ($currentUser->isNotAdmin()) {
            $isAuthorized = false;

            return view('settings.publisherIdChanged', compact('isAuthorized', 'isUnique', 'publisherId', 'isCorrect', 'inputApiKey', 'changes', 'apiKey'))->render();
        }

        if ($this->hasPublisherIdBeenChanged($publisherId, $dbSettings)) {
            if (!isUniquePublisherId($publisherId)) {
                $isUnique = false;

                return view('settings.publisherIdChanged', compact('isUnique', 'publisherId', 'isCorrect', 'inputApiKey', 'changes', 'apiKey'))->render();
            }

            if ($this->changeHandler->checkPublisherValidity($this->changeHandler->searchForPublisher($publisherId), $publisherId)) {
                $isCorrect                 = true;
                $publishedOrganizationData = $this->changeHandler->getPublishedOrganizationData($organization)->first();
                $publishedActivities       = $this->changeHandler->getPublishedActivities($organization);

                if ($this->changeHandler->hasPublishedAnyOrganizationFile($publishedOrganizationData)) {
                    $changes['organizationData'] = $this->changeHandler->changesForOrganizationData($publishedOrganizationData, $publisherId, $apiKey);
                    (getVal($changes, ['organizationData', 0, 'linkage']) == false) ?: $inputApiKey = true;
                    unset($changes['organizationData']['linkage']);
                }

                if ($this->changeHandler->hasPublishedAnyActivityFile($publishedActivities)) {
                    $changes['activity'] = $this->changeHandler->changesForActivityData($publishedActivities, $publisherId, $apiKey);
                    (getVal($changes, ['activity', 'linkage']) == false) ?: $inputApiKey = true;
                    unset($changes['activity']['linkage']);
                }
            }

            return view('settings.publisherIdChanged', compact('changes', 'publisherId', 'oldPublisherId', 'apiKey', 'inputApiKey', 'isCorrect', 'isUnique', 'isAuthorized'))->render();
        }

        if ($oldPublisherId == "" || $publisherId == $oldPublisherId) {
            if (!$this->changeHandler->checkPublisherValidity($this->changeHandler->searchForPublisher($publisherId), $publisherId) || !isUniquePublisherId($publisherId)) {
                return response(['status' => 'Incorrect', 'loadModal' => false]);
            }

            return response(['status' => 'Correct', 'loadModal' => false]);
        }

        return response(['status' => false, 'loadModal' => false]);
    }

    /**
     * Handle the publisher id change process and dispatches a job.
     *
     * @param Request $request
     * @return mixed
     */
    public function handlePublisherIdChanged(Request $request)
    {
        $organizationId = session('org_id');
        $publisherId    = $request->get('publisherId');
        $newApiKey      = $request->get('apiKey');
        $changes        = json_decode($request->get('changes'), true);
        $dbSettings     = $this->settingsManager->getSettings($organizationId);
        $filePath       = $this->getFilePath($organizationId);
        $this->logger->info('Publisher Id change process has been started.', ['Organization Id' => $organizationId]);
        $this->dispatchJob($organizationId, auth()->user()->id, $publisherId, $newApiKey, $dbSettings, $filePath, $changes);
        Session::put(['publisherIdChange' => true]);

        $response = ['type' => 'success', 'code' => ['message', ['message' => trans('success.publisher_id_changing')]]];

        return redirect()->route('publishing-settings')->withResponse($response);
    }

    /**
     * Dispatches a job.
     *
     * @param $organizationId
     * @param $userId
     * @param $publisherId
     * @param $apiKey
     * @param $settings
     * @param $filePath
     * @param $changes
     */
    protected function dispatchJob($organizationId, $userId, $publisherId, $apiKey, $settings, $filePath, $changes)
    {
        $this->dispatch(new PublisherIdChanger($organizationId, $userId, $publisherId, $apiKey, $settings, $filePath, $changes));
    }

    /**
     * Ajax request to verify entered api key with publisher id.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyApiWithPublisherId(Request $request)
    {
        try {
            $publisherId = $request->get('publisherId');
            $apiKey      = $request->get('apiKey');

            $publisherIdResponse = $this->changeHandler->searchForPublisher($publisherId);
            $apiKeyResponse      = $this->changeHandler->searchForApiKey($apiKey);

            if (!$apiKeyResponse) {
                return response(['status' => false]);
            }

            if (!$this->changeHandler->isApiKeyOfThePublisher(json_decode($publisherIdResponse, true), $apiKeyResponse)) {
                return response(['status' => false]);
            }

            return response(['status' => true]);
        } catch (Exception $exception) {
            return response(['status' => false]);
        }
    }

    /**
     * Returns file storage path to store status of publisher id change process.
     *
     * @param      $organizationId
     * @param null $filename
     * @return string
     */
    protected function getFilePath($organizationId, $filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s/%s/%s', storage_path(), self::PUBLISHER_ID_CHANGED_DIR, $organizationId, $filename);
        }

        return sprintf('%s/%s/%s', storage_path(), self::PUBLISHER_ID_CHANGED_DIR, $organizationId);
    }

    /**
     * Returns the status of the publisher id change process.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function getStatus()
    {
        $orgId    = session('org_id');
        $filePath = $this->getFilePath($orgId, self::PUBLISHER_ID_CHANGED_FILENAME);

        if (!file_exists($filePath)) {
            return response(['status' => false]);
        }

        $response = json_decode(file_get_contents($filePath), true);

        if (getVal($response, ['status']) == 'Processing') {
            return response(['status' => 'Processing']);
        }

        if (getVal($response, ['status']) == 'Completed') {
            return response(['status' => 'Completed']);
        }

        return response(['status' => false, 'message' => getVal($response, ['message'])]);
    }

    /**
     * Complete the publisher id change process.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function completePublisherIdChangingProcess()
    {
        if (Session::has('publisherIdChange')) {
            Session::forget('publisherIdChange');
            try {
                unlink($this->getFilePath(session('org_id'), self::PUBLISHER_ID_CHANGED_FILENAME));
            } catch (Exception $e) {
                return response(['status' => 'failed']);
            }
            $this->logger->info('Publisher id process has been completed.', ['Organization Id' => session('org_id')]);

            return response(['status' => true]);
        }

        return response(['status' => false]);
    }

    /**
     * Check if the publisher id has been changed.
     *
     * @param $newPublisherId
     * @param $settings
     * @return bool
     */
    public function hasPublisherIdBeenChanged($newPublisherId, $settings)
    {
        $oldPublisherId = ($settings) ? getVal($settings, ['registry_info', 0, 'publisher_id']) : "";

        if ($oldPublisherId == "" || $newPublisherId == $oldPublisherId || $newPublisherId == "") {
            return false;
        }

        return true;
    }
}

