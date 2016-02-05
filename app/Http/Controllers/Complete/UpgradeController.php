<?php namespace App\Http\Controllers\Complete;

use App\Services\SettingsManager;
use App\Services\UpgradeManager;
use Illuminate\Database\DatabaseManager;
use App\Http\Controllers\Controller;

/**
 * Class UpgradeController
 * @package App\Http\Controllers\Complete
 */
class UpgradeController extends Controller
{
    /**
     * @var mixed
     */
    protected $orgId;
    /**
     * @var null
     */
    protected $version;
    /**
     * @var array
     */
    protected $versions;
    /**
     * @var UpgradeManager
     */
    protected $upgradeManager;

    /**
     * @param DatabaseManager $databaseManager
     * @param SettingsManager $settingsManager
     * @param UpgradeManager  $upgradeManager
     */
    function __construct(DatabaseManager $databaseManager, SettingsManager $settingsManager, UpgradeManager $upgradeManager)
    {
        $this->middleware('auth');
        $this->orgId = session('org_id');
        $settings    = $settingsManager->getSettings($this->orgId);
        $version     = $settings->version;
        $db_versions = $databaseManager->table('versions')->get();
        $versions    = [];
        foreach ($db_versions as $ver) {
            $versions[] = $ver->version;
        }
        $this->versions       = $versions;
        $versionKey           = array_search($version, $versions);
        $this->version        = (end($versions) === $version) ? null : $versions[$versionKey + 1];
        $this->upgradeManager = $upgradeManager;
    }

    /**
     * display version upgrade page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->version) {
            return redirect()->back();
        }

        return view('Upgrade.index', ['version' => $this->version, 'orgId' => $this->orgId]);
    }

    /**
     * continue version upgrade
     * @param $version
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($version)
    {
        if (!$this->version) {
            return redirect()->back();
        }
        $result   = $this->upgrade();
        $response = $result ? ['type' => 'success', 'code' => ['upgraded', ['version' => $version]]] : ['type' => 'danger', 'code' => ['upgrade_failed']];

        return redirect('/settings')->withResponse($response);
    }

    /**
     * upgrade version
     * @return bool
     */
    protected function upgrade()
    {
        $version = $this->version;
        $orgId   = $this->orgId;

        return $this->upgradeManager->upgrade($orgId, $version);
    }
}
