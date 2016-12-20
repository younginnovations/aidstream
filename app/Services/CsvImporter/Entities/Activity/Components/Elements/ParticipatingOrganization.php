<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ParticipatingOrganization
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ParticipatingOrganization extends Element
{
    /**
     * @var array
     */
    private $_csvHeaders = ['participating_organisation_role', 'participating_organisation_type', 'participating_organisation_name', 'participating_organisation_identifier'];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'participating_organization';

    /**
     * @var array
     */
    protected $template = ['type' => '', 'date' => '', 'narrative' => ['narrative' => '', 'language' => '']];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var
     */
    protected $narratives;

    /**
     * @var array
     */
    protected $orgRoles = [];

    /**
     * ParticipatingOrganisation constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare ParticipatingOrganisation Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeaders))) {
                foreach ($values as $index => $value) {
                    $this->map($key, $value, $index);
                }
            }
        }
    }

    /**
     * Map data from CSV file into ParticipatingOrganisation data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        if (!is_null($value)) {
            $this->setOrganisationRole($key, $value, $index);
            $this->setIdentifier($key, $value, $index);
            $this->setOrganisationType($key, $value, $index);
            $this->data['participating_organization'][$index]['activity_id'] = '';
            $this->setNarrative($key, $value, $index);
        }

    }

    /**
     * Set Organisation Role of Participating Organisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setOrganisationRole($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['organization_role'])) {
            $this->data['participating_organization'][$index]['organization_role'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $validOrganizationRoles = $this->loadCodeList('OrganisationRole', 'V201');

            foreach ($validOrganizationRoles['OrganisationRole'] as $role) {
                if (ucwords($value) == $role['name']) {
                    $value = $role['code'];
                    break;
                }
            }

            $this->orgRoles[] = $value;
            $this->orgRoles   = array_unique($this->orgRoles);

            $this->data['participating_organization'][$index]['organization_role'] = $value;
        }
    }

    /**
     * Set Identifier of Participating Organisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setIdentifier($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['identifier'])) {
            $this->data['participating_organization'][$index]['identifier'] = '';
        }

        if ($key == $this->_csvHeaders[3] && (!is_null($value))) {
            $this->data['participating_organization'][$index]['identifier'] = $value;
        }

    }

    /**
     * Set OrganisationType for Participating Organisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setOrganisationType($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['organization_type'])) {
            $this->data['participating_organization'][$index]['organization_type'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $validOrganizationType = $this->loadCodeList('OrganisationType', 'V201');

            foreach ($validOrganizationType['OrganisationType'] as $type) {
                if (ucwords($value) == $type['name']) {
                    $value = $type['code'];
                    break;
                }
            }

            $this->types[] = $value;
            $this->types   = array_unique($this->types);

            $this->data['participating_organization'][$index]['organization_type'] = $value;
        }
    }

    /**
     * Set Narrative for ParticipatingOrganisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setNarrative($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['narrative'])) {
            $this->data['participating_organization'][$index]['narrative'][] = ['narrative' => '', 'language' => ''];
        } else {
            if ($key == $this->_csvHeaders[2]) {
                foreach ($this->data['participating_organization'][$index]['narrative'] as $d) {
                    $this->data['participating_organization'][$index]['narrative'] = array_filter($d);
                }

                $narrative          = ['narrative' => $value, 'language' => ''];
                $this->narratives[] = $narrative;

                $this->data['participating_organization'][$index]['narrative'][] = $narrative;
            }
        }
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        return [
            'participating_organization'                     => 'required|required_only_one_among:identifier,narrative|funding_implementing_required',
            'participating_organization.*.organization_role' => sprintf('required|in:%s', $this->validOrganizationRoles()),
            'participating_organization.*.organization_type' => sprintf('in:%s', $this->validOrganizationTypes()),
        ];
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        return [
            'participating_organization.required'                      => trans('validation.required', ['attribute' => trans('elementForm.participating_organisation')]),
            'participating_organization.funding_implementing_required' => trans('validation.funding_implementing_required'),
            'participating_organization.*.organization_role.required'  => trans('validation.required', ['attribute' => trans('elementForm.participating_organisation_role')]),
            'participating_organization.required_only_one_among'       => trans(
                'validation.required_only_one_among',
                [
                    'attribute' => trans('elementForm.participating_organisation_identifier'),
                    'values'    => trans('elementForm.participating_organisation_name')
                ]
            ),
            'participating_organization.*.organization_role.in'        => trans('validation.code_list', ['attribute' => trans('elementForm.participating_organisation_role')]),
            'participating_organization.*.organization_type.in'        => trans('validation.code_list', ['attribute' => trans('elementForm.participating_organisation_type')])
        ];
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $this->validator = $this->factory->sign($this->data())
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();

        $this->setValidity();

        return $this;
    }

    /**
     * Get the valid OrganizationRole from the OrganizationRole codelist as a string.
     * @return string
     */
    protected function validOrganizationRoles()
    {
        list($organizationRoleCodeList, $organizationRoles) = [$this->loadCodeList('OrganisationRole', 'V201'), []];

        array_walk(
            $organizationRoleCodeList['OrganisationRole'],
            function ($organizationRole) use (&$organizationRoles) {
                $organizationRoles[] = $organizationRole['code'];
                $organizationRoles[] = $organizationRole['name'];
            }
        );

        return implode(',', array_keys(array_flip($organizationRoles)));
    }

    /**
     * Get the valid OrganizationType from the OrganizationType codelist as a string.
     * @return string
     */
    protected function validOrganizationTypes()
    {
        list($organizationTypeCodeList, $organizationTypes) = [$this->loadCodeList('OrganisationType', 'V201'), []];

        array_walk(
            $organizationTypeCodeList['OrganisationType'],
            function ($organizationType) use (&$organizationTypes) {
                $organizationTypes[] = $organizationType['code'];
                $organizationTypes[] = $organizationType['name'];
            }
        );

        return implode(',', array_keys(array_flip($organizationTypes)));
    }
}
