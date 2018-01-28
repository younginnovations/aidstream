<?php namespace App\Services\Activity\ParticipatingOrganizations;

use App\Core\V201\Traits\GetCodes;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;

/**
 * Class PartnerOrganization
 * @package App\Services\Activity\ParticipatingOrganizations
 */
class PartnerOrganizationData
{
    use GetCodes;

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
     * @var
     */
    protected $cleanData;

    protected $countries = [];

    /**
     * Initialize the ParticipatingOrganizationData class.
     *
     * @param Activity $activity
     * @param array    $partnerOrganizationDetails
     * @param          $organizationRepository
     * @return $this
     */
    public function init(Activity $activity, array $partnerOrganizationDetails, $organizationRepository, $cleanData = null)
    {
        $this->activityId             = $activity->id;
        $this->reportingOrganization  = $activity->organization;
        $this->organizationRepository = $organizationRepository;
        $this->cleanData              = $cleanData;
        $this->countries              = $this->getNameWithCode('Organization', 'Country');

        $this->activity = $activity;

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
        $participatingOrganizationData = [];
        $this->partners                = $this->reportingOrganization->partners();
        $this->partnersWithName        = $this->getPartnersWithName();
        $this->activityPartners        = $this->activityPartners();

        if ($this->partners->isEmpty()) {
            $this->createNewPartners();
        } else {
            $this->updateOldPartners()
//                 ->removeActivity()
                 ->addNewPartners();
        }

        foreach ($this->participatingOrganizations as $organization) {
            $participatingOrganizationData[] = $organization->data();
        }

        return $participatingOrganizationData;
    }

    /**
     * Create a new Partner Organization (OrganizationData).
     */
    protected function createNewPartners()
    {
        foreach ($this->participatingOrganizations as $participatingOrganization) {
            if ($participatingOrganization->identifier() === $this->reportingOrganization->identifier) {
                if (trim($participatingOrganization->name()) !== trim($this->reportingOrganization->name)
                    || $participatingOrganization->type() !== array_get($this->reportingOrganization->reporting_org, '0.reporting_organization_type', '')) {

                    $value                    = $participatingOrganization->data();
                    $value['organization_id'] = $this->reportingOrganization->id;

                    if ($this->needsToBeCleaned()) {
                        if ($data = $this->cleanData->where('identifier', $participatingOrganization->identifier())->first()) {
                            $value['name']    = [['narrative' => $data->validnames, 'language' => $data->validlang]];
                            $value['type']    = $data->validtype;
                            $value['country'] = $data->validcountry;
                        } else {
                            $name             = array_get($value, 'narrative.0.narrative', '');
                            $language         = array_get($value, 'narrative.0.language', '');
                            $value['name']    = [['narrative' => trim($name), 'language' => $language]];
                            $value['type']    = array_get($value, 'organization_type', '');
                            $value['country'] = $this->getCountry($participatingOrganization->identifier());
                        }
                    } else {
                        $name             = array_get($value, 'narrative.0.narrative', '');
                        $language         = array_get($value, 'narrative.0.language', '');
                        $value['name']    = [['narrative' => trim($name), 'language' => $language]];
                        $value['type']    = array_get($value, 'organization_type', '');
                        $value['country'] = $this->getCountry($participatingOrganization->identifier());
                    }

                    $value['used_by']          = [+ $this->activityId];
                    $value['is_reporting_org'] = false;
                    $value['is_publisher']     = boolval(array_get($value, 'is_publisher', false));


                    $organization           = $this->organizationRepository->storeOrgData($value);
                    $this->partnersWithName = $this->getPartnersWithName();

                    $participatingOrganization->data['org_data_id'] = $organization->id;
                    $participatingOrganization->data['country']     = array_has($this->countries, $organization->country) ? $organization->country : '';
                }
            }
        }
    }

    /**
     * Update all old Partner Organizations.
     *
     * @return $this
     */
    protected function updateOldPartners()
    {
        foreach ($this->participatingOrganizations as $participatingOrganization) {
            if ($participatingOrganization->identifier() === $this->reportingOrganization->identifier) {
                if (trim($participatingOrganization->name()) !== trim($this->reportingOrganization->name)
                    || $participatingOrganization->type() !== array_get($this->reportingOrganization->reporting_org, '0.reporting_organization_type', '')) {
                    if ($participatingOrganization->identifier()) {
                        $identifier = (string) $participatingOrganization->identifier();
                        if ($existingPartner = $this->partners->where('identifier', $identifier)->first()) {
                            $this->updatePartner($existingPartner);
                        }
                    } else {
                        if ($existingPartner = $this->partnersWithName->where('nameString', trim($participatingOrganization->name()))->first()) {
                            $this->updatePartner($existingPartner);
                        }
                    }

                }

                $this->partners         = $this->reportingOrganization->partners();
                $this->partnersWithName = $this->getPartnersWithName();
            }
        }

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
            if ($participatingOrganization->identifier() === $this->reportingOrganization->identifier) {
                if (trim($participatingOrganization->name()) !== trim($this->reportingOrganization->name)
                    || $participatingOrganization->type() !== array_get($this->reportingOrganization->reporting_org, '0.reporting_organization_type', '')) {

                    if ($participatingOrganization->identifier()) {
                        $identifier = (string) $participatingOrganization->identifier();
                        if (!($existingPartner = $this->partners->where('identifier', $identifier)->first())) {
                            $organization = $this->createPartnerOrganization($participatingOrganization);
                        }
                    } else {
                        if (!$existingPartner = $this->partnersWithName->where('nameString', trim($participatingOrganization->name()))->first()) {
                            $organization = $this->createPartnerOrganization($participatingOrganization);
                        }
                    }

                    if (isset($organization)) {
                        $participatingOrganization->data['org_data_id'] = $organization->id;
                        $participatingOrganization->data['country']     = array_has($this->countries, $organization->country) ? $organization->country : '';
                    }
                }

                $this->partners         = $this->reportingOrganization->partners();
                $this->partnersWithName = $this->getPartnersWithName();
            }
        }

        return $this;
    }

    /**
     * Create a new Partner Organisation (OrganizationData).
     *
     * @param ParticipatingOrganization $participatingOrganization
     * @return
     */
    protected function createPartnerOrganization(ParticipatingOrganization $participatingOrganization)
    {
        $value = $participatingOrganization->data();

        if ($this->needsToBeCleaned()) {
            if ($data = $this->cleanData->where('identifier', $participatingOrganization->identifier())->first()) {
                $value['name']    = [['narrative' => $data->validnames, 'language' => $data->validlang]];
                $value['type']    = $data->validtype;
                $value['country'] = $data->validcountry;
            } else {
                $name             = array_get($value, 'narrative.0.narrative', '');
                $language         = array_get($value, 'narrative.0.language', '');
                $value['name']    = [['narrative' => trim($name), 'language' => $language]];
                $value['type']    = array_get($value, 'organization_type', '');
                $value['country'] = $this->getCountry($participatingOrganization->identifier());
            }
        } else {
            $name             = array_get($value, 'narrative.0.narrative', '');
            $language         = array_get($value, 'narrative.0.language', '');
            $value['name']    = [['narrative' => trim($name), 'language' => $language]];
            $value['type']    = array_get($value, 'organization_type', '');
            $value['country'] = $this->getCountry($participatingOrganization->identifier());
        }

        $value['organization_id']  = $this->reportingOrganization->id;
        $value['used_by']          = [+ $this->activityId];
        $value['is_reporting_org'] = false;
        $value['is_publisher']     = boolval(array_get($value, 'is_publisher', false));

        return $this->organizationRepository->storeOrgData($value);
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

    /**
     * Check if the Partner Organizations' data need to be cleaned.
     *
     * @return bool
     */
    protected function needsToBeCleaned()
    {
        return boolval($this->cleanData);
    }

    /**
     * Get the country by breaking down an Organization Identifier.
     *
     * @param $identifier
     * @return mixed
     */
    protected function getCountry($identifier)
    {
        return array_first(
            explode('-', $identifier),
            function ($pieces) {
                return true;
            }
        );
    }

    /**
     * Store OrganizationData.
     *
     * @return $this
     */
    protected function storeOrganizations()
    {
        foreach ($this->organizations as $organization) {
            $this->organizationRepository->storeOrgData($organization);
        }

        return $this;
    }
}
