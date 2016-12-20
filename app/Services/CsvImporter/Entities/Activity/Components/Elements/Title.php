<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class Title
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Title extends Element
{
    /**
     * Csv Header for Title element.
     * @var array
     */
    private $_csvHeader = ['activity_title'];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'activity_title';

    /**
     * @var
     */
    protected $narratives;

    /**
     * @var
     */
    protected $languages;

    /**
     * Template for Title element.
     * @var array
     */
    protected $template = [['narrative' => '', 'language' => '']];

    /**
     * Title constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare Title element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeader))) {
                foreach ($values as $value) {
                    $this->map($value);
                }
            }
        }
    }

    /**
     * Map data from CSV file into Title data format.
     * @param $value
     */
    public function map($value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->data[end($this->_csvHeader)][] = $this->setNarrative($value);
        }
    }

    /**
     * Set the Narrative for the Title element.
     * @param $value
     * @return array
     */
    public function setNarrative($value)
    {
        $narrative          = ['narrative' => $value, 'language' => ''];
        $this->narratives[] = $narrative;

        return $narrative;
    }


    /**
     * Get the languages for the Title element.
     * @return mixed
     */
    public function language()
    {
        return $this->languages;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        return [
            'activity_title'             => 'size:1',
            'activity_title.0.narrative' => 'required'
        ];
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        return [
            'activity_title.size'                 => trans('validation.csv_size', ['attribute' => trans('element.title')]),
            'activity_title.0.narrative.required' => trans('validation.required', ['attribute' => trans('element.title')]),
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
}
