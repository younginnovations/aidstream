<?php namespace App\Services\Activity\ParticipatingOrganizations;

use App\Models\Activity\Activity;
use App\Models\Organization\Organization;

/**
 * Class PartnerOrganization
 * @package App\Services\Activity\ParticipatingOrganizations
 */
class PartnerOrganizationData
{
    /**
     * @var
     */
    protected $activityId;

    /**
     * @var Organization
     */
    protected $reportingOrganization;

    /**
     * @var array
     */
    protected $participatingOrganizations = [];

    /**
     * @var
     */
    protected $organizationRepository;

    /**
     * @var array
     */
    protected $organizations = [];

    /**
     * @var
     */
    protected $partners = null;

    /**
     * @var
     */
    protected $activityPartners = null;

    /**
     * @var
     */
    protected $partnersWithName = null;

    /**
     * Initialize the ParticipatingOrganizationData class.
     *
     * @param Activity $activity
     * @param array    $partnerOrganizationDetails
     * @param          $organizationRepository
     * @return $this
     */
    public function init(Activity $activity, array $partnerOrganizationDetails, $organizationRepository)
    {
        $this->activityId             = $activity->id;
        $this->reportingOrganization  = $activity->organization;
        $this->organizationRepository = $organizationRepository;

        foreach ($partnerOrganizationDetails as $data) {
            $this->participatingOrganizations[] = new ParticipatingOrganization($data);
        }

        return $this;
    }

    /**
     * Sync an Activity's Participating Organizations with the organization's Partner Organizations (OrganizationData).
     */
    public function sync()
    {
        $this->partners         = $this->reportingOrganization->partners();
        $this->partnersWithName = $this->getPartnersWithName();
        $this->activityPartners = $this->activityPartners();

        if ($this->partners->isEmpty()) {
            $this->createNewPartners();
        } else {
            $this->updateOldPartners()
                 ->removeActivity()
                 ->addNewPartners();
        }
    }

    /**
     * Create a new Partner Organization (OrganizationData).
     */
    protected function createNewPartners()
    {
        foreach ($this->participatingOrganizations as $participatingOrganization) {
            if ($participatingOrganization->identifier() !== $this->reportingOrganization->identifier) {
                $value                     = $participatingOrganization->data();
                $value['organization_id']  = $this->reportingOrganization->id;
                $value['name']             = array_get($value, 'narrative', [['narrative' => '', 'language' => '']]);
                $value['used_by']          = [+ $this->activityId];
                $value['is_reporting_org'] = false;
                $value['type']             = array_get($value, 'organization_type', '');
                $value['is_publisher']     = boolval(array_get($value, 'is_publisher', false));

                if (!array_has($this->organizations, $participatingOrganization->identifier())) {
                    $this->organizations[$participatingOrganization->identifier()] = $value;
                }
            }
        }

        foreach ($this->organizations as $organization) {
            $this->organizationRepository->storeOrgData($organization);
        }

        $this->partnersWithName = $this->getPartnersWithName();
    }

    /**
     * Update all old Partner Organizations.
     *
     * @return $this
     */
    protected function updateOldPartners()
    {
        foreach ($this->participatingOrganizations as $participatingOrganization) {
            if ($participatingOrganization->identifier() != $this->reportingOrganization->identifier) {
                if ($participatingOrganization->identifier()) {
                    if ($existingPartner = $this->partners->where('identifier', $participatingOrganization->identifier())->first()) {
                        $this->updatePartner($existingPartner);
                    }
                } else {
                    if ($existingPartner = $this->partnersWithName->where('nameString', $participatingOrganization->name())->first()) {
                        $this->updatePartner($existingPartner);
                    }
                }

            }
        }

        $this->partnersWithName = $this->getPartnersWithName();

        return $this;
    }

    /**
     * Get the partners associated with the current activity.
     *
     * @return array
     */
    protected function activityPartners()
    {
        $activityPartners = [];

        foreach ($this->partners as $partner) {
            if (array_has(array_flip($partner->used_by), $this->activityId)) {
                $activityPartners[] = $partner;
            }
        }

        return $activityPartners;
    }

    /**
     * Remove activity linkage from one or more Partner Organization.
     *
     * @return $this
     */
    protected function removeActivity()
    {
        $identifiers = [];
        $names       = [];

        foreach ($this->participatingOrganizations as $participatingOrganization) {
            $identifiers[] = $participatingOrganization->identifier();
            $names[]       = $participatingOrganization->name();
        }

        $removedOrganizations = $this->partners->filter(
            function ($value, $index) use ($identifiers, $names) {
                if ($value->identifier) {
                    if (!in_array($value->identifier, $identifiers)) {
                        return $value;
                    }
                } else {
                    if (!in_array($value->nameString, $names)) {
                        return $value;
                    }
                }
            }
        );

        foreach ($removedOrganizations as $organization) {
            $oldUsed = array_flip($organization->used_by);
            unset($oldUsed[$this->activityId]);
            $organization->used_by = array_flip($oldUsed);

            if (array_key_exists('nameString', $organization->toArray())) {
                unset($organization->nameString);
            }

            $organization->save();
        }

        $this->partnersWithName = $this->getPartnersWithName();

        return $this;
    }

    /**
     * Add new partner organisations if any.
     *
     * @return $this
     */
    protected function addNewPartners()
    {
        foreach ($this->participatingOrganizations as $participatingOrganization) {
            if ($participatingOrganization->identifier() !== $this->reportingOrganization->identifier) {
                if ($participatingOrganization->identifier()) {
                    if (!($existingPartner = $this->partners->where('identifier', $participatingOrganization->identifier())->first())) {
                        $this->createPartnerOrganization($participatingOrganization);
                    }
                } else {
                    if (!$existingPartner = $this->partnersWithName->where('nameString', $participatingOrganization->name())->first()) {
                        $this->createPartnerOrganization($participatingOrganization);
                    }
                }
            }
        }

        $this->partnersWithName = $this->getPartnersWithName();

        return $this;
    }

    /**
     * Create a new Partner Organisation (OrganizationData).
     *
     * @param ParticipatingOrganization $participatingOrganization
     */
    protected function createPartnerOrganization(ParticipatingOrganization $participatingOrganization)
    {
        $value                     = $participatingOrganization->data();
        $value['organization_id']  = $this->reportingOrganization->id;
        $value['name']             = array_get($value, 'narrative', [['narrative' => '', 'language' => '']]);
        $value['used_by']          = [+ $this->activityId];
        $value['is_reporting_org'] = false;
        $value['type']             = array_get($value, 'organization_type', '');
        $value['is_publisher']     = boolval(array_get($value, 'is_publisher', false));

        $this->organizationRepository->storeOrgData($value);
    }

    /**
     * Get the collection of partners with the name attribute included.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getPartnersWithName()
    {
        $partnerOrganizations = [];

        foreach ($this->partners as $index => $partner) {
            $tempOrg                      = $partner;
            $tempOrg['nameString']        = $partner->actualName();
            $partnerOrganizations[$index] = $tempOrg;
        }

        return collect($partnerOrganizations);
    }

    /**
     * Update an existing partner.
     *
     * @param $existingPartner
     */
    protected function updatePartner($existingPartner)
    {
        if (!array_has(array_flip($existingPartner->used_by), $this->activityId)) {
            $usedBy                   = $existingPartner->used_by;
            $usedBy[]                 = $this->activityId;
            $existingPartner->used_by = array_unique($usedBy);

            if (array_key_exists('nameString', $existingPartner->toArray())) {
                unset($existingPartner->nameString);
            }

            $existingPartner->save();
        }
    }
}
