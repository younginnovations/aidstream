<?php
namespace App\Core;

use App;
use Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;

class Version
{
    protected $activity;
    protected $organization;
    protected $version;
    protected $activityElement;
    protected $organizationElement;
    protected $settingsElement;
    protected $repository;
    protected $formElement;
    protected $iatiAttributes = [];

    public function __construct()
    {
        $this->setVersion();
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion()
    {
        $this->version = Session::get('version');
        if (!isset($this->version)) {
            $this->version = config('app.default_version_name');
            Session::put('version', $this->version);
        }
        $this->activityElement     = App::make("App\Core\\$this->version\IatiActivity");
        $this->organizationElement = App::make("App\Core\\$this->version\IatiOrganization");
        $this->settingsElement     = App::make("App\Core\\$this->version\IatiSettings");

        return $this;
    }

    /**
     * @return array
     */
    public function getIatiAttributes()
    {
        return $this->iatiAttributes;
    }

    /**
     * @param array $iatiAttributes
     */
    public function setIatiAttributes($iatiAttributes)
    {
        $this->iatiAttributes = $iatiAttributes;
    }

    /**
     * @return App\Core\V201\IatiActivity
     */
    public function getActivityElement()
    {
        return $this->activityElement;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    public function getOrganizationElement()
    {
        return $this->organizationElement;
    }

    public function getAddMoreForm()
    {
        return $this->formElement;
    }

    /**
     * returns setting elements
     * @return App\Core\V201\IatiSettings
     */
    public function getSettingsElement()
    {
        return $this->settingsElement;
    }

    /**
     * @return Excel
     */
    public function getExcel()
    {
        return app('excel');
    }
}
