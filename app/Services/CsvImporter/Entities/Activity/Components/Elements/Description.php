<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class Description
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Description extends Element
{
    /**
     * CSV Header of Description with their code.
     */
    private $_csvHeaders = ['activity_description_general' => 1, 'activity_description_objectives' => 2, 'activity_description_target_groups' => 3, 'activity_description_others' => 4];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'description';

    /**
     * @var array
     */
    protected $narratives = [];

    /**
     * @var
     */
    protected $languages;

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var array
     */
    protected $template = [['type' => '', 'narrative' => ['narrative' => '', 'language' => '']]];

    /**
     * Description constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare the Description element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, $this->_csvHeaders)) {
                foreach ($values as $value) {
                    $this->map($key, $value);
                }
            }
        }
    }

    /**
     * Map data from CSV file into Description data format.
     * @param $key
     * @param $value
     */
    public function map($key, $value)
    {
        if (!(is_null($value) || $value == "")) {
            $type                                            = $this->setType($key);
            $this->data['description'][$type]['type']        = $type;
            $this->data['description'][$type]['narrative'][] = $this->setNarrative($value);
        }
    }

    /**
     * Set the type for the Description element.
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
     * Set the Narrative for the Description element.
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
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'description' => 'required|min:1'
        ];

        foreach (getVal($this->data(), ['description'], []) as $key => $value) {
            $rules['description.' . $key . '.narrative'] = 'size:1';
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
            'description.size' => 'At least one type of description is required.'
        ];

        foreach (getVal($this->data(), ['description'], []) as $key => $value) {
            $messages['description.' . $key . '.narrative.size'] = 'Multiple narratives for Description with the same type is not allowed.';
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
