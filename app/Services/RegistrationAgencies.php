<?php namespace App\Services;

use App\Core\Version;

/**
 * Class RegistrationAgencies
 * @package App\Services
 */
class RegistrationAgencies
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $regAgencyRepo;

    /**
     * RegistrationAgencies constructor.
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        $this->regAgencyRepo = $version->getSettingsElement()->getRegistrationAgencyRepository();
    }

    /**
     * returns all registration agencies
     * @return mixed
     */
    public function getRegAgencies()
    {
        return $this->regAgencyRepo->getAgencies();
    }

    /**
     * returns code and name array of all registration agencies
     * @return array
     */
    public function getRegAgenciesCode()
    {
        $agencies = [];
        foreach ($this->getRegAgencies() as $agency) {
            $agencies[sprintf('%s-%s', $agency->country, $agency->short_form)] = $agency->name;
        }

        return $agencies;
    }

    /**
     * creates registration agencies
     * @param $regAgency
     * @return mixed
     */
    public function createRegAgency($regAgency)
    {
        return $this->regAgencyRepo->createRegAgency($regAgency);
    }
}
