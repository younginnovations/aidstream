<?php namespace App\Services\Activity\ParticipatingOrganizations;


/**
 * Class ParticipatingOrganization
 * @package App\Services\Activity\ParticipatingOrganizations
 */
/**
 * Class ParticipatingOrganization
 * @package App\Services\Activity\ParticipatingOrganizations
 */
/**
 * Class ParticipatingOrganization
 * @package App\Services\Activity\ParticipatingOrganizations
 */
class ParticipatingOrganization
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @var mixed|string
     */
    protected $name = '';

    /**
     * @var mixed|string
     */
    protected $identifier = '';

    /**
     * @var mixed
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $role;

    /**
     * ParticipatingOrganization constructor.
     * @param array $participatingOrganization
     */
    public function __construct(array $participatingOrganization)
    {
        $this->name       = array_get($participatingOrganization, 'narrative.0.narrative', '');
        $this->identifier = array_get($participatingOrganization, 'identifier', '');
        $this->data       = $participatingOrganization;
        $this->type       = array_get($participatingOrganization, 'organization_type', '');
        $this->role       = array_get($participatingOrganization, 'organization_role', '');
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return mixed|string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed|string
     */
    public function identifier()
    {
        return $this->identifier;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return array_get($this->data, $key, null);
    }

    public function type()
    {
        return $this->type;
    }
}
