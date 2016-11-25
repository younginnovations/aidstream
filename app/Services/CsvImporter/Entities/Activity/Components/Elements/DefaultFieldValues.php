<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class DefaultFieldValues
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class DefaultFieldValues extends Element
{

    /**
     * @var array
     */
    protected $_csvHeaders = ['activity_default_currency', 'activity_default_language', 'humanitarian'];

    /**
     * @var string
     */
    protected $index = 'default_field_values';

    /**
     * DefaultFieldValues constructor.
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
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeaders))) {
                foreach ($values as $index => $value) {
                    $this->map($key, $value, $index);
                }
            }
        }
    }

    /**
     * Map data from CSV file into Default Field Values data format.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function map($key, $value, $index)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setLinkedDataUri($key, $value, $index);
            $this->setDefaultLanguage($key, $value, $index);
            $this->setDefaultCurrency($key, $value, $index);
            $this->setDefaultHierarchy($key, $value, $index);
            $this->setDefaultCollaborationType($key, $value, $index);
            $this->setDefaultFlowType($key, $value, $index);
            $this->setDefaultFinanceType($key, $value, $index);
            $this->setDefaultAidType($key, $value, $index);
            $this->setDefaultTiedStatus($key, $value, $index);
            $this->setHumanitarian($key, $value, $index);
        }
    }

    /**
     * Set linked data uri for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setLinkedDataUri($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['linked_data_uri'])) {
            $this->data['default_field_values'][$index]['linked_data_uri'] = '';
        }
        $this->data['default_field_values'][$index]['linked_data_uri'] = '';
    }

    /**
     * Set language for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultLanguage($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_language'])) {
            $this->data['default_field_values'][$index]['default_language'] = '';
        }
        if ($key == $this->_csvHeaders[1]) {
            $this->data['default_field_values'][$index]['default_language'] = strtolower($value);
        }
    }

    /**
     * Set currency for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultCurrency($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_currency'])) {
            $this->data['default_field_values'][$index]['default_currency'] = '';
        }
        if ($key == $this->_csvHeaders[0]) {
            $this->data['default_field_values'][$index]['default_currency'] = strtoupper($value);
        }
    }

    /**
     * Set hierarchy for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultHierarchy($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_hierarchy'])) {
            $this->data['default_field_values'][$index]['default_hierarchy'] = '';
        }
        if (array_key_exists('default_currency', $this->data['default_field_values'][$index])) {
            $this->data['default_field_values'][$index]['default_hierarchy'] = 1;
        }
    }

    /**
     * Set collaboration type for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultCollaborationType($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_collaboration_type'])) {
            $this->data['default_field_values'][$index]['default_collaboration_type'] = '';
        }
        if (array_key_exists('default_hierarchy', $this->data['default_field_values'][$index])) {
            $this->data['default_field_values'][$index]['default_collaboration_type'] = '';
        }
    }

    /**
     * Set flow type for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultFlowType($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_flow_type'])) {
            $this->data['default_field_values'][$index]['default_flow_type'] = '';
        }
        if (array_key_exists('default_flow_type', $this->data['default_field_values'][$index])) {
            $this->data['default_field_values'][$index]['default_flow_type'] = '';
        }
    }

    /**
     * Set finance type for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultFinanceType($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_finance_type'])) {
            $this->data['default_field_values'][$index]['default_finance_type'] = '';
        }
        if (array_key_exists('default_hierarchy', $this->data['default_field_values'][$index])) {
            $this->data['default_field_values'][$index]['default_finance_type'] = '';
        }
    }

    /**
     * Set aid type for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultAidType($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_aid_type'])) {
            $this->data['default_field_values'][$index]['default_aid_type'] = '';
        }
        if (array_key_exists('default_finance_type', $this->data['default_field_values'][$index])) {
            $this->data['default_field_values'][$index]['default_aid_type'] = '';
        }
    }

    /**
     * Set tied status for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setDefaultTiedStatus($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['default_tied_status'])) {
            $this->data['default_field_values'][$index]['default_tied_status'] = '';
        }
        if (array_key_exists('default_aid_type', $this->data['default_field_values'][$index])) {
            $this->data['default_field_values'][$index]['default_tied_status'] = '';
        }
    }

    /**
     * Set humanitarian for the default field values.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setHumanitarian($key, $value, $index)
    {
        if (!isset($this->data['default_field_values'][$index]['humanitarian'])) {
            $this->data['default_field_values'][$index]['humanitarian'] = '';
        }
        if ($key == $this->_csvHeaders[2]) {
            if ((strtolower($value) == 'yes') || (strtolower($value) == 'true')) {
                $value = '1';
            } else if ((strtolower($value) == 'no') || (strtolower($value) == 'false')) {
                $value = '0';
            }

            $this->data['default_field_values'][$index]['humanitarian'] = $value;
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
        return [
            'default_field_values'                    => 'size:1',
            'default_field_values.*.default_currency' => sprintf('in:%s', $this->defaultValueCodeList('Currency', 'V201')),
            'default_field_values.*.default_language' => sprintf('in:%s', $this->defaultValueCodeList('Language', 'V201')),
            'default_field_values.*.humanitarian'     => sprintf('in:%s', '1,0')
        ];
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        return [
            'default_field_values.size'                  => 'Multiple Default Field Values for an activity is not allowed.',
            'default_field_values.*.default_currency.in' => 'Entered Default Currency is invalid.',
            'default_field_values.*.default_language.in' => 'Entered Default Language is invalid.',
            'default_field_values.*.humanitarian.in'     => 'Entered Humanitarian is invalid.'
        ];
    }

    /**
     * Return Codelist of the default Field Values.
     * @param $codeList
     * @param $version
     * @return string
     */
    protected function defaultValueCodeList($codeList, $version)
    {
        list($defaultValueCodeList, $codes) = [$this->loadCodeList($codeList, $version), []];

        array_walk(
            $defaultValueCodeList[$codeList],
            function ($defaultValues) use (&$codes) {
                $codes[] = $defaultValues['code'];
            }
        );

        return implode(',', array_keys(array_flip($codes)));
    }
}