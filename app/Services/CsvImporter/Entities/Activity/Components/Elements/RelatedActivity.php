<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class RelatedActivity
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class RelatedActivity extends Element
{
    /**
     * Csv Header for RelatedActivity element.
     * @var array
     */
    private $_csvHeaders = ['related_activity_identifier', 'related_activity_type'];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'related_activity';

    /**
     * RelatedActivity constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare RelatedActivity element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeaders))) {
                foreach ($values as $index => $value) {
                    $this->map($key, $index, $value);
                }
            }
        }
    }

    /**
     * Map data from CSV file into RelatedActivity data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $index, $value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setRelatedActivityIdentifier($key, $value, $index);
            $this->setRelatedActivityType($key, $value, $index);
        }
    }

    /**
     * Maps RelatedActivity Identifiers
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setRelatedActivityIdentifier($key, $value, $index)
    {
        if (!isset($this->data['related_activity'][$index]['activity_identifier'])) {
            $this->data['related_activity'][$index]['activity_identifier'] = '';
        }

        if ($key == $this->_csvHeaders[0]) {
            $this->data['related_activity'][$index]['activity_identifier'] = $value;
        }
    }

    /**
     * Maps RelatedActivity Type
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setRelatedActivityType($key, $value, $index)
    {
        if (!isset($this->data['related_activity'][$index]['relationship_type'])) {
            $this->data['related_activity'][$index]['relationship_type'] = '';
        }

        if ($key == $this->_csvHeaders[1]) {
            $relatedActivityType = $this->loadCodeList('RelatedActivityType', 'V201');

            foreach ($relatedActivityType['RelatedActivityType'] as $type) {
                if (ucwords($value) == $type['name']) {
                    $value = $type['code'];
                    break;
                }
            }

            $this->data['related_activity'][$index]['relationship_type'] = $value;
        }
    }

    /**
     * Provides RelatedActivity Codes
     * @return string
     */
    protected function relatedActivityCode()
    {
        $codes = [];

        $relatedActivityType = $this->loadCodeList('RelatedActivityType', 'V201');
        foreach ($relatedActivityType['RelatedActivityType'] as $type) {
            $codes[] = $type['code'];
        }

        return implode(',', $codes);
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $rules = [];

        foreach (getVal($this->data(), ['related_activity'], []) as $key => $value) {
            $rules['related_activity.' . $key . '.activity_identifier'] = sprintf(
                'required_unless:%s,%s',
                'related_activity.' . $key . '.relationship_type',
                ''
            );
            $rules['related_activity.' . $key . '.relationship_type']   = sprintf(
                'required_unless:%s,%s|in:%s',
                'related_activity.' . $key . '.activity_identifier',
                '',
                $this->relatedActivityCode()
            );
        }

        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach (getVal($this->data(), ['related_activity'], []) as $key => $value) {
            $messages['related_activity.' . $key . '.activity_identifier.required_unless'] = trans('validation.required', ['attribute' => trans('elementForm.related_activity_identifier')]);
            $messages['related_activity.' . $key . '.relationship_type.required_unless']   = trans('validation.required', ['attribute' => trans('elementForm.related_activity_relationship_type')]);
            $messages['related_activity.' . $key . '.relationship_type.in']                = trans('validation.code_list', ['attribute' => trans('elementForm.related_activity_relationship_type')]);
        }

        return $messages;
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
}
