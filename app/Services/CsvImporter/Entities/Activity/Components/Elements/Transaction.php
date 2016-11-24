<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\ActivityRow;
use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Elements\Transaction\PreparesTransactionData;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

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
        $sectorVocabulary   = $this->validCodeList('SectorVocabulary', 'V201');
        $sectorCode         = $this->validCodeList('Sector', 'V201');
        $sectorCategoryCode = $this->validCodeList('SectorCategory', 'V201');
        $regionCode         = $this->validCodeList('Region', 'V201');
        $countryCode        = $this->validCodeList('Country', 'V201', 'Organization');

        $rules = [
            'transaction'                                          => 'check_recipient_region_country',
            'transaction.transaction_type.*.transaction_type_code' => sprintf('required|in:%s', $this->validCodeOrName('TransactionType', 'V201')),
            'transaction.transaction_date.*.date'                  => 'required|date_format:Y-m-d',
            'transaction.value.*.amount'                           => 'required|numeric|min:0',
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
            'transaction.check_recipient_region_country'                    => 'Recipient Region or Recipient Country must be present either in Activity level or Transaction level but not in both.',
            'transaction.transaction_type.*.transaction_type_code.required' => 'Transaction type is required.',
            'transaction.transaction_type.*.transaction_type_code.in'       => 'Entered transaction type is incorrect.',
            'transaction.transaction_date.*.date.required'                  => 'Transaction date is required.',
            'transaction.transaction_date.*.date.date_format'               => 'Please enter transaction date in Y-m-d format.',
            'transaction.value.*.amount.required'                           => 'Transaction Value is required.',
            'transaction.value.*.amount.numeric'                            => 'Transaction Value should be numeric.',
            'transaction.value.*.amount.min'                                => 'Transaction Value cannot be negative.',
            'transaction.value.*.date.required'                             => 'Transaction Value Date is required.',
            'transaction.value.*.date.date_format'                          => 'Please enter transaction value date in Y-m-d format.',
            'transaction.provider_organization.*.type.in'                   => 'Entered provider organisation type is incorrect in Transaction.',
            'transaction.provider_organization.only_one_among'              => 'Provider Organisation identifier is required if organisation name is not present in Transaction.',
            'transaction.receiver_organization.*.type.in'                   => 'Entered receiver organisation type is incorrect in Transaction.',
            'transaction.receiver_organization.only_one_among'              => 'Receiver Organisation identifier is required if organisation name is not present in Transaction.',
            'transaction.sector.check_sector'                               => 'Sector information must be present either in Transaction level or Activity level but not in both.',
            'transaction.sector.*.sector_vocabulary.in'                     => 'Entered sector vocabulary is incorrect in Transaction.',
            'transaction.sector.*.sector_vocabulary.required_if'            => 'Sector Vocabulary is required in Transaction if not present in Activity Level.',
            'transaction.sector.*.sector_code.in'                           => 'Entered sector code for vocabulary (1) is incorrect in Transaction.',
            'transaction.sector.*.sector_category_code.in'                  => 'Entered sector code for vocabulary (2) is incorrect in Transaction.',
            'transaction.sector.*.sector_text.required_unless'              => 'Sector code is required in Transaction.',
            'transaction.recipient_country.*.country_code.in'               => 'Entered Recipient country code is incorrect in Transaction.',
            'transaction.recipient_region.*.region_code.in'                 => 'Entered recipient region code is incorrect in Transaction.'
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
}
