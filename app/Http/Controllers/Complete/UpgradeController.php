<?php namespace App\Http\Controllers\Complete;

use App\Services\SettingsManager;
use App\Services\UpgradeManager;
use Illuminate\Database\DatabaseManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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

    protected $currentVersion;

    /**
     * @param DatabaseManager $databaseManager
     * @param SettingsManager $settingsManager
     * @param UpgradeManager  $upgradeManager
     */
    function __construct(DatabaseManager $databaseManager, SettingsManager $settingsManager, UpgradeManager $upgradeManager)
    {
        $this->middleware('auth');
        $this->orgId = session('org_id');
        $db_organization = $databaseManager->table('organizations')->select('name')->where('id','=',$this->orgId)->first();
        $this->orgName = $db_organization->name;
        $settings    = $settingsManager->getSettings($this->orgId);
        $this->currentVersion   = $settings ? $settings->version : null;
        $db_versions = $databaseManager->table('versions')->get();
        $versions    = [];
        foreach ($db_versions as $ver) {
            $versions[] = $ver->version;
        }
        $this->versions       = $versions;
        $versionKey           = array_search($this->currentVersion, $versions);
        $this->version        = (end($versions) === $this->currentVersion) ? null : $versions[$versionKey + 1];
        $this->upgradeManager = $upgradeManager;
    }

    /**
     * display version upgrade page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->version || !session('allowed_upgrade')) {

            return redirect()->back();
        }

        return view('Upgrade.index', ['version' => $this->version, 'orgId' => $this->orgId,'orgName'=> $this->orgName]);
    }

    /**
     * continue version upgrade
     * @param $version
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($version,DatabaseManager $databaseManager)
    {
        if (!$this->version) {
            return redirect()->back();
        }
        $result   = $this->upgrade();
        if($result) {
            Session::put('current_version',$version);

            $versions_db = $databaseManager->table('versions')->get();
            $versions = [];
            foreach ($versions_db as $ver) {
                $versions[] = $ver->version;
            }
            $versionKey  = array_search($version, $versions);
            $next_version = (end($versions) == $version) ? null : $versions[$versionKey + 1];
            Session::put('next_version',$next_version);

            $response = ['type' => 'success', 'code' => ['upgraded', ['version' => $version]]];

        } else {
            $response = ['type' => 'danger', 'code' => ['upgrade_failed']];

            return redirect('/settings')->withResponse($response);
        }

        return redirect('/upgrade-version/complete')->withResponse($response);
    }

    /**
     * upgrade version
     * @return bool
     */
    protected function upgrade()
    {
        $next_version = $this->version;
        $orgId   = $this->orgId;

        return $this->upgradeManager->upgrade($orgId, $next_version);
    }
}
