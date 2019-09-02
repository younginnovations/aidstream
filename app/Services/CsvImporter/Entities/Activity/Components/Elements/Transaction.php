<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;
use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Elements\Transaction\PreparesTransactionData;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;
use Illuminate\Support\Facades\Log;

/**
 * Class Transaction
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Transaction extends Element
{
    use PreparesTransactionData;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Index under which the data is stored within the object.
     * @var string
     */
    protected $index = 'transaction';

    /**
     * @var array
     */
    protected $_csvHeaders = [
        'transaction_internal_reference',
        'transaction_type',
        'transaction_date',
        'transaction_value',
        'transaction_value_date',
        'transaction_description',
        'transaction_provider_organisation_identifier',
        'transaction_provider_organisation_activity_identifier',
        'transaction_provider_organisation_type',
        'transaction_provider_organisation_description',
        'transaction_receiver_organisation_identifier',
        'transaction_receiver_organisation_activity_identifier',
        'transaction_receiver_organisation_type',
        'transaction_receiver_organisation_description',
        'transaction_sector_vocabulary',
        'transaction_sector_code',
        'transaction_recipient_country_code',
        'transaction_recipient_region_code'
    ];

    /**
     * @var array
     */
    protected $template = [
        'reference'             => '',
        'humanitarian'          => '',
        'transaction_type'      => ['transaction_type_code' => ''],
        'transaction_date'      => ['date' => ''],
        'value'                 => ['amount' => '', 'date' => '', 'currency' => ''],
        'description'           => ['narrative' => ['narrative' => '', 'language' => '']],
        'provider_organization' => ['organization_identifier_code' => '', 'provider_activity_id' => '', 'type' => '', 'narrative' => ['narrative' => '', 'language' => '']],
        'receiver_organization' => ['organization_identifier_code' => '', 'receiver_activity_id' => '', 'type' => '', 'narrative' => ['narrative' => '', 'language' => '']],
        'disbursement_channel'  => ['disbursement_channel_code' => ''],
        'sector'                => [
            'sector_vocabulary'    => '',
            'vocabulary_uri'       => '',
            'sector_code'          => '',
            'sector_category_code' => '',
            'sector_text'          => '',
            'narrative'            => ['narrative' => '', 'language' => '']
        ],
        'recipient_country'     => ['country_code' => '', 'narrative' => ['narrative' => '', 'language' => '']],
        'recipient_region'      => ['region_code' => '', 'vocabulary' => '', 'vocabulary_uri' => '', 'narrative' => ['narrative' => '', 'language' => '']],
        'flow_type'             => ['flow_type' => ''],
        'finance_type'          => ['finance_type' => ''],
        'aid_type'              => ['aid_type' => ''],
        'tied_status'           => ['tied_status_code' => '']
    ];

    /**
     * @var ActivityRow
     */
    protected $activityRow;

    private $version;

    /**
     * Transaction constructor.
     * @param            $transactionRow
     * @param            $activityRow
     * @param Validation $factory
     */
    public function __construct($transactionRow, $activityRow, Validation $factory)
    {
        $this->prepare($transactionRow);
        $this->factory     = $factory;
        $this->activityRow = $activityRow;
    }

    /**
     * Prepare the IATI Element.
     * @param $fields
     */
    protected function prepare($fields)
    {
        foreach ($fields as $key => $value) {
            $value = (!is_null($value)) ? $value : '';
            $this->setInternalReference($key, $value);
            $this->setHumanitarian();
            $this->setTransactionType($key, $value);
            $this->setTransactionDate($key, $value);
            $this->setTransactionValue($key, $value);
            $this->setTransactionValueDate($key, $value);
            $this->setTransactionDescription($key, $value);
            $this->setProviderOrganization($key, $value);
            $this->setReceiverOrganization($key, $value);
            $this->setDisbursementChannel();
            $this->setSector($key, $value);
            $this->setRecipientCountry($key, $value);
            $this->setRecipientRegion($key, $value);
            $this->setFlowType();
            $this->setFinanceType();
            $this->setAidType();
            $this->setTiedStatus();
        }
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $activitySector                                           = getVal($this->activityLevelSector(), ['sector'], []);
        $this->data['transaction']['sector'][0]['activitySector'] = (empty($activitySector) ? '' : $activitySector);

        $recipientRegion                                      = getVal($this->activityLevelRecipientRegion(), ['recipient_region'], []);
        $this->data['transaction']['activityRecipientRegion'] = (empty($recipientRegion) ? '' : $recipientRegion);

        $recipientCountry                                      = getVal($this->activityLevelRecipientCountry(), ['recipient_country'], []);
        $this->data['transaction']['activityRecipientCountry'] = (empty($recipientCountry) ? '' : $recipientCountry);

        $this->validator = $this->factory->sign($this->data())
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();
        $this->setValidity();

        unset($this->data['transaction']['sector'][0]['activitySector']);
        unset($this->data['transaction']['activityRecipientRegion']);
        unset($this->data['transaction']['activityRecipientCountry']);

        return $this;
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        $sectorVocabulary   = $this->validCodeList('SectorVocabulary', $this->version);
        $sectorCode         = $this->validCodeList('Sector', 'V201');
        $sectorCategoryCode = $this->validCodeList('SectorCategory', 'V201');
        $regionCode         = $this->validCodeList('Region', 'V201');
        $countryCode        = $this->validCodeList('Country', 'V201', 'Organization');

        $rules = [
            'transaction'                                          => 'check_recipient_region_country',
            'transaction.transaction_type.*.transaction_type_code' => sprintf('required|in:%s', $this->validCodeOrName('TransactionType', $this->activityRow->version)),
            'transaction.transaction_date.*.date'                  => 'required|date_format:Y-m-d',
            'transaction.value.*.amount'                           => 'required|numeric',
            'transaction.value.*.date'                             => 'required|date_format:Y-m-d',
            'transaction.provider_organization.*.type'             => sprintf('in:%s', $this->validCodeOrName('OrganisationType', 'V201')),
            'transaction.provider_organization'                    => 'only_one_among',
            'transaction.receiver_organization.*.type'             => sprintf('in:%s', $this->validCodeOrName('OrganisationType', 'V201')),
            'transaction.receiver_organization'                    => 'only_one_among',
            'transaction.sector'                                   => 'check_sector',
            'transaction.sector.0.sector_vocabulary'               => sprintf('required_if:%s,%s|in:%s', 'transaction.sector.0.activitySector', '', $sectorVocabulary),
            'transaction.sector.0.sector_code'                     => sprintf('required_if:%s,%s|in:%s', 'transaction.sector.0.sector_vocabulary', '1', $sectorCode),
            'transaction.sector.0.sector_category_code'            => sprintf('required_if:%s,%s|in:%s', 'transaction.sector.0.sector_vocabulary', '2', $sectorCategoryCode),
            'transaction.sector.0.sector_text'                     => sprintf(
                'required_unless:%s,%s,%s,%s,%s,%s',
                'transaction.sector.0.sector_vocabulary',
                '1',
                'transaction.sector.0.sector_vocabulary',
                '2',
                'activitySector',
                ''
            ),
            'transaction.recipient_country.0.country_code'         => sprintf('in:%s', $countryCode),
            'transaction.recipient_region.0.region_code'           => sprintf('in:%s', $regionCode)
        ];


        return $rules;
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        $message = [
            'transaction.check_recipient_region_country'                    => trans('validation.sector_in_activity_and_transaction'),
            'transaction.transaction_type.*.transaction_type_code.required' => trans('validation.required', ['attribute' => trans('elementForm.transaction_type')]),
            'transaction.transaction_type.*.transaction_type_code.in'       => trans('validation.code_list', ['attribute' => trans('elementForm.transaction_type')]),
            'transaction.transaction_date.*.date.required'                  => trans('validation.required', ['attribute' => trans('elementForm.transaction_date')]),
            'transaction.transaction_date.*.date.date_format'               => trans('validation.csv_date', ['attribute' => trans('elementForm.transaction_date')]),
            'transaction.value.*.amount.required'                           => trans('validation.required', ['attribute' => trans('elementForm.transaction_value')]),
            'transaction.value.*.amount.numeric'                            => trans('validation.numeric', ['attribute' => trans('elementForm.transaction_value')]),
            'transaction.value.*.amount.min'                                => trans('validation.negative', ['attribute' => trans('elementForm.transaction_value')]),
            'transaction.value.*.date.required'                             => trans('validation.required', ['attribute' => trans('elementForm.transaction_value_date')]),
            'transaction.value.*.date.date_format'                          => trans('validation.csv_date', ['attribute' => trans('elementForm.transaction_value_date')]),
            'transaction.provider_organization.*.type.in'                   => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.provider_organisation_type')]),
            'transaction.provider_organization.only_one_among'              => trans(
                'validation.required_if',
                [
                    'attribute' => trans('elementForm.provider_organisation_identifier'),
                    'values'    => trans('elementForm.organisation_name'),
                    'value'     => 'absent'
                ]
            ),
            'transaction.receiver_organization.*.type.in'                   => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.receiver_organisation_type')]),
            'transaction.receiver_organization.only_one_among'              => trans(
                'validation.required_if_in_transaction',
                [
                    'attribute' => trans('elementForm.receiver_organisation_identifier'),
                    'values'    => trans('elementForm.organisation_name')
                ]
            ),
            'transaction.sector.check_sector'                               => trans('validation.sector_validation'),
            'transaction.sector.*.sector_vocabulary.in'                     => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.sector_vocabulary')]),
            'transaction.sector.*.sector_vocabulary.required_if'            => trans('validation.sector_vocabulary_required'),
            'transaction.sector.*.sector_code.in'                           => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.sector_code')]),
            'transaction.sector.*.sector_category_code.in'                  => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.sector_code')]),
            'transaction.sector.*.sector_text.required_unless'              => trans('validation.required_in_transaction', ['attribute' => trans('elementForm.sector_code')]),
            'transaction.recipient_country.*.country_code.in'               => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.recipient_country_code')]),
            'transaction.recipient_region.*.region_code.in'                 => trans('validation.invalid_in_transaction', ['attribute' => trans('elementForm.recipient_region_code')]),
        ];

        return $message;
    }

    /**
     * Get the valid codes/names from the respective code list.
     * @param $name
     * @param $version
     * @return string
     */
    protected function validCodeOrName($name, $version)
    {
        list($validCodes, $codes) = [$this->loadCodeList($name, $version), []];

        array_walk(
            $validCodes[$name],
            function ($type) use (&$codes) {
                $codes[] = $type['code'];
                $codes[] = $type['name'];
            }
        );

        return implode(',', array_keys(array_flip($codes)));
    }

    /**
     * Get the valid codes from the respective code list.
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
     * Get the Sector for the Activity Level.
     * @return mixed
     */
    protected function activityLevelSector()
    {
        return $this->activityRow->sector->data;
    }

    /**
     * Get the Recipient Country for the Activity Level.
     * @return mixed
     */
    protected function activityLevelRecipientCountry()
    {
        return $this->activityRow->recipientCountry->data;
    }

    /**
     * Get the Recipient Region for the Activity Level.
     * @return mixed
     */
    protected function activityLevelRecipientRegion()
    {
        return $this->activityRow->recipientRegion->data;
    }
    
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
