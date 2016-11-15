<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class RecipientRegion
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class RecipientRegion extends Element
{
    /**
     * CSV Header of Description with their code
     */
    private $_csvHeaders = ['recipient_region_code', 'recipient_region_percentage'];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'recipient_region';

    /**
     * @var array
     */
    protected $regions = [];

    /**
     * @var array
     */
    protected $percentage = [];

    /**
     * @var array
     */
    protected $template = [['region_code' => '', 'region_vocabulary' => '', 'vocabulary_uri' => '', 'percentage' => '', 'narrative' => ['narrative' => '', 'language' => '']]];

    /**
     * @var
     */
    protected $recipientCountry;

    /**
     * @var int
     */
    protected $totalPercentage = 0;

    protected $fields;

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
        $this->recipientCountry($fields);
    }

    /**
     * Prepare RecipientRegion Element.
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
     * Map data from CSV into RecipientRegion data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setRegion($key, $value, $index);
            $this->setRegionVocabulary($index);
            $this->setVocabularyUri($index);
            $this->setPercentage($key, $value, $index);
            $this->setNarrative($index);
        }
    }

    /**
     * Set Region of RecipientRegion.
     * @param $key
     * @param $value
     * @param $index
     * @return mixed
     */
    protected function setRegion($key, $value, $index)
    {
        if (!isset($this->data['recipient_region'][$index]['region_code'])) {
            $this->data['recipient_region'][$index]['region_code'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $this->regions[] = $value;
            $this->regions   = array_unique($this->regions);

            $this->data['recipient_region'][$index]['region_code'] = $value;
        }
    }

    /**
     * Set Percentage of RecipientRegion.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setPercentage($key, $value, $index)
    {
        if (!isset($this->data['recipient_region'][$index]['percentage'])) {
            $this->data['recipient_region'][$index]['percentage'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $this->percentage[] = $value;

            $this->data['recipient_region'][$index]['percentage'] = $value;
        }
    }

    /**
     * Set Narrative of RecipientRegion.
     * @param $index
     * @return array
     */
    protected function setNarrative($index)
    {
        $narrative = ['narrative' => '', 'language' => ''];

        $this->data['recipient_region'][$index]['narrative'][0] = $narrative;
    }

    /**
     * Set VocabularyUri of RecipientRegion.
     * @param $index
     */
    protected function setVocabularyUri($index)
    {
        $this->data['recipient_region'][$index]['vocabulary_uri'] = '';
    }

    /**
     *Set Region Vocabulary of RecipientRegion.
     * @param $index
     */
    protected function setRegionVocabulary($index)
    {
        $this->data['recipient_region'][$index]['region_vocabulary'] = '';
    }


    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $recipientCountry = $this->recipientCountry->data;

        $this->totalPercentage                           = $this->totalPercentage() + $this->recipientCountry->totalPercentage();
        $this->data['recipient_country']                 = (empty($recipientCountry)) ? '' : $recipientCountry;
        $this->data['recipient_region_total_percentage'] = getVal($this->data, ['recipient_region'], []);
        $this->data['total_percentage']                  = $this->totalPercentage;
        $this->validator                                 = $this->factory->sign($this->data())
                                                                         ->with($this->rules(), $this->messages())
                                                                         ->getValidatorInstance();
        $this->setValidity();
        unset($this->data['recipient_region_total_percentage']);
        unset($this->data['recipient_country']);

        return $this;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $codes = $this->validRecipientRegion();
        $rules = [];

        if (count($this->fields) == 20) {
            $rules = [
                'recipient_region' => sprintf('required_if:recipient_country,%s', ''),
            ];
        }
        ($this->data['recipient_country'] != '' && (array_key_exists('recipient_region', $this->data)))
            ? $rules['total_percentage'] = 'recipient_country_region_percentage_sum' : null;

        ($this->data['recipient_country'] == '' && array_key_exists('recipient_region', $this->data))
            ? $rules['recipient_region_total_percentage'] = 'percentage_sum' : null;

        foreach (getVal($this->data(), ['recipient_region'], []) as $key => $value) {
            $rules['recipient_region.' . $key . '.region_code'] = sprintf('required_with:recipient_region.%s.percentage|in:%s', $key, $codes);
            $rules['recipient_region.' . $key . '.percentage']  = 'numeric|max:100|min:0';
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
            'recipient_region.required_if'                             => 'Recipient Region is required if Recipient Country is not present.',
            'recipient_region_total_percentage.percentage_sum'         => 'Sum of percentage of Recipient Regions must be equal to 100.',
            'total_percentage.recipient_country_region_percentage_sum' => 'Sum of percentage of Recipient Country and Recipient Region must be equal to 100.'
        ];

        foreach (getVal($this->data(), ['recipient_region'], []) as $key => $value) {
            $messages['recipient_region.' . $key . '.region_code.required_with'] = 'Recipient region code is required with Percentage.';
            $messages['recipient_region.' . $key . '.region_code.in']            = 'Entered Recipient region code is invalid.';
            $messages['recipient_region.' . $key . '.percentage.numeric']        = 'Percentage must be numeric.';
            $messages['recipient_region.' . $key . '.percentage.max']            = 'Percentage cannot be more than 100.';
            $messages['recipient_region.' . $key . '.percentage.min']            = 'Percentage cannot be less than 0.';
        }

        return $messages;
    }

    /**
     * Return Valid Recipient Region Codes.
     * @return string
     */
    protected function validRecipientRegion()
    {
        $recipientRegionCodeList = $this->loadCodeList('Region', 'V201');
        $codes                   = [];

        array_walk(
            $recipientRegionCodeList['Region'],
            function ($regionCode) use (&$codes) {
                $codes[] = $regionCode['code'];
            }
        );

        return implode(',', $codes);
    }

    /**
     * Store Recipient Country Object.
     * @param $fields
     */
    protected function recipientCountry($fields)
    {
        $this->recipientCountry = app()->make(RecipientCountry::class, [$fields]);
    }

    /**
     * Calculate Total Percentage of Recipient Region.
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
