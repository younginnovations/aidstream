<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ContactInfo
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ContactInfo extends Element
{
    const TEMPLATE_FILE_PATH = '/Services/CsvImporter/Entities/Activity/Components/Elements/Templates/ContactInfo.json';

    /**
     * Csv Header for ContactInfo element.
     * @var array
     */
    private $_csvHeaders = [
        'contact_type',
        'contact_organization',
        'contact_department',
        'contact_person_name',
        'contact_job_title',
        'contact_telephone',
        'contact_email',
        'contact_website',
        'contact_mailing_address'
    ];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'contact_info';

    /**
     * ContactInfo constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
//        $this->template = $this->loadTemplate();
        $this->factory = $factory;
    }

    /**
     * Prepare ContactInfo element.
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
     * Map data from CSV file into ContactInfo data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $index, $value)
    {
        if (!(is_null($value) || $value == "")) {
            $this->setContactType($key, $value, $index);
            $this->setContactOrganization($key, $value, $index);
            $this->setContactDepartment($key, $value, $index);
            $this->setContactPersonName($key, $value, $index);
            $this->setContactJobTitle($key, $value, $index);
            $this->setContactTelephone($key, $value, $index);
            $this->setContactEmail($key, $value, $index);
            $this->setContactWebsite($key, $value, $index);
            $this->setContactMailingAddress($key, $value, $index);
        }
    }

    /**
     * Maps ContactInfo Identifiers
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setContactType($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['type'])) {
            $this->data['contact_info'][$index]['type'] = '';
        }

        if ($key == $this->_csvHeaders[0]) {
            $relatedActivityType = $this->loadCodeList('ContactType', 'V201');

            foreach ($relatedActivityType['ContactType'] as $type) {
                if (ucwords($value) == $type['name']) {
                    $value = $type['code'];
                    break;
                }
            }

            $this->data['contact_info'][$index]['type'] = $value;
        }
    }

    /**
     * Maps ContactInfo Type
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setContactOrganization($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['organization'][0]['narrative'][0]['narrative'])) {
            $this->data['contact_info'][$index]['organization'][0]['narrative'][0]['narrative'] = '';
        }

        $this->data['contact_info'][$index]['organization'][0]['narrative'][0]['language'] = '';

        if ($key == $this->_csvHeaders[1]) {
            $this->data['contact_info'][$index]['organization'][0]['narrative'][0]['narrative'] = $value;
        }
    }

    protected function setContactDepartment($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['department'][0]['narrative'][0]['narrative'])) {
            $this->data['contact_info'][$index]['department'][0]['narrative'][0]['narrative'] = '';
        }

        $this->data['contact_info'][$index]['department'][0]['narrative'][0]['language'] = '';

        if ($key == $this->_csvHeaders[2]) {
            $this->data['contact_info'][$index]['department'][0]['narrative'][0]['narrative'] = $value;
        }
    }

    protected function setContactPersonName($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['person_name'][0]['narrative'][0]['narrative'])) {
            $this->data['contact_info'][$index]['person_name'][0]['narrative'][0]['narrative'] = '';
        }

        $this->data['contact_info'][$index]['person_name'][0]['narrative'][0]['language'] = '';

        if ($key == $this->_csvHeaders[3]) {
            $this->data['contact_info'][$index]['person_name'][0]['narrative'][0]['narrative'] = $value;
        }
    }

    protected function setContactJobTitle($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['job_title'][0]['narrative'][0]['narrative'])) {
            $this->data['contact_info'][$index]['job_title'][0]['narrative'][0]['narrative'] = '';
        }

        $this->data['contact_info'][$index]['job_title'][0]['narrative'][0]['language'] = '';

        if ($key == $this->_csvHeaders[4]) {
            $this->data['contact_info'][$index]['job_title'][0]['narrative'][0]['narrative'] = $value;
        }
    }

    protected function setContactTelephone($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['telephone'][0]['telephone'])) {
            $this->data['contact_info'][$index]['telephone'][0]['telephone'] = '';
        }

        if ($key == $this->_csvHeaders[5]) {
            $this->data['contact_info'][$index]['telephone'][0]['telephone'] = $value;
        }
    }

    protected function setContactEmail($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['email'][0]['email'])) {
            $this->data['contact_info'][$index]['email'][0]['email'] = '';
        }

        if ($key == $this->_csvHeaders[6]) {
            $this->data['contact_info'][$index]['email'][0]['email'] = $value;
        }
    }

    protected function setContactWebsite($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['website'][0]['website'])) {
            $this->data['contact_info'][$index]['website'][0]['website'] = '';
        }

        if ($key == $this->_csvHeaders[7]) {
            $this->data['contact_info'][$index]['website'][0]['website'] = $value;
        }
    }

    protected function setContactMailingAddress($key, $value, $index)
    {
        if (!isset($this->data['contact_info'][$index]['mailing_address'][0]['narrative'][0]['narrative'])) {
            $this->data['contact_info'][$index]['mailing_address'][0]['narrative'][0]['narrative'] = '';
        }

        $this->data['contact_info'][$index]['mailing_address'][0]['narrative'][0]['language'] = '';

        if ($key == $this->_csvHeaders[8]) {
            $this->data['contact_info'][$index]['mailing_address'][0]['narrative'][0]['narrative'] = $value;
        }
    }

    /**
     * Provides ContactType Code
     * @return string
     */
    protected function contactTypeCode()
    {
        $codes = [];

        $contactType = $this->loadCodeList('ContactType', 'V201');
        foreach ($contactType['ContactType'] as $type) {
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

        $rules['contact_info.*.type'] = sprintf('in:%s', $this->contactTypeCode());
        $rules['contact_info.*.email.0.email'] = 'email';
        $rules['contact_info.*.website.0.website'] = 'url';
        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['contact_info.*.type.in'] = 'Entered Contact Type is not valid.';
        $messages['contact_info.*.email.0.email.email'] = 'Incorrect email address.';
        $messages['contact_info.*.website.0.website.url'] = 'Incorrect website url.';

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
