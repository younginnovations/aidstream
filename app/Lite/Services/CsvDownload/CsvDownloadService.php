<?php namespace App\Lite\Services\CsvDownload;


use App\Core\V201\Traits\GetCodes;
use App\Lite\Contracts\ActivityRepositoryInterface;
use App\Lite\Services\Data\Traits\TransformsData;

/**
 * Class CsvDownloadService
 * @package App\Lite\Services\CsvDownload
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
     * CsvDownloadService constructor.
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
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
        $processedActivity = $this->prepareSimpleData($orgId, $version);
        $template          = $this->getTemplate();

        foreach ($processedActivity as $index => $activity) {
            $this->csvData[$this->index] = $template;

            foreach ($activity as $field => $value) {
                if (is_array($value)) {
                    $methodName = getVal($this->fieldToMethodMapper, [$field]);

                    if (method_exists($this, $methodName)) {
                        $this->{$this->fieldToMethodMapper[$field]}($value);
                    } elseif (!in_array($field, $this->ignoredElement)) {
                        $this->{$field}($value);
                    }
                } else {
                    $this->csvData[$this->index][$field] = $value;
                }
            }
            $this->fillEmptyStringInEmptyColumns();

            $this->maxRowIndex   = 0;
            $this->rowIndexCount = 0;
            $this->index ++;
        }

        $this->csvData['headers'] = $this->simpleHeaders();

        return $this->csvData;
    }

    /**
     * Sets empty value to field that doesn't have multiple values.
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
                $this->csvData[$this->index]['participating_organisation_role'] = sprintf('%s (%s)', ucwords(str_replace('_', ' ', $orgRole)), $this->orgRoles[$orgRole]);
                $participatingOrgTypeCode                                       = getVal($specificOrg, ['organisation_type']);
                $this->csvData[$this->index]['participating_organisation_type'] = sprintf('%s (%s)', getVal($organisationTypes, [$participatingOrgTypeCode]), $participatingOrgTypeCode);
                $this->csvData[$this->index]['participating_organisation_name'] = getVal($specificOrg, ['organisation_name']);
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
            $this->csvData[$this->index]['budget_period_start'] = getVal($budget, ['startDate']);
            $this->csvData[$this->index]['budget_period_end']   = getVal($budget, ['endDate']);
            $this->csvData[$this->index]['budget_amount']       = getVal($budget, ['amount']);
            $this->csvData[$this->index]['budget_currency']     = getVal($budget, ['currency']);
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
            $this->csvData[$this->index]['document_link_category'] = ($documentCategory) ? sprintf('%s (%s)', $documentCategories[$documentCategory], $documentCategory) : '';
            $this->csvData[$this->index]['document_link_title']    = getVal($documentLink, [0, 'document_title']);
            $this->csvData[$this->index]['document_link_url']      = getVal($documentLink, [0, 'document_url']);
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
            $this->csvData[$this->index]['transaction_type']                       = ($transactionTypeCode) ? sprintf(
                '%s (%s)',
                $transactionTypes[$transactionTypeCode],
                $transactionTypeCode
            ) : '';
            $this->csvData[$this->index]['transaction_reference']                  = getVal($transaction, ['reference']);
            $this->csvData[$this->index]['transaction_date']                       = getVal($transaction, ['date']);
            $this->csvData[$this->index]['transaction_amount']                     = getVal($transaction, ['amount']);
            $this->csvData[$this->index]['transaction_currency']                   = getVal($transaction, ['currency']);
            $this->csvData[$this->index]['transaction_description']                = getVal($transaction, ['description']);
            $this->csvData[$this->index]['transaction_receiver_organisation_name'] = getVal($transaction, ['organisation']);
            $this->index ++;
            $this->rowIndexCount ++;
        }

        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = ($this->index > $this->maxRowIndex) ? $this->index : $this->maxRowIndex - 1;
    }

    /**
     * Prepare simple data of the organisation.
     *
     * @param $orgId
     * @param $version
     * @return array
     */
    protected function prepareSimpleData($orgId, $version)
    {
        $activities   = $this->activityRepository->all($orgId);
        $reversedData = [];

        foreach ($activities as $index => $activity) {
            $documentLinks        = $activity->documentLinks;
            $transactions         = $activity->transactions;
            $budget               = $activity->budget;
            $reversedData[$index] = $this->transformReverse($this->getMapping($activity->toArray(), 'Activity', $version));
            $reversedData[$index] = array_merge($reversedData[$index], $this->transformReverse($this->getMapping(['budget' => $budget], 'Budget', $version)));

            $fundingOrganisation      = array_pluck($reversedData, 'funding_organisations');
            $implementingOrganisation = array_pluck($reversedData, 'implementing_organisations');

            if (array_key_exists('funding_organisations', $reversedData[$index])) {
                unset($reversedData[$index]['funding_organisations']);
                $reversedData[$index]['participating_organisation']['funding_organisations'] = getVal($fundingOrganisation, [0], []);
            }

            if (array_key_exists('implementing_organisations', $reversedData[$index])) {
                unset($reversedData[$index]['implementing_organisations']);
                $reversedData[$index]['participating_organisation']['implementing_organisations'] = getVal($implementingOrganisation, [0], []);
            }

            if ($documentLinks) {
                $reversedData[$index] = array_merge($reversedData[$index], ['document_link' => $this->transformReverse($this->getMapping($documentLinks->toArray(), 'DocumentLink', $version))]);
            }

            if ($transactions) {
                $mappedTransactions   = ['transaction' => $this->transformReverse($this->getMapping($transactions->toArray(), 'Transaction', $version))];
                $reversedData[$index] = array_merge($reversedData[$index], $this->prepareTransactionData($mappedTransactions, $transactions));
            }
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
            'activity_identifier',
            'activity_title',
            'activity_status',
            'sector',
            'general_description',
            'objectives',
            'target_groups',
            'start_date',
            'end_date',
            'country',
            'participating_organisation_role',
            'participating_organisation_type',
            'participating_organisation_name',
            'document_link_category',
            'document_link_title',
            'document_link_url',
            'budget_period_start',
            'budget_period_end',
            'budget_amount',
            'budget_currency',
            'transaction_type',
            'transaction_reference',
            'transaction_date',
            'transaction_amount',
            'transaction_currency',
            'transaction_description',
            'transaction_receiver_organisation_name'
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
}

