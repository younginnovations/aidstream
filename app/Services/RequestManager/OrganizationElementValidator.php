<?php namespace App\Services\RequestManager;

use App\Core\Version;
use App\Models\Organization\OrganizationData;

class OrganizationElementValidator
{
    protected $elementValidator;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->elementValidator = $version->getOrganizationElement()->getOrganizationElementValidator();
    }

    /**
     * Validate Organization Schema
     * @param OrganizationData $organization
     * @return mixed
     */
    public function validateOrganization(OrganizationData $organization)
    {
        return $this->elementValidator->validateOrganization($organization);
    }
}
