<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ActivityScope
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityScope extends Element
{

    /**
     * Csv Header for ActivityScope element.
     * @var array
     */
    private $_csvHeader = ['activity_scope'];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'activity_scope';

    /**
     * ActivityScope constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare the IATI Element.
     * @param $fields
     */
    protected function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeader))) {
                foreach ($values as $value) {
                    $this->map($value, $values);
                }
            }
        }
    }

    /**
     * Map data from CSV file into ActivitySec data format.
     * @param $value
     * @param $values
     */
    protected function map($value, $values)
    {
        if (!(is_null($value) || $value == "")) {
            (count(array_filter($values)) == 1) ? $this->data[$this->csvHeader()] = $value : $this->data[$this->csvHeader()][] = $value;
        }
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
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $rules[$this->csvHeader()] = (is_array(getVal($this->data, [$this->csvHeader()]))) ? 'size:1' : sprintf('in:%s', $this->validActivityScope());

        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        return [
            $this->csvHeader() . '.size' => 'Multiple Activity Scopes are not allowed.',
            $this->csvHeader() . '.in'   => 'Entered Activity Scope is invalid.',
        ];
    }

    /**
     * Get the valid ActivityScope from the ActivityScope codelist as a string.
     * @return string
     */
    protected function validActivityScope()
    {
        list($activityStatusCodeList, $codes) = [$this->loadCodeList('ActivityScope', 'V201'), []];

        array_walk(
            $activityStatusCodeList['ActivityScope'],
            function ($activityStatus) use (&$codes) {
                $codes[] = $activityStatus['code'];
            }
        );

        return implode(',', array_keys(array_flip($codes)));
    }

    /**
     * Get the Csv header for ActivityStatus.
     * @return mixed
     */
    protected function csvHeader()
    {
        return end($this->_csvHeader);
    }
}