<?php namespace App\Core\V201\Formatter;

use App\Core\V201\Formatter\Factory\Traits\StringConcatenator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TransactionCsvDataFormatter
 * @package App\Core\V201\Formatter
 */
class TransactionCsvDataFormatter
{
    use StringConcatenator;
    protected $headers;
    /**
     * @var GetCodeName
     */
    protected $codeNameHelper;
    protected $csvData = [];
    /**
     * @var SimpleCsvDataFormatter
     */
    protected $csvDataFormatter;

    /**
     * TransactionCsvDataFormatter Constructor
     * @param SimpleCsvDataFormatter $csvDataFormatter
     */
    public function __construct(SimpleCsvDataFormatter $csvDataFormatter)
    {
        $this->headers          = [
            'Activity_Identifier',
            'Activity_Title',
            'Transaction-ref',
            'TransactionType-code',
            'TransactionDate-iso_date',
            'TransactionValue-currency',
            'TransactionValue-value_date',
            'TransactionValue-text',
            'Description_text',
            'ProviderOrg-ref',
            'ProviderOrg-provider_activity_id',
            'ProviderOrg-Narrative_text',
            'ReceiverOrg-ref',
            'ReceiverOrg-receiver_activity_id',
            'ReceiverOrg-Narrative_text',
            'DisbursementChannel-code',
            'Sector-vocabulary',
            'Sector-code',
            'RecipientCountry-code',
            'RecipientRegion-vocabulary',
            'RecipientRegion-code',
            'FlowType-code',
            'FinanceType-code',
            'AidType-code',
            'TiedStatus-code'
        ];
        $this->csvDataFormatter = $csvDataFormatter;
    }

    /**
     * Format data for transaction csv
     * @param Collection $activities
     * @return array
     */
    public function format(Collection $activities)
    {
        if ($activities->isEmpty()) {
            return false;
        }
        $this->csvData = ['headers' => $this->headers];
        foreach ($activities as $activity) {
            $this->csvData = array_merge($this->csvData, $this->formatTransactions($activity));
        }

        if (count($this->csvData) == 1) {
            return null;
        }

        return $this->csvData;
    }

    /**
     * Format Transaction
     * @param $activity
     * @return array
     */
    public function formatTransactions($activity)
    {
        $transactionData = [];

        foreach ($activity->transactions as $transaction) {
            $transaction       = $transaction->transaction;
            $transactionData[] = [
                'Activity_Identifier'              => getVal($activity->identifier, ['iati_identifier_text'], ''),
                'Activity_Title'                   => !($activity->title) ? '' : $this->csvDataFormatter->formatTitle($activity->title),
                'Transaction-ref'                  => getVal($transaction, ['reference'], ''),
                'TransactionType-code'             => getVal($transaction, ['transaction_type', 0, 'transaction_type_code'], null),
                'TransactionDate-iso_date'         => getVal($transaction, ['transaction_date', 0, 'date'], null),
                'TransactionValue-currency'        => getVal($transaction, ['value', 0, 'currency'], null),
                'TransactionValue-value_date'      => getVal($transaction, ['value', 0, 'date'], null),
                'TransactionValue-text'            => getVal($transaction, ['value', 0, 'amount'], null),
                'Description_text'                 => !(getVal($transaction, ['description', 0, 'narrative'], null)) ? '' : $this->concatenateIntoString($transaction['description'], 'narrative', true, 'narrative'),
                'ProviderOrg-ref'                  => getVal($transaction, ['provider_organization', 0, 'organization_identifier_code'], ''),
                'ProviderOrg-provider_activity_id' => getVal($transaction, ['provider_organization', 0, 'provider_activity_id'], ''),
                'ProviderOrg-Narrative_text'       => !(getVal($transaction, ['provider_organization', 0, 'narrative'], null)) ? '' : $this->concatenateIntoString(
                    $transaction['provider_organization'],
                    'narrative',
                    true,
                    'narrative'
                ),
                'ReceiverOrg-ref'                  => (getVal($transaction, ['receiver_organization', 0, 'organization_identifier_code'], '')),
                'ReceiverOrg-receiver_activity_id' => (getVal($transaction, ['receiver_organization', 0, 'receiver_activity_id'], '')),
                'ReceiverOrg-Narrative_text'       => (getVal($transaction, ['receiver_organization', 0, 'narrative'], '')) ? '' : $this->concatenateIntoString(
                    $transaction['receiver_organization'],
                    'narrative',
                    true,
                    'narrative'
                ),
                'DisbursementChannel-code'         => getVal($transaction, ['disbursement_channel', 0, 'disbursement_channel_code'], ''),
                'Sector-vocabulary'                => getVal($transaction, ['sector', 0, 'sector_vocabulary'], ''),
                'Sector-code'                      => (!(getVal($transaction, ['sector', 0, 'sector_code'], '')) && !(getVal($transaction, ['sector', 0, 'sector_category_code'], '')) && !(getVal($transaction, ['sector', 0, 'sector_text'], ''))) ? '' : $this->formatSector(
                    $transaction['sector'][0]
                ),
                'RecipientCountry-code'            => getVal($transaction, ['recipient_country', 0, 'country_code'], ''),
                'RecipientRegion-vocabulary'       => getVal($transaction, ['recipient_region', 0, 'vocabulary'], ''),
                'RecipientRegion-code'             => getVal($transaction, ['recipient_region', 0, 'region_code'], ''),
                'FlowType-code'                    => getVal($transaction, ['flow_type', 0, 'flow_type'], ''),
                'FinanceType-code'                 => getVal($transaction, ['finance_type', 0, 'finance_type'], ''),
                'AidType-code'                     => getVal($transaction, ['aid_type', 0, 'aid_type'], ''),
                'TiedStatus-code'                  => getVal($transaction, ['tied_status', 0, 'tied_status_code'], '')
            ];
        }

        return $transactionData;
    }

    /**
     * Format Transaction Sector
     * @param $transactionSector
     * @return mixed
     */
    protected function formatSector($transactionSector)
    {
        if ($transactionSector['sector_vocabulary'] == 1) {
            $sector = $transactionSector['sector_code'];
        } elseif ($transactionSector['sector_vocabulary'] == 2) {
            $sector = $transactionSector['sector_category_code'];
        } else {
            $sector = $transactionSector['sector_text'];
        }

        return $sector;
    }
}
