<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class Location
 * @package App\Core\V201\Requests\Activity
 */
class Location extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForLocation($this->get('location'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForLocation($this->get('location'));
    }

    /**
     * returns rules for location form
     * @param $formFields
     * @return array
     */
    protected function getRulesForLocation($formFields)
    {
        $rules = [];
        foreach ($formFields as $locationIndex => $location) {
            $locationForm = 'location.' . $locationIndex;
            $rules        = array_merge(
                $rules,
                $this->getRulesForLocationId($location['location_id'], $locationForm),
                $this->getRulesForName($location['name'], $locationForm),
                $this->getRulesForLocationDescription($location['location_description'], $locationForm),
                $this->getRulesForActivityDescription($location['activity_description'], $locationForm),
                $this->getRulesForAdministrative($location['administrative'], $locationForm),
                $this->getRulesForPoint($location['point'], $locationForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for location form
     * @param $formFields
     * @return array
     */
    protected function getMessagesForLocation($formFields)
    {
        $messages = [];
        foreach ($formFields as $locationIndex => $location) {
            $locationForm = 'location.' . $locationIndex;
            $messages     = array_merge(
                $messages,
                $this->getMessagesForLocationId($location['location_id'], $locationForm),
                $this->getMessagesForName($location['name'], $locationForm),
                $this->getMessagesForLocationDescription($location['location_description'], $locationForm),
                $this->getMessagesForActivityDescription($location['activity_description'], $locationForm),
                $this->getMessagesForAdministrative($location['administrative'], $locationForm),
                $this->getMessagesForPoint($location['point'], $locationForm)
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
    protected function getRulesForLocationId($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $locationIdIndex => $locationId) {
            $locationIdForm = sprintf('%s.location_id.%s', $formBase, $locationIdIndex);
            if ($locationId['code'] != "") {
                $rules[sprintf('%s.vocabulary', $locationIdForm)] = 'required_with:' . sprintf('%s.code', $locationIdForm);
            }
            if ($locationId['vocabulary'] != "") {
                $rules[sprintf('%s.code', $locationIdForm)] = 'required_with:' . sprintf('%s.vocabulary', $locationIdForm);
            }
        }
        return $rules;
    }

    /**
     * returns messages for location id
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForLocationId($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $locationIdIndex => $locationId) {
            $locationIdForm = sprintf('%s.location_id.%s', $formBase, $locationIdIndex);
            if ($locationId['code'] != "") {
                $messages[sprintf('%s.vocabulary.required_with', $locationIdForm)] = 'Vocabulary is required when Code is present';
            }
            if ($locationId['vocabulary'] != "") {
                $messages[sprintf('%s.code.required_with', $locationIdForm)] = 'Code is required when Vocabulary is present';
            }
        }
        return $messages;
    }

    /**
     * returns rules for name
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForName($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $nameIndex => $name) {
            $narrativeForm = sprintf('%s.name.%s', $formBase, $nameIndex);
            $rules         = array_merge($rules, $this->getRulesForNarrative($name['narrative'], $narrativeForm));
        }

        return $rules;
    }

    /**
     * returns messages for name
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForName($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $nameIndex => $name) {
            $narrativeForm = sprintf('%s.name.%s', $formBase, $nameIndex);
            $messages      = array_merge($messages, $this->getMessagesForNarrative($name['narrative'], $narrativeForm));
        }

        return $messages;
    }

    /**
     * returns rules for location description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForLocationDescription($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.location_description.%s', $formBase, $descriptionIndex);
            $rules         = array_merge($rules, $this->getRulesForNarrative($description['narrative'], $narrativeForm));
        }

        return $rules;
    }

    /**
     * returns messages for location description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForLocationDescription($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.location_description.%s', $formBase, $descriptionIndex);
            $messages      = array_merge($messages, $this->getMessagesForNarrative($description['narrative'], $narrativeForm));
        }

        return $messages;
    }

    /**
     * returns rules for activity description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForActivityDescription($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.activity_description.%s', $formBase, $descriptionIndex);
            $rules         = array_merge($rules, $this->getRulesForNarrative($description['narrative'], $narrativeForm));
        }

        return $rules;
    }

    /**
     * returns messages for activity description
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForActivityDescription($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.activity_description.%s', $formBase, $descriptionIndex);
            $messages      = array_merge($messages, $this->getMessagesForNarrative($description['narrative'], $narrativeForm));
        }

        return $messages;
    }

    /**
     * returns rules for administrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForAdministrative($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $administrativeIndex => $administrative) {
            $administrativeForm                              = sprintf('%s.administrative.%s', $formBase, $administrativeIndex);
            $rules[sprintf('%s.level', $administrativeForm)] = 'min:0|integer';
        }

        return $rules;
    }

    /**
     * returns messages for administrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForAdministrative($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $administrativeIndex => $administrative) {
            $administrativeForm                                         = sprintf('%s.administrative.%s', $formBase, $administrativeIndex);
            $messages[sprintf('%s.level.integer', $administrativeForm)] = 'Level should be a non-negative integer.';
        }

        return $messages;
    }

    /**
     * returns rules for point
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForPoint($formFields, $formBase)
    {
        $rules                                     = [];
        $pointForm                                 = sprintf('%s.point.0', $formBase);
        $rules[sprintf('%s.srs_name', $pointForm)] = 'required';
        $positionForm                              = sprintf('%s.position.0', $pointForm);
        $latitude                                  = sprintf('%s.latitude', $positionForm);
        $longitude                                 = sprintf('%s.longitude', $positionForm);
        $rules[$latitude]                          = sprintf('required_with:%s|numeric', $longitude);
        $rules[$longitude]                         = sprintf('required_with:%s|numeric', $latitude);

        return $rules;
    }

    /**
     * returns messages for point
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForPoint($formFields, $formBase)
    {
        $messages                                                       = [];
        $pointForm                                                      = sprintf('%s.point.0', $formBase);
        $messages[sprintf('%s.srs_name.required', $pointForm)]          = 'SRS name is required.';
        $positionForm                                                   = sprintf('%s.position.0', $pointForm);
        $messages[sprintf('%s.latitude.required_with', $positionForm)]  = 'Latitude is required when Longitude is present.';
        $messages[sprintf('%s.latitude.numeric', $positionForm)]        = 'Latitude should be numeric.';
        $messages[sprintf('%s.longitude.required_with', $positionForm)] = 'Longitude is required when Latitude is present.';
        $messages[sprintf('%s.longitude.numeric', $positionForm)]       = 'Longitude should be numeric.';

        return $messages;
    }
}
