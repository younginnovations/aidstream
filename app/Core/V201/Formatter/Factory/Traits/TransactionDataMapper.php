<?php namespace App\Core\V201\Formatter\Factory\Traits;


/**
 * Class TransactionDataMapper
 * @package App\Core\V201\Formatter\Factory\Traits
 */
trait TransactionDataMapper
{
    /**
     * Mapping of V201 transaction data
     *
     * Format Transaction
     * @param $transaction
     * @return array
     */
    public function formatTransactionsV201($transaction)
    {
        $transactionData = [
            'Transaction Internal Reference'            => getVal($transaction, ['reference'], ''),
            'Transaction Type'                          => getVal($transaction, ['transaction_type', 0, 'transaction_type_code'], null),
            'Transaction Date'                          => getVal($transaction, ['transaction_date', 0, 'date'], null),
            'Transaction Currency'                      => getVal($transaction, ['value', 0, 'currency'], null),
            'Transaction Value Date'                    => getVal($transaction, ['value', 0, 'date'], null),
            'Transaction Amount'                        => getVal($transaction, ['value', 0, 'amount'], null),
            'Transaction Description'                   => !(getVal($transaction, ['description', 0, 'narrative'], null)) ? '' : $this->concatenateIntoString(
                $transaction['description'],
                'narrative',
                true,
                'narrative'
            ),
            'Sector Vocabulary'                         => getVal($transaction, ['sector', 0, 'sector_vocabulary'], ''),
            'Sector Code'                               => (!(getVal($transaction, ['sector', 0, 'sector_code'], '')) && !(getVal(
                    $transaction,
                    ['sector', 0, 'sector_category_code'],
                    ''
                )) && !(getVal(
                    $transaction,
                    ['sector', 0, 'sector_text'],
                    ''
                ))) ? '' : $this->formatSector(
                $transaction['sector'][0]
            ),
            'Recipient Country Code'                    => getVal($transaction, ['recipient_country', 0, 'country_code'], ''),
            'Recipient Region Code'                     => getVal($transaction, ['recipient_region', 0, 'region_code'], ''),
            'Provider Organisation Identifier'          => getVal($transaction, ['provider_organization', 0, 'organization_identifier_code'], ''),
            'Provider Organisation Activity Identifier' => getVal($transaction, ['provider_organization', 0, 'provider_activity_id'], ''),
            'Provider Organisation Description'         => !(getVal($transaction, ['provider_organization', 0, 'narrative'], null)) ? '' : $this->concatenateIntoString(
                $transaction['provider_organization'],
                'narrative',
                true,
                'narrative'
            ),
            'Receiver Organisation Identifier'          => (getVal($transaction, ['receiver_organization', 0, 'organization_identifier_code'], '')),
            'Receiver Organisation Activity Identifier' => (getVal($transaction, ['receiver_organization', 0, 'receiver_activity_id'], '')),
            'Receiver Organisation Description'         => (getVal($transaction, ['receiver_organization', 0, 'narrative'], '')) ? '' : $this->concatenateIntoString(
                $transaction['receiver_organization'],
                'narrative',
                true,
                'narrative'
            ),
            'Disbursement Channel Code'                 => getVal($transaction, ['disbursement_channel', 0, 'disbursement_channel_code'], ''),
            'Flow Type Code'                            => getVal($transaction, ['flow_type', 0, 'flow_type'], ''),
            'Finance Type Code'                         => getVal($transaction, ['finance_type', 0, 'finance_type'], ''),
            'Aid Type Code'                             => getVal($transaction, ['aid_type', 0, 'aid_type'], ''),
            'Tied Status Code'                          => getVal($transaction, ['tied_status', 0, 'tied_status_code'], '')
        ];

        return $transactionData;
    }

    /**
     * Mapping of V202 Transaction
     *
     * @param $transaction
     * @return array
     */
    public function formatTransactionsV202($transaction)
    {
        $v201Transaction = $this->formatTransactionsV201($transaction);

        $transactionData                               = array_splice($v201Transaction, 0, 1);
        $transactionData['Humanitarian']               = getVal($transaction, ['humanitarian'], '');
        $transactionData                               = array_merge($transactionData, array_splice($v201Transaction, 0, 11));
        $transactionData['Provider Organisation Type'] = getVal($transaction, ['provider_organization', 0, 'type'], '');
        $transactionData                               = array_merge($transactionData, array_splice($v201Transaction, 0, 3));
        $transactionData['Receiver Organisation Type'] = getVal($transaction, ['receiver_organization', 0, 'type'], '');
        $transactionData                               = array_merge($transactionData, array_splice($v201Transaction, 0));

        return $transactionData;
    }

    /**
     * Format data version wise.
     *
     * @param $activity
     * @param $version
     * @return array
     */
    public function formatVersionWise($activity, $version)
    {
        $transactionData = [];
        $methodName      = sprintf('formatTransactions%s', $version);

        foreach ($activity->transactions as $index => $transaction) {
            $transaction = $transaction->transaction;
            if (method_exists($this, $methodName)) {
                $this->formatTransactionsV202($transaction);
                $transactionData[$index] = [
                    'Activity Identifier' => getVal($activity->identifier, ['iati_identifier_text'], '')
                ];
                $transactionData[$index] = array_merge($transactionData[$index], $this->{$methodName}($transaction));
            }
        }

        return $transactionData;
    }
}

