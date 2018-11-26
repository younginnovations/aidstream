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
                $this->getRulesForLocationReach($location['location_reach'], $locationForm),
                $this->getRulesForLocationId($location['location_id'], $locationForm),
                $this->getRulesForName($location['name'], $locationForm),
                $this->getRulesForLocationDescription($location['location_description'], $locationForm),
                $this->getRulesForActivityDescription($location['activity_description'], $locationForm),
                $this->getRulesForAdministrative($location['administrative'], $locationForm),
                $this->getRulesForPoint($location['point'], $locationForm),
                $this->getRulesForExactness($location['exactness'], $locationForm),
                $this->getRulesForLocationClass($location['location_class'], $locationForm),
                $this->getRulesForFeatureDesignation($location['feature_designation'], $locationForm)
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
                $this->getMessagesForLocationReach($location['location_reach'], $locationForm),
                $this->getMessagesForLocationId($location['location_id'], $locationForm),
                $this->getMessagesForName($location['name'], $locationForm),
                $this->getMessagesForLocationDescription($location['location_description'], $locationForm),
                $this->getMessagesForActivityDescription($location['activity_description'], $locationForm),
                $this->getMessagesForAdministrative($location['administrative'], $locationForm),
                $this->getMessagesForPoint($location['point'], $locationForm),
                $this->getMessagesForExactness($location['exactness'], $locationForm),
                $this->getMessagesForLocationClass($location['location_class'], $locationForm),
                $this->getMessagesForFeatureDesignation($location['feature_designation'], $locationForm)
            );
        }

        return $messages;
    }

    /**
     * returns rules for location reach
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getRulesForLocationReach($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $locationReachIndex => $locationReach) {
            $locationReachForm = sprintf('%s.location_reach.%s', $formBase, $locationReachIndex);
            $rules[sprintf('%s.code', $locationReachForm)] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for location reach
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getMessagesForLocationReach($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $locationReachIndex => $locationReach) {
            $locationReachForm = sprintf('%s.location_reach.%s', $formBase, $locationReachIndex);
            $messages[sprintf('%s.code.required', $locationReachForm)] = 'Code is required';
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
            $rules[sprintf('%s.vocabulary', $locationIdForm)]= 'required';
            $rules[sprintf('%s.code', $locationIdForm)] = 'required';
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
            $messages[sprintf('%s.vocabulary.required', $locationIdForm)] = 'Vocabulary is required.';
            $messages[sprintf('%s.code.required', $locationIdForm)] = 'Code is required.';
            if ($locationId['code'] != "") {
                $messages[sprintf('%s.vocabulary.required_with', $locationIdForm)] = trans(
                    'validation.required_with',
                    ['attribute' => trans('elementForm.vocabulary'), 'values' => trans('elementForm.code')]
                );
            }
            if ($locationId['vocabulary'] != "") {
                $messages[sprintf('%s.code.required_with', $locationIdForm)] = trans(
                    'validation.required_with',
                    ['attribute' => trans('elementForm.code'), 'values' => trans('elementForm.vocabulary')]
                );
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
            $rules[sprintf('%s.vocabulary', $administrativeForm)] = 'required';
            $rules[sprintf('%s.code', $administrativeForm)] = 'required';
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
            $messages[sprintf('%s.vocabulary.required', $administrativeForm)] = 'Vocabulary is required';
            $messages[sprintf('%s.code.required', $administrativeForm)] = 'Code is required';
            $messages[sprintf('%s.level.integer', $administrativeForm)] = trans('validation.integer', ['attribute' => trans('elementForm.level')]);
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
        $messages[sprintf('%s.srs_name.required', $pointForm)]          = trans('validation.required', ['attribute' => trans('elementForm.srs_name')]);
        $positionForm                                                   = sprintf('%s.position.0', $pointForm);
        $messages[sprintf('%s.latitude.required_with', $positionForm)]  = trans('validation.required_with', ['attribute' => trans('elementForm.latitude'), 'values' => trans('elementForm.longitude')]);
        $messages[sprintf('%s.latitude.numeric', $positionForm)]        = trans('validation.numeric', ['attribute' => trans('elementForm.latitude')]);
        $messages[sprintf('%s.longitude.required_with', $positionForm)] = trans(
            'validation.required_with',
            ['attribute' => trans('elementForm.longitude'), 'values' => trans('elementForm.latitude')]
        );
        $messages[sprintf('%s.longitude.numeric', $positionForm)]       = trans('validation.numeric', ['attribute' => trans('elementForm.longitude')]);

        return $messages;
    }

    /**
     * returns rules for exactness
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getRulesForExactness($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $exactnessIndex => $exactness) {
            $exactnessForm = sprintf('%s.exactness.%s', $formBase, $exactnessIndex);
            $rules[sprintf('%s.code', $exactnessForm)] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for exactness
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getMessagesForExactness($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $exactnessIndex => $x) {
            $exactnessForm = sprintf('%s.exactness.%s', $formBase, $exactnessIndex);
            $messages[sprintf('%s.code.required', $exactnessForm)] = 'Code is required';
        }

        return $messages;
    }
    /**
     * returns rules for location class
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getRulesForLocationClass($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $locationClassIndex => $locationClass) {
            $locationClassForm = sprintf('%s.location_class.%s', $formBase, $locationClassIndex);
            $rules[sprintf('%s.code', $locationClassForm)] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for location class
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getMessagesForLocationClass($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $locationClassIndex => $locationClass) {
            $locationClassForm = sprintf('%s.location_class.%s', $formBase, $locationClassIndex);
            $messages[sprintf('%s.code.required', $locationClassForm)] = 'Code is required';
        }

        return $messages;
    }
    /**
     * returns rules for feature designation
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getRulesForFeatureDesignation($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $featureDesignationIndex => $featureDesignation) {
            $featureDesignationForm = sprintf('%s.feature_designation.%s', $formBase, $featureDesignationIndex);
            $rules[sprintf('%s.code', $featureDesignationForm)] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for feature designation
     * @param $formFields
     * @param $formBase
     * @return array
     */

    protected function getMessagesForFeatureDesignation($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $featureDesignationIndex => $featureDesignation) {
            $featureDesignationForm = sprintf('%s.feature_designation.%s', $formBase, $featureDesignationIndex);
            $messages[sprintf('%s.code.required', $featureDesignationForm)] = 'Code is required';
        }

        return $messages;
    }
}
