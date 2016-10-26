<?php namespace App\Core\V201\Repositories;

use App\Models\RegistrationAgency as RegistrationAgencyModel;

/**
 * Class RegistrationAgency
 * @package App\Core\V201\Repositories
 */
class RegistrationAgency
{
    /**
     * @var RegistrationAgencyModel
     */
    protected $registrationAgency;

    /**
     * RegistrationAgency constructor.
     * @param RegistrationAgencyModel $registrationAgency
     */
    public function __construct(RegistrationAgencyModel $registrationAgency)
    {
        $this->registrationAgency = $registrationAgency;
    }

    /**
     * returns agencies
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAgencies()
    {
        return $this->registrationAgency->all();
    }

    /**
     * creates registration agency
     * @param $regAgency
     * @return static
     */
    public function createRegAgency($regAgency)
    {
        return $this->registrationAgency->create($regAgency);
    }
}
