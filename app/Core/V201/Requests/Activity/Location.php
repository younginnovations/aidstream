<?php namespace App\Core\V201\Requests\Activity;

use App\Core\V201\Requests\Activity\Validation;
use App\Http\Requests\Request;

/**
 * Class Location
 * @package App\Core\V201\Requests\Activity
 */
class Location extends Request
{

    /**
     * @var Validation
     */
    private $validation;

    function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForLocation($this->request->get('location'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForLocation($this->request->get('location'));
    }

    /**
     * returns rules for location form
     * @param $formFields
     * @return array
     */
    public function addRulesForLocation($formFields)
    {
        $rules = [];
        foreach ($formFields as $locationIndex => $location) {
            $locationForm                                                   = 'location.' . $locationIndex;
            $rules[sprintf('%s.location_reach.0.code', $locationForm)]      = 'required';
            $rules[sprintf('%s.exactness.0.code', $locationForm)]           = 'required';
            $rules[sprintf('%s.location_class.0.code', $locationForm)]      = 'required';
            $rules[sprintf('%s.feature_designation.0.code', $locationForm)] = 'required';
            $rules                                                          = array_merge(
                $rules,
                $this->addRulesForLocationId($location['location_id'], $locationForm),
                $this->addRulesForName($location['name'], $locationForm),
                $this->addRulesForLocationDescription($location['location_description'], $locationForm),
                $this->addRulesForActivityDescription($location['activity_description'], $locationForm),
                $this->addRulesForAdministrative($location['activity_description'], $locationForm),
                $this->addRulesForPoint($location['point'], $locationForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for location form
     * @param $formFields
     * @return array
     */
    public function addMessagesForLocation($formFields)
    {
        $messages = [];
        foreach ($formFields as $locationIndex => $location) {
            $locationForm                                                               = 'location.' . $locationIndex;
            $messages[sprintf('%s.location_reach.0.code.required', $locationForm)]      = 'Code is required.';
            $messages[sprintf('%s.exactness.0.code.required', $locationForm)]           = 'Code is required.';
            $messages[sprintf('%s.location_class.0.code.required', $locationForm)]      = 'Code is required.';
            $messages[sprintf('%s.feature_designation.0.code.required', $locationForm)] = 'Code is required.';
            $messages                                                                   = array_merge(
                $messages,
                $this->addMessagesForLocationId($location['location_id'], $locationForm),
                $this->addMessagesForName($location['name'], $locationForm),
                $this->addMessagesForLocationDescription($location['location_description'], $locationForm),
                $this->addMessagesForActivityDescription($location['activity_description'], $locationForm),
                $this->addMessagesForAdministrative($location['activity_description'], $locationForm),
                $this->addMessagesForPoint($location['point'], $locationForm)
            );
        }

        return $messages;
    }

    /**
     * returns rules for location id
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForLocationId($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $locationIdIndex => $locationId) {
            $locationIdForm                                   = sprintf(
                '%s.location_id.%s',
                $formBase,
                $locationIdIndex
            );
            $rules[sprintf('%s.vocabulary', $locationIdForm)] = 'required';
            $rules[sprintf('%s.code', $locationIdForm)]       = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for location id
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForLocationId($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $locationIdIndex => $locationId) {
            $locationIdForm                                               = sprintf(
                '%s.location_id.%s',
                $formBase,
                $locationIdIndex
            );
            $messages[sprintf('%s.vocabulary.required', $locationIdForm)] = 'Vocabulary is required.';
            $messages[sprintf('%s.code.required', $locationIdForm)]       = 'Code is required.';
        }

        return $messages;
    }

    /**
     * returns rules for name
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForName($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $nameIndex => $name) {
            $narrativeForm = sprintf('%s.name.%s', $formBase, $nameIndex);
            $rules         = array_merge(
                $rules,
                $this->validation->addRulesForNarrative($name['narrative'], $narrativeForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for name
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForName($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $nameIndex => $name) {
            $narrativeForm = sprintf('%s.name.%s', $formBase, $nameIndex);
            $messages      = array_merge(
                $messages,
                $this->validation->addMessagesForNarrative($name['narrative'], $narrativeForm)
            );
        }

        return $messages;
    }

    /**
     * returns rules for location description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForLocationDescription($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.location_description.%s', $formBase, $descriptionIndex);
            $rules         = array_merge(
                $rules,
                $this->validation->addRulesForNarrative(
                    $description['narrative'],
                    $narrativeForm
                )
            );
        }

        return $rules;
    }

    /**
     * returns messages for location description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForLocationDescription($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.location_description.%s', $formBase, $descriptionIndex);
            $messages      = array_merge(
                $messages,
                $this->validation->addMessagesForNarrative(
                    $description['narrative'],
                    $narrativeForm
                )
            );
        }

        return $messages;
    }

    /**
     * returns rules for activity description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForActivityDescription($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.activity_description.%s', $formBase, $descriptionIndex);
            $rules         = array_merge(
                $rules,
                $this->validation->addRulesForNarrative(
                    $description['narrative'],
                    $narrativeForm
                )
            );
        }

        return $rules;
    }

    /**
     * returns messages for activity description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForActivityDescription($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.activity_description.%s', $formBase, $descriptionIndex);
            $messages      = array_merge(
                $messages,
                $this->validation->addMessagesForNarrative(
                    $description['narrative'],
                    $narrativeForm
                )
            );
        }

        return $messages;
    }

    /**
     * returns rules for administrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForAdministrative($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $administrativeIndex => $administrative) {
            $administrativeForm                                   = sprintf(
                '%s.administrative.%s',
                $formBase,
                $administrativeIndex
            );
            $rules[sprintf('%s.vocabulary', $administrativeForm)] = 'required';
            $rules[sprintf('%s.code', $administrativeForm)]       = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for administrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForAdministrative($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $administrativeIndex => $administrative) {
            $administrativeForm                                               = sprintf(
                '%s.administrative.%s',
                $formBase,
                $administrativeIndex
            );
            $messages[sprintf('%s.vocabulary.required', $administrativeForm)] = 'Vocabulary is Required';
            $messages[sprintf('%s.code.required', $administrativeForm)]       = 'Code is Required';
        }

        return $messages;
    }

    /**
     * returns rules for point
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForPoint($formFields, $formBase)
    {
        $rules                                         = [];
        $pointForm                                     = sprintf('%s.point.0', $formBase);
        $rules[sprintf('%s.srs_name', $pointForm)]     = 'required';
        $positionForm                                  = sprintf('%s.position.0', $pointForm);
        $rules[sprintf('%s.latitude', $positionForm)]  = 'required';
        $rules[sprintf('%s.longitude', $positionForm)] = 'required';

        return $rules;
    }

    /**
     * returns messages for point
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForPoint($formFields, $formBase)
    {
        $messages                                                  = [];
        $pointForm                                                 = sprintf('%s.point.0', $formBase);
        $messages[sprintf('%s.srs_name.required', $pointForm)]     = 'SRS name is required.';
        $positionForm                                              = sprintf('%s.position.0', $pointForm);
        $messages[sprintf('%s.latitude.required', $positionForm)]  = 'Latitude is required.';
        $messages[sprintf('%s.longitude.required', $positionForm)] = 'Longitude is required.';

        return $messages;
    }

}
