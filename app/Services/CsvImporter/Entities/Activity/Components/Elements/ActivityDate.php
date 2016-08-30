<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ActivityDate
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ActivityDate extends Element
{
    /**
     * Csv Headers for the ActivityDate element.
     * @var array
     */
    private $_csvHeaders = ['actual_start_date' => 2, 'actual_end_date' => 4, 'planned_start_date' => 1, 'planned_end_date' => 3];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'activity_date';

    /**
     * Template for the ActivityDate element.
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
     * @var
     */
    protected $dates;

    protected $actualDates = [];

    protected $plannedDates = [];

    protected $activityDate = [];

    /**
     * ActivityDate constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare ActivityDate element.
     * @param $fields
     */
    public function prepare($fields)
    {
        $index = 0;

        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, $this->_csvHeaders)) {
                foreach ($values as $value) {
                    $this->map($key, $value, $index);
                    $index ++;
                }
            }
        }
    }

    /**
     * Map data from CSV file into the ActivityDate data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, &$index)
    {
        if (!(is_null($value) || $value == "")) {
            $type                                               = $this->setType($key);
            $this->data['activity_date'][$index]['date']        = $this->setDate($value);
            $this->data['activity_date'][$index]['type']        = $type;
            $this->data['activity_date'][$index]['narrative'][] = $this->setNarrative($value);
        }
    }

    /**
     * Set the type for ActivityDate element.
     * @param $key
     * @return mixed
     */
    public function setType($key)
    {
        $this->types[] = $key;
        $this->types   = array_unique($this->types);

        return $this->_csvHeaders[$key];
    }

    /**
     * Set the Date for the ActivityDate element.
     * @param $value
     * @return mixed
     */
    public function setDate($value)
    {
        $this->dates[] = $value;

        return $value;
    }

    /**
     * Set the Narrative for the ActivityDate element.
     * @param $value
     * @return array
     */
    public function setNarrative($value)
    {
        $narrative          = ['narrative' => '', 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'activity_date' => 'required|multiple_activity_date|start_date_required|start_end_date',
        ];

        foreach ($this->actualDates as $index => $date) {
            foreach ($date as $key => $value) {
                $rules['activity_date.' . $index]                        = 'actual_date';
                $rules['activity_date.' . $index . '.' . $key . '.date'] = 'date_format:Y-m-d|actual_date';
            }
        }

        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $messages = [
            'activity_date.required'               => 'Activity Date is required.',
            'activity_date.multiple_activity_date' => 'Multiple Activity dates are not allowed.',
            'activity_date.start_date_required'    => 'Actual Start Date or Planned Start Date is required.',
            'activity_date.start_end_date'         => 'Actual Start Date or Planned Start Date should be before Actual End Date or Planned End Date.',
        ];

        foreach ($this->actualDates as $index => $date) {
            foreach ($date as $key => $value) {
                $messages['activity_date.' . $index . '.actual_date']                   = 'Actual Start Date And Actual End Date cannot exceed present date.';
                $messages['activity_date.' . $index . '.' . $key . '.date.date_format'] = 'Activity Date must be of format Y-m-d.';
            }
        }

        return $messages;
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $this->activityDateRules();

        $this->validator = $this->factory->sign($this->activityDate)
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();

        $this->setValidity();

        return $this;
    }

    /**
     * Append additional rules for Activity Date.
     */
    protected function activityDateRules()
    {
        $this->sortByType();
        $this->activityDate['activity_date'] = array_merge($this->actualDates, $this->plannedDates);
    }

    /**
     * Sort ActivityDate by their type.
     */
    protected function sortByType()
    {
        $dates = array_flip($this->_csvHeaders);

        foreach (getVal($this->data(), ['activity_date'], []) as $key => $value) {
            $type = getVal($dates, [getVal($value, ['type'], '')], '');

            if ($type == $dates[2] || $type == $dates[4]) {
                $this->actualDates[$dates[$this->_csvHeaders[$type]]][] = $value;
            } else {
                $this->plannedDates[$dates[$this->_csvHeaders[$type]]][] = $value;
            }
        }
    }
}
