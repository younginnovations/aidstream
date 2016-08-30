<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class RecipientCountry
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class RecipientCountry extends Element
{
    /**
     * CSV Header of Description with their code
     */
    private $_csvHeaders = ['recipient_country_code', 'recipient_country_percentage'];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'recipient_country';

    /**
     * @var array
     */
    protected $countries = [];

    /**
     * @var array
     */
    protected $percentage = [];

    /**
     * @var int
     */
    protected $totalPercentage = 0;

    /**
     * @var
     */
    protected $recipientRegion;

    protected $fields;

    /**
     * @var array
     */
    protected $template = [['country_code' => '', 'percentage' => '', 'narrative' => ['narrative' => '', 'language' => '']]];

    /**
     * Description constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
        $this->fields  = $fields;
    }

    /**
     * Prepare RecipientCountry Element.
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
     * Map data from CSV file into RecipientCountry data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setCountry($key, $value, $index);
            $this->setPercentage($key, $value, $index);
            $this->setNarrative($index);
        }
    }

    /**
     * Set Country for RecipientCountry.
     * @param $key
     * @param $value
     * @param $index
     * @return mixed
     * @internal param $key
     *
     */
    protected function setCountry($key, $value, $index)
    {
        if (!isset($this->data['recipient_country'][$index]['country_code'])) {
            $this->data['recipient_country'][$index]['country_code'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $this->countries[] = $value;
            $this->countries   = array_unique($this->countries);

            $this->data['recipient_country'][$index]['country_code'] = $value;
        }
    }

    /**
     * Set Percentage for RecipientCountry Element.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setPercentage($key, $value, $index)
    {
        if (!isset($this->data['recipient_country'][$index]['percentage'])) {
            $this->data['recipient_country'][$index]['percentage'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $this->percentage[]                                    = $value;
            $this->data['recipient_country'][$index]['percentage'] = $value;
        }
    }

    /**
     * Set Narrative for RecipientCountry Element.
     * @param $index
     * @return array
     */
    protected function setNarrative($index)
    {
        $narrative = ['narrative' => '', 'language' => ''];

        $this->data['recipient_country'][$index]['narrative'][0] = $narrative;
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $this->recipientRegion($this->fields);

        $recipientRegion                                  = $this->recipientRegion->data;
        $this->data['recipient_region']                   = (empty($recipientRegion)) ? '' : $recipientRegion;
        $this->data['recipient_country_total_percentage'] = getVal($this->data, ['recipient_country'], []);
        $this->validator                                  = $this->factory->sign($this->data())
                                                                          ->with($this->rules(), $this->messages())
                                                                          ->getValidatorInstance();
        $this->setValidity();
        unset($this->data['recipient_country_total_percentage']);
        unset($this->data['recipient_region']);

        return $this;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $codes = $this->validRecipientCountry();
        $rules = [];

        if (count($this->fields) == 20) {
            $rules = [
                'recipient_country' => sprintf('required_if:recipient_region,%s', ''),
            ];
        }

        ($this->data['recipient_region'] != '') ?: $rules['recipient_country_total_percentage'] = 'percentage_sum';


        foreach (getVal($this->data(), ['recipient_country'], []) as $key => $value) {
            $rules['recipient_country.' . $key . '.country_code'] = sprintf('required_with:recipient_country.%s.percentage|in:%s', $key, $codes);
            $rules['recipient_country.' . $key . '.percentage']   = sprintf('required_with:recipient_country.%s.country_code', $key);
            $rules['recipient_country.' . $key . '.percentage']   = 'numeric|max:100|min:0';
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
            'recipient_country.required_if' => 'Recipient Country is required if Recipient Region is not present.',
            'percentage_sum'                => 'Sum of percentage of Recipient Country must be 100.'
        ];

        foreach (getVal($this->data(), ['recipient_country'], []) as $key => $value) {
            $messages['recipient_country.' . $key . '.country_code.required_with'] = 'Recipient Country code is required with Percentage.';
            $messages['recipient_country.' . $key . '.country_code.in']            = 'Entered Recipient Country code is invalid.';
            $messages['recipient_country.' . $key . '.percentage.required_with']   = 'Percentage is required with Recipient Country Code.';
            $messages['recipient_country.' . $key . '.percentage.numeric']         = 'Percentage must be numeric.';
            $messages['recipient_country.' . $key . '.percentage.max']             = 'Percentage cannot be more than 100';
            $messages['recipient_country.' . $key . '.percentage.min']             = 'Percentage cannot be less than 0';
        }

        return $messages;
    }

    /**
     * Return valid Recipient Country.
     * @return string
     */
    protected function validRecipientCountry()
    {
        $recipientCountryCodeList = $this->loadCodeList('Country', 'V201', "Organization");
        $codes                    = [];

        array_walk(
            $recipientCountryCodeList['Country'],
            function ($countryCode) use (&$codes) {
                $codes[] = $countryCode['code'];
            }
        );

        return implode(',', $codes);
    }

    /**
     * Store Recipient Region object
     * @param $fields
     */
    protected function recipientRegion($fields)
    {
        $this->recipientRegion = app()->make(RecipientRegion::class, [$fields]);
    }

    /**
     * Calculate total Percentage of Recipient Country.
     * @return int
     */
    public function totalPercentage()
    {
        foreach ($this->percentage as $percentage) {
            $this->totalPercentage += $percentage;
        }

        return $this->totalPercentage;
    }
}
