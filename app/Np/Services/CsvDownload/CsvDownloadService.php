<?php namespace App\Np\Services\CsvDownload;


use App\Core\V201\Traits\GetCodes;
use App\Helpers\GetCodeName;
use App\Np\Contracts\NpActivityRepositoryInterface;
use App\Np\Services\Data\Traits\TransformsData;

/**
 * Class CsvDownloadService
 * @package App\Np\Services\CsvDownload
 */
class CsvDownloadService
{
    use TransformsData, GetCodes;

    /**
     * @var ActivityRepositoryInterface
     */
    protected $activityRepository;

    /**
     * To count the row index.
     *
     * @var int
     */

    protected $rowIndexCount = 0;

    /**
     * To count the maximum row taken by a field.
     * @var int
     */
    protected $maxRowIndex = 0;

    /**
     * To track index of the row.
     *
     * @var int
     */
    protected $index = 0;

    /**
     * Holds data to be exported
     *
     * @var array
     */
    protected $csvData = [];

    /**
     * Document Type and its code
     *
     * @var array
     */
    protected $documentType = ['outcomes_document' => 'A08', 'annual_report' => 'B01'];

    /**
     * Organisation Roles and its code.
     *
     * @var array
     */
    protected $orgRoles = ['funding_organisations' => 4, 'implementing_organisations' => 1];

    /**
     * @var array
     */
    protected $ignoredElement = ['location'];

    /**
     * Method to redirect to respective field.
     *
     * @var array
     */
    protected $fieldToMethodMapper = [
        'participating_organisation' => 'participatingOrganisation',
        'document_link'              => 'documentLink'
    ];

    /**
     * @var GetCodeName
     */
    protected $codeName;

    /**
     * CsvDownloadService constructor.
     * @param ActivityRepositoryInterface $activityRepository
     * @param GetCodeName                 $codeName
     */
    public function __construct(NpActivityRepositoryInterface $activityRepository, GetCodeName $codeName)
    {
        $this->activityRepository = $activityRepository;
        $this->codeName           = $codeName;
    }

    /**
     * Returns csv formatted simpled data
     *
     * @param $orgId
     * @param $version
     * @return array
     */
    public function simpleData($orgId, $version)
    {
        $activities = $this->activityRepository->all($orgId);
        $template   = $this->getTemplate();
        $header     = $this->simpleHeaders();

        foreach ($activities as $index => $activity) {
            $reversedData                = $this->prepareSimpleData($activity, $version);
            $this->csvData[$this->index] = $template;
            foreach ($reversedData as $field => $value) {
                if (is_array($value) && !empty($value)) {
                    if (!in_array($field, $this->ignoredElement)) {
                        if (method_exists($this, $methodName = camel_case($field))) {
                            $this->{$methodName}($value);
                        }
                    }
                } else {
                    $this->csvData[$this->index][getVal($header, [$field])] = $value;
                }
            }

            $this->fillEmptyStringInEmptyColumns();
            $this->index         += $this->maxRowIndex;
            $this->maxRowIndex   = 0;
            $this->rowIndexCount = 0;
        }

        return $this->csvData;
    }

    /**
     * Sets empty value to field that does not have multiple values.
     */
    protected function fillEmptyStringInEmptyColumns()
    {
        foreach ($this->csvData as $rowIndex => $activityRow) {
            foreach ($this->simpleHeaders() as $key => $value) {
                $tempContainer[$rowIndex][$value] = (array_key_exists($value, $activityRow)) ? $activityRow[$value] : '';
                $this->csvData                    = $tempContainer;
            }
        }
    }

    /**
     * Returns csv formatted country data.
     * @param $value
     */
    protected function country($value)
    {
        $this->rowIndexCount = 0;
        foreach ($value as $index => $country) {
            $this->csvData[$this->index]['Country'] = $country;
            $this->index ++;
            $this->rowIndexCount ++;
        }
        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    /**
     * Returns csv formatted participating organisation data.
     *
     * @param $value
     */
    protected function participatingOrganisation($value)
    {
        $this->rowIndexCount = 0;
        $organisationTypes   = $this->getNameWithCode('Activity', 'OrganisationType');

        foreach ($value as $orgRole => $participatingOrg) {
            foreach ($participatingOrg as $specificOrg) {
                $this->csvData[$this->index]['Participating Organisation Role'] = ($orgRole != "") ? sprintf('%s (%s)', ucwords(str_replace('_', ' ', $orgRole)), $this->orgRoles[$orgRole]) : $orgRole;
                $participatingOrgTypeCode                                       = getVal($specificOrg, ['organisation_type']);
                $this->csvData[$this->index]['Participating Organisation Type'] =
                    (($typeCode = getVal($organisationTypes, [$participatingOrgTypeCode])) != "") ? sprintf(
                        '%s (%s)',
                        $typeCode,
                        $participatingOrgTypeCode
                    ) : '';
                $this->csvData[$this->index]['Participating Organisation Name'] = getVal($specificOrg, ['organisation_name']);
                $this->index ++;
                $this->rowIndexCount ++;
            }
        }

        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    /**
     * Returns csv formatted budget data.
     *
     * @param $value
     */
    protected function budget($value)
    {
        $this->rowIndexCount = 0;

        foreach ($value as $budget) {
            $this->csvData[$this->index]['Budget Period Start'] = getVal($budget, ['startDate']);
            $this->csvData[$this->index]['Budget Period End']   = getVal($budget, ['endDate']);
            $this->csvData[$this->index]['Budget Amount']       = getVal($budget, ['amount']);
            $this->csvData[$this->index]['Budget Currency']     = getVal($budget, ['currency']);
            $this->index ++;
            $this->rowIndexCount ++;
        }

        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    /**
     * Returns csv formatted document link data
     *
     * @param $value
     */
    protected function documentLink($value)
    {
        $this->rowIndexCount = 0;
        $documentCategories  = $this->getNameWithCode('Activity', 'DocumentCategory');

        foreach ($value as $documentType => $documentLink) {
            $documentCategory                                      = $this->documentType[$documentType];
            $this->csvData[$this->index]['Document Link Category'] = ($documentCategory) ? sprintf('%s (%s)', $documentCategories[$documentCategory], $documentCategory) : '';
            $this->csvData[$this->index]['Document Link Title']    = getVal($documentLink, [0, 'document_title']);
            $this->csvData[$this->index]['Document Link Url']      = getVal($documentLink, [0, 'document_url']);
            $this->index ++;
            $this->rowIndexCount ++;
        }

        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    /**
     * Returns csv formatted transaction data.
     *
     * @param $value
     */
    protected function transaction($value)
    {
        $this->rowIndexCount = 0;
        $transactionTypes    = $this->getNameWithCode('Activity', 'TransactionType');
        foreach ($value as $transaction) {
            $transactionTypeCode                                                   = getVal($transaction, ['type']);
            $this->csvData[$this->index]['Transaction Type']                       = ($transactionTypeCode) ? sprintf(
                '%s (%s)',
                $transactionTypes[$transactionTypeCode],
                $transactionTypeCode
            ) : '';
            $this->csvData[$this->index]['Transaction Reference']                  = getVal($transaction, ['reference']);
            $this->csvData[$this->index]['Transaction Date']                       = getVal($transaction, ['date']);
            $this->csvData[$this->index]['Transaction Amount']                     = getVal($transaction, ['amount']);
            $this->csvData[$this->index]['Transaction Currency']                   = getVal($transaction, ['currency']);
            $this->csvData[$this->index]['Transaction Description']                = getVal($transaction, ['description']);
            $this->csvData[$this->index]['Transaction Receiver Organisation Name'] = getVal($transaction, ['organisation']);
            $this->index ++;
            $this->rowIndexCount ++;
        }

        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    /**
     * Prepare simple data of the organisation.
     *
     * @param $activity
     * @param $version
     * @return array
     */
    protected function prepareSimpleData($activity, $version)
    {
        $documentLinks = $activity->documentLinks;
        $transactions  = $activity->transactions;
        $budget        = $activity->budget;
        $reversedData  = $this->transformReverse($this->getMapping($activity->toArray(), 'Activity', $version));
        $reversedData  = array_merge($reversedData, $this->transformReverse($this->getMapping(['budget' => $budget], 'Budget', $version)));

        if (array_key_exists('funding_organisations', $reversedData)) {
            $reversedData['participating_organisation']['funding_organisations'] = getVal($reversedData, ['funding_organisations'], []);
            unset($reversedData['funding_organisations']);
        }

        if (array_key_exists('implementing_organisations', $reversedData)) {
            $reversedData['participating_organisation']['implementing_organisations'] = getVal($reversedData, ['implementing_organisations'], []);
            unset($reversedData['implementing_organisations']);
        }

        if ($documentLinks) {
            $reversedData = array_merge($reversedData, ['document_link' => $this->transformReverse($this->getMapping($documentLinks->toArray(), 'DocumentLink', $version))]);
        }

        if ($transactions) {
            $mappedTransactions = ['transaction' => $this->transformReverse($this->getMapping($transactions->toArray(), 'Transaction', $version))];
            $reversedData       = array_merge($reversedData, $this->prepareTransactionData($mappedTransactions, $transactions));
        }

        return $reversedData;
    }

    /**
     * Headers for the simple csv.
     *
     * @return array
     */
    protected function simpleHeaders()
    {
        return [
            'activity_identifier'                    => 'Activity Identifier',
            'activity_title'                         => 'Activity Title',
            'activity_status'                        => 'Activity Status',
            'sector'                                 => 'Activity Sector',
            'general_description'                    => 'General Description',
            'objectives'                             => 'Objectives',
            'target_groups'                          => 'Target Groups',
            'start_date'                             => 'Start Date',
            'end_date'                               => 'End Date',
            'country'                                => 'Country',
            'participating_organisation_role'        => 'Participating Organisation Role',
            'participating_organisation_type'        => 'Participating Organisation Type',
            'participating_organisation_name'        => 'Participating Organisation Name',
            'document_link_category'                 => 'Document Link Category',
            'document_link_title'                    => 'Document Link Title',
            'document_link_url'                      => 'Document Link Url',
            'budget_period_start'                    => 'Budget Period Start',
            'budget_period_end'                      => 'Budget Period End',
            'budget_amount'                          => 'Budget Amount',
            'budget_currency'                        => 'Budget Currency',
            'transaction_type'                       => 'Transaction Type',
            'transaction_reference'                  => 'Transaction Reference',
            'transaction_date'                       => 'Transaction Date',
            'transaction_amount'                     => 'Transaction Amount',
            'transaction_currency'                   => 'Transaction Currency',
            'transaction_description'                => 'Transaction Description',
            'transaction_receiver_organisation_name' => 'Transaction Receiver Organisation Name'
        ];
    }

    /**
     * Prepare empty value for the given headers.
     *
     * @return array
     */
    protected function getTemplate()
    {
        $template = [];
        foreach ($this->simpleHeaders() as $key => $value) {
            $template[$value] = '';
        }

        return $template;
    }


    /**
     * Prepare transactions of the activity.
     *
     * @param $mappedTransactions
     * @param $rawTransactions
     * @return mixed
     */
    protected function prepareTransactionData($mappedTransactions, $rawTransactions)
    {
        foreach ($mappedTransactions as $transactionIndex => $transaction) {
            foreach ($transaction as $index => $field) {
                $selectedTransaction                                   = $rawTransactions->where('id', $field['id'])->toArray();
                $transactionType                                       = getVal($selectedTransaction, [$index, 'transaction', 'transaction_type', 0, 'transaction_type_code']);
                $mappedTransactions[$transactionIndex][$index]['type'] = $transactionType;
                unset($mappedTransactions[$transactionIndex][$index]['id']);
            }
        }

        return $mappedTransactions;
    }

    /**
     * Map Sector data into Csv format.
     *
     * @param array $values
     */
    protected function sector(array $values)
    {
        foreach ($values as $value) {
            $this->csvData[$this->index]['Activity Sector'] = $this->codeName->getCodeName('Activity', 'Sector', $value);
            $this->index ++;
            $this->rowIndexCount ++;
        }

        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }
}

