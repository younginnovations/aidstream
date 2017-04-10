<?php namespace App\Services\CsvImporter\Entities\Activity\Components;


use App\Models\Activity\Activity;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class TransactionRow
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class TransactionRow
{

    /**
     * @var
     */
    protected $activityId;
    /**
     * @var
     */
    protected $version;

    /**
     * @var
     */
    protected $transactionRow;

    /**
     * Path to obtain empty template of transaction element.
     */
    const TRANSACTION_TEMPLATE_PATH = 'Services/CsvImporter/Entities/Activity/Transaction/Template';


    /**
     * Contains mapping of the header file to its respective field.
     *
     * @var array
     */
    protected $headerToFieldMap = [
        'transaction_internal_reference'            => 'reference',
        'humanitarian'                              => 'humanitarian',
        'transaction_type'                          => ['transaction_type' => ['transaction_type_code']],
        'transaction_date'                          => ['transaction_date' => ['date']],
        'transaction_amount'                        => ['value' => 'amount'],
        'transaction_value_date'                    => ['value' => 'date'],
        'transaction_currency'                      => ['value' => 'currency'],
        'transaction_description'                   => ['description' => [['narrative' => ['narrative']]]],
        'sector_vocabulary'                         => ['sector' => ['sector_vocabulary']],
        'sector_code'                               => ['sector' => ['sector_code']],
        'recipient_country_code'                    => ['recipient_country' => ['country_code']],
        'recipient_region_code'                     => ['recipient_region' => ['region_code']],
        'provider_organisation_identifier'          => ['provider_organization' => 'organization_identifier_code'],
        'provider_organisation_type'                => ['provider_organization' => 'type'],
        'provider_organisation_activity_identifier' => ['provider_organization' => 'provider_activity_id'],
        'provider_organisation_description'         => ['provider_organization' => [['narrative' => ['narrative']]]],
        'receiver_organisation_identifier'          => ['receiver_organization' => 'organization_identifier_code'],
        'receiver_organisation_type'                => ['receiver_organization' => 'type'],
        'receiver_organisation_activity_identifier' => ['receiver_organization' => 'receiver_activity_id'],
        'receiver_organisation_description'         => ['receiver_organization' => [['narrative' => ['narrative']]]],
        'disbursement_channel_code'                 => ['disbursement_channel' => ['disbursement_channel_code']],
        'flow_type_code'                            => ['flow_type' => ['flow_type']],
        'finance_type_code'                         => ['finance_type' => ['finance_type']],
        'aid_type_code'                             => ['aid_type' => ['aid_type']],
        'tied_status_code'                          => ['tied_status' => ['tied_status_code']]
    ];

    /**
     * @var Validation
     */
    protected $factory;
    /**
     * @var
     */
    protected $validator;

    /**
     * @var array
     */
    protected $allowedHumanitarianValues = ['yes' => '1', 'no' => '0', 'true' => '1', 'false' => '0'];
    protected $allowedBothCasesField = ['country_code', 'currency', 'aid_type'];
    protected $allowedDoubleValue = ['amount'];
    protected $dateField = ['transaction_value_date'];

    /**
     * TransactionRow constructor.
     * @param            $fields
     * @param            $activityId
     * @param            $version
     * @param Validation $factory
     */
    public function __construct($fields, $activityId, $version, Validation $factory)
    {
        $this->fields     = $fields;
        $this->activityId = $activityId;
        $this->version    = $version;
        $this->factory    = $factory;
    }

    /**
     * Initialize the Row object.
     *
     * @return mixed
     */
    public function init()
    {
        $this->transactionRow['transaction'] = $this->loadTemplate($this->version, 'transaction');

        foreach ($this->fields as $csvHeader => $value) {
            if (array_key_exists($csvHeader, $this->headerToFieldMap) && $value !== "") {
                $this->headerMapper($csvHeader, $value);
            }
        }

        $this->filterSector()
             ->filterHumanitarian()
             ->filterTransactionType();


        return $this;
    }

    /**
     * Maps the header file to its corresponding field.
     *
     * @param $csvHeader
     * @param $value
     */
    protected function headerMapper($csvHeader, $value)
    {
        if (is_array($this->headerToFieldMap[$csvHeader])) {
            foreach ($this->headerToFieldMap[$csvHeader] as $arrayKey => $field) {
                $this->map($arrayKey, $field, $value);
            }
        } else {
            $this->transactionRow['transaction'][$this->headerToFieldMap[$csvHeader]] = (string) $this->filterValue($value, $this->headerToFieldMap[$csvHeader]);
        }
    }

    /**
     * Maps the header file to its corresponding field.
     *
     * @param $arrayKey
     * @param $field
     * @param $value
     */
    public function map($arrayKey, $field, $value)
    {
        if (is_array($field)) {
            foreach ($field as $index => $item) {
                if (is_array($item)) {
                    $this->transactionRow['transaction'][$arrayKey][$index] = array_merge($this->transactionRow['transaction'][$arrayKey][$index], $this->narrative($item, $value));
                } else {
                    $this->transactionRow['transaction'][$arrayKey][$index][$item] = (string) $this->filterValue($value, $item);
                }
            }
        } else {
            $this->transactionRow['transaction'][$arrayKey][0][$field] = (string) $this->filterValue($value, $field);
        }
    }

    /**
     * Maps the narrative of the field.
     *
     * @param $item
     * @param $value
     * @return array
     */
    protected function narrative($item, $value)
    {
        if (is_array($item)) {
            foreach ($item as $index => $itemKey) {
                return [$index => [['narrative' => $value, 'language' => '']]];
            }
        }
    }

    /**
     * Filter the value of the field.
     *
     * @param      $value
     * @param      $field
     * @return int
     */
    protected function filterValue($value, $field)
    {
        if (in_array($field, $this->allowedDoubleValue)) {
            return $value;
        }

        if (in_array($field, $this->dateField)) {
            return dateFormat('Y-m-d', $value);
        }

        if (gettype($value) == 'double') {
            return (int) $value;
        }

        if (in_array($field, $this->allowedBothCasesField)) {
            return strtoupper($value);
        }

        return $value;
    }

    /**
     * Filter the data mapping of sector.
     *
     * @return $this
     */
    protected function filterSector()
    {
        $sectorVocabulary = (string) getVal((array) $this->transactionRow, ['transaction', 'sector', 0, 'sector_vocabulary']);
        $sectorCode       = (string) getVal((array) $this->transactionRow, ['transaction', 'sector', 0, 'sector_code']);

        if ($sectorVocabulary == '2' && $sectorCode != "") {
            $this->transactionRow['transaction']['sector'][0]['sector_category_code'] = $sectorCode;
            $this->transactionRow['transaction']['sector'][0]['sector_code']          = '';
        } elseif ($sectorVocabulary != '1' && $sectorVocabulary != '2' && $sectorCode != "") {
            $this->transactionRow['transaction']['sector'][0]['sector_text'] = $sectorCode;
            $this->transactionRow['transaction']['sector'][0]['sector_code'] = '';
        }

        return $this;
    }

    /**
     * Filter the data mapping of humanitarian.
     *
     * @return $this
     */
    protected function filterHumanitarian()
    {
        $humanitarian = getVal((array) $this->transactionRow, ['transaction', 'humanitarian']);

        if ($humanitarian != "" && $humanitarian != 1 && $humanitarian !== 0) {
            if (array_key_exists(strtolower($humanitarian), $this->allowedHumanitarianValues)) {
                $this->transactionRow['transaction']['humanitarian'] = $this->allowedHumanitarianValues[strtolower($humanitarian)];
            }
        }

        return $this;
    }

    /**
     * Filter the data mapping of transaction type
     *
     * @return $this
     */
    protected function filterTransactionType()
    {
        $transactionType = getVal((array) $this->transactionRow, ['transaction', 'transaction_type', 0, 'transaction_type_code']);

        $validTransactionType = $this->loadCodeList('TransactionType', 'V201');

        foreach ($validTransactionType['TransactionType'] as $type) {
            if (ucwords($transactionType) == $type['name']) {
                $this->transactionRow['transaction']['transaction_type'][0]['transaction_type_code'] = $type['code'];
            }
        }

        return $this;
    }

    /**
     * Process the Row.
     *
     * @return $this
     */
    public function process()
    {
        return $this->init();
    }

    /**
     * Returns the errors after validation.
     *
     * @return mixed
     */
    public function errors()
    {
        return $this->validator->errors()->getMessages();
    }

    /**
     * Returns the mapped data.
     *
     * @return mixed
     */
    public function data()
    {
        return $this->transactionRow;
    }

    /**
     * Validate the Row.
     *
     * @return $this
     */
    public function validate()
    {
        $activity = Activity::where('id', $this->activityId)->first()->toArray();

        $activitySector                                                     = getVal($activity, ['sector'], []);
        $this->transactionRow['transaction']['sector'][0]['activitySector'] = (empty($activitySector) ? '' : $activitySector);

        $recipientRegion                                                = getVal($activity, ['recipient_region'], []);
        $this->transactionRow['transaction']['activityRecipientRegion'] = (empty($recipientRegion) ? '' : $recipientRegion);

        $recipientCountry                                                = getVal($activity, ['recipient_country'], []);
        $this->transactionRow['transaction']['activityRecipientCountry'] = (empty($recipientCountry) ? '' : $recipientCountry);

        $this->validator = $this->factory->sign($this->transactionRow)
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();

        $this->setValidity();

        unset($this->transactionRow['transaction']['sector'][0]['activitySector']);
        unset($this->transactionRow['transaction']['activityRecipientRegion']);
        unset($this->transactionRow['transaction']['activityRecipientCountry']);

        return $this;
    }


    /**
     * Returns rules for the transaction data.
     *
     * @return array
     */
    protected function rules()
    {
        $sectorVocabulary   = $this->validCodeList('SectorVocabulary', 'V201');
        $sectorCode         = $this->validCodeList('Sector', 'V201');
        $sectorCategoryCode = $this->validCodeList('SectorCategory', 'V201');
        $regionCode         = $this->validCodeList('Region', 'V201');
        $countryCode        = $this->validCodeList('Country', 'V201', 'Organization');

        $rules = [
            'transaction'                                                  => 'check_recipient_region_country',
            'transaction.humanitarian'                                     => 'in:1,0',
            'transaction.transaction_type.*.transaction_type_code'         => sprintf('required|in:%s', $this->validCodeList('TransactionType', 'V201')),
            'transaction.transaction_date.*.date'                          => 'required|date_format:Y-m-d',
            'transaction.value.*.amount'                                   => 'required|numeric|min:0',
            'transaction.value.*.date'                                     => 'required|date_format:Y-m-d',
            'transaction.value.*.currency'                                 => sprintf('in:%s', $this->validCodeList('Currency', 'V201')),
            'transaction.provider_organization.*.type'                     => sprintf('in:%s', $this->validCodeList('OrganisationType', 'V201')),
            'transaction.provider_organization'                            => 'only_one_among',
            'transaction.receiver_organization.*.type'                     => sprintf('in:%s', $this->validCodeList('OrganisationType', 'V201')),
            'transaction.receiver_organization'                            => 'only_one_among',
            'transaction.sector'                                           => 'check_sector',
            'transaction.sector.0.sector_vocabulary'                       => sprintf('required_if:%s,%s|in:%s', 'transaction.sector.0.activitySector', '', $sectorVocabulary),
            'transaction.sector.0.sector_code'                             => sprintf('required_if:%s,%s|in:%s', 'transaction.sector.0.sector_vocabulary', '1', $sectorCode),
            'transaction.sector.0.sector_category_code'                    => sprintf('required_if:%s,%s|in:%s', 'transaction.sector.0.sector_vocabulary', '2', $sectorCategoryCode),
            'transaction.sector.0.sector_text'                             => sprintf(
                'required_unless:%s,%s,%s,%s,%s,%s',
                'transaction.sector.0.sector_vocabulary',
                '1',
                'transaction.sector.0.sector_vocabulary',
                '2',
                'activitySector',
                ''
            ),
            'transaction.recipient_country.0.country_code'                 => sprintf('in:%s', $countryCode),
            'transaction.recipient_region.0.region_code'                   => sprintf('in:%s', $regionCode),
            'transaction.aid_type.*.aid_type'                              => sprintf('in:%s', $this->validCodeList('AidType', 'V201')),
            'transaction.finance_type.*.finance_type'                      => sprintf('in:%s', $this->validCodeList('FinanceType', 'V201')),
            'transaction.flow_type.*.flow_type'                            => sprintf('in:%s', $this->validCodeList('FlowType', 'V201')),
            'transaction.tied_status.*.tied_status_code'                   => sprintf('in:%s', $this->validCodeList('TiedStatus', 'V201')),
            'transaction.disbursement_channel.*.disbursement_channel_code' => sprintf('in:%s', $this->validCodeList('DisbursementChannel', 'V201'))
        ];

        return $rules;
    }

    /**
     * Returns messages for the provided validations.
     *
     * @return array
     */
    protected function messages()
    {
        $message = [
            'transaction.check_recipient_region_country'                      => trans('validation.sector_in_activity_and_transaction'),
            'transaction.humanitarian.in'                                     => trans('validation.code_list', ['attribute' => trans('elementForm.humanitarian')]),
            'transaction.transaction_type.*.transaction_type_code.required'   => trans('validation.required', ['attribute' => trans('elementForm.transaction_type')]),
            'transaction.transaction_type.*.transaction_type_code.in'         => trans('validation.code_list', ['attribute' => trans('elementForm.transaction_type')]),
            'transaction.transaction_date.*.date.required'                    => trans('validation.required', ['attribute' => trans('elementForm.transaction_date')]),
            'transaction.transaction_date.*.date.date_format'                 => trans('validation.csv_date', ['attribute' => trans('elementForm.transaction_date')]),
            'transaction.value.*.amount.required'                             => trans('validation.required', ['attribute' => trans('elementForm.transaction_value')]),
            'transaction.value.*.amount.numeric'                              => trans('validation.numeric', ['attribute' => trans('elementForm.transaction_value')]),
            'transaction.value.*.amount.min'                                  => trans('validation.negative', ['attribute' => trans('elementForm.transaction_value')]),
            'transaction.value.*.date.required'                               => trans('validation.required', ['attribute' => trans('elementForm.transaction_value_date')]),
            'transaction.value.*.date.date_format'                            => trans('validation.csv_date', ['attribute' => trans('elementForm.transaction_value_date')]),
            'transaction.value.*.currency.in'                                 => trans('validation.code_list', ['attribute' => trans('elementForm.transaction_currency')]),
            'transaction.provider_organization.*.type.in'                     => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.provider_organisation_type')]),
            'transaction.provider_organization.only_one_among'                => trans(
                'validation.required_if',
                [
                    'attribute' => trans('elementForm.provider_organisation_identifier'),
                    'values'    => trans('elementForm.organisation_name')
                ]
            ),
            'transaction.receiver_organization.*.type.in'                     => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.receiver_organisation_type')]),
            'transaction.receiver_organization.only_one_among'                => trans(
                'validation.required_if_in_transaction',
                [
                    'attribute' => trans('elementForm.receiver_organisation_identifier'),
                    'values'    => trans('elementForm.organisation_name')
                ]
            ),
            'transaction.sector.check_sector'                                 => trans('validation.sector_validation'),
            'transaction.sector.*.sector_vocabulary.in'                       => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.sector_vocabulary')]),
            'transaction.sector.*.sector_vocabulary.required_if'              => trans('validation.sector_vocabulary_required'),
            'transaction.sector.*.sector_code.in'                             => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.sector_code')]),
            'transaction.sector.*.sector_category_code.in'                    => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.sector_code')]),
            'transaction.sector.*.sector_text.required_unless'                => trans('validation.required_in_transaction', ['attribute' => trans('elementForm.sector_code')]),
            'transaction.recipient_country.*.country_code.in'                 => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.recipient_country_code')]),
            'transaction.recipient_region.*.region_code.in'                   => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.recipient_region_code')]),
            'transaction.aid_type.*.aid_type.in'                              => trans('validation.code_list', ['attribute' => trans('elementForm.aid_type')]),
            'transaction.finance_type.*.finance_type.in'                      => trans('validation.code_list', ['attribute' => trans('elementForm.finance_type')]),
            'transaction.flow_type.*.flow_type.in'                            => trans('validation.code_list', ['attribute' => trans('elementForm.flow_type')]),
            'transaction.tied_status.*.tied_status_code.in'                   => trans('validation.code_list', ['attribute' => trans('elementForm.tied_status')]),
            'transaction.disbursement_channel.*.disbursement_channel_code.in' => trans('validation.code_list', ['attribute' => trans('elementForm.disbursement_channel_code')])
        ];

        return $message;
    }

    /**
     * Set the validity for the IATI Element data.
     */
    protected function setValidity()
    {
        $this->isValid = $this->validator->passes();
    }

    /**
     * Load the provided Activity CodeList.
     *
     * @param        $codeList
     * @param        $version
     * @param string $directory
     * @return array
     */
    protected function loadCodeList($codeList, $version, $directory = "Activity")
    {
        return json_decode(file_get_contents(app_path(sprintf('Core/%s/Codelist/en/%s/%s.json', $version, $directory, $codeList))), true);
    }

    /**
     * Get the valid codes from the respective code list.
     *
     * @param        $name
     * @param        $version
     * @param string $directory
     * @return string
     */
    protected function validCodeList($name, $version, $directory = "Activity")
    {
        $codeList = $this->loadCodeList($name, $version, $directory);
        $codes    = [];

        array_walk(
            $codeList[$name],
            function ($vocabulary) use (&$codes) {
                $codes[] = $vocabulary['code'];
            }
        );

        return implode(",", $codes);
    }

    /**
     * Load the empty template of the transaction version-wise.
     *
     * @param $version
     * @param $filename
     * @return mixed
     */
    protected function loadTemplate($version, $filename)
    {
        $path = sprintf('%s/%s/%s.json', app_path(self::TRANSACTION_TEMPLATE_PATH), $version, $filename);

        return json_decode(file_get_contents($path), true);
    }
}

