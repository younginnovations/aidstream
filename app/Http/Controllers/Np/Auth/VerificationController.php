<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RequestManager\Password;
use App\Services\SettingsManager;
use App\Services\Verification;
use Illuminate\Http\RedirectResponse;

/**
 * Class VerificationController
 * @package App\Http\Controllers\Auth
 */
class VerificationController extends Controller
{
    /**
     * @var Verification
     */
    protected $verificationManager;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * VerificationController constructor.
     * @param Verification    $verificationManager
     * @param SettingsManager $settingsManager
     */
    public function __construct(Verification $verificationManager, SettingsManager $settingsManager)
    {
        $this->verificationManager = $verificationManager;
        $this->settingsManager     = $settingsManager;
    }

    /**
     * verifies user
     * @param $code
     * @return RedirectResponse
     */
    public function verifyUser($code)
    {
        return $this->verificationManager->verifyUser($code);
    }

    /**
     * verifies secondary
     * @param $code
     * @return RedirectResponse
     */
    public function verifySecondary($code)
    {
        return $this->verificationManager->verifySecondary($code);
    }

    /**
     * saves registry info
     * @param $code
     * @return RedirectResponse
     */
    public function saveRegistryInfo($code)
    {
        $registryInfo = request()->all();

        if ($this->verificationManager->saveRegistryInfo($code, $registryInfo, $this->settingsManager)) {
            return redirect()->to('/auth/login')->withMessage(trans('success.registry_info_saved'));
        } else {
            return redirect()->to('/auth/login')->withErrors([trans('error.failed_to_save_registry_info')]);
        }
    }

    /**
     * Add publishing info later.
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPublishingInfoLater()
    {
        $code         = request()->get('code');
        $registryInfo = ['publisher_id' => '', 'api_id' => ''];

        if ($this->verificationManager->saveRegistryInfo($code, $registryInfo, $this->settingsManager)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
