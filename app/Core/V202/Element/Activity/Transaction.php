<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\Transaction as V201Transaction;
use Illuminate\Support\Collection;

/**
 * Class Transaction
 * @package App\Core\V202\Element\Activity
 */
class Transaction extends V201Transaction
{
    /**
     * @param $transactions
     * @return array
     */
    public function getXmlData(Collection $transactions)
    {
        $transactionData = [];

        foreach ($transactions as $totalTransaction) {
            $transaction = $totalTransaction->transaction;
            $vocabulary  = $transaction['sector'][0]['sector_vocabulary'];
            if ($vocabulary == 1) {
                $sectorValue = $transaction['sector'][0]['sector_code'];
            } elseif ($vocabulary == 2) {
                $sectorValue = $transaction['sector'][0]['sector_category_code'];
            } else {
                $sectorValue = $transaction['sector'][0]['sector_text'];
            }
            $transactionData[] = [
                '@attributes'          => [
                    'ref'          => $transaction['reference'],
                    'humanitarian' => getVal($transaction, ['humanitarian'])
                ],
                'transaction-type'     => [
                    '@attributes' => [
                        'code' => $transaction['transaction_type'][0]['transaction_type_code']
                    ]
                ],
                'transaction-date'     => [
                    '@attributes' => [
                        'iso-date' => $transaction['transaction_date'][0]['date']
                    ]
                ],
                'value'                => [
                    '@attributes' => [
                        'currency'   => $transaction['value'][0]['currency'],
                        'value-date' => $transaction['value'][0]['date']
                    ],
                    '@value'      => $transaction['value'][0]['amount']
                ],
                'description'          => [
                    'narrative' => $this->buildNarrative($transaction['description'][0]['narrative'])
                ],
                'provider-org'         => [
                    '@attributes' => [
                        'ref'                  => $transaction['provider_organization'][0]['organization_identifier_code'],
                        'provider-activity-id' => $transaction['provider_organization'][0]['provider_activity_id'],
                        'type'                 => getVal($transaction, ['provider_organization', 0, 'type'])
                    ],
                    'narrative'   => $this->buildNarrative($transaction['provider_organization'][0]['narrative'])
                ],
                'receiver-org'         => [
                    '@attributes' => [
                        'ref'                  => $transaction['receiver_organization'][0]['organization_identifier_code'],
                        'receiver-activity-id' => $transaction['receiver_organization'][0]['receiver_activity_id'],
                        'type'                 => getVal($transaction, ['receiver_organization', 0, 'type'])
                    ],
                    'narrative'   => $this->buildNarrative($transaction['receiver_organization'][0]['narrative'])
                ],
                'disbursement-channel' => [
                    '@attributes' => [
                        'code' => $transaction['disbursement_channel'][0]['disbursement_channel_code']
                    ]
                ],
                'sector'               => [
                    '@attributes' => [
                        'vocabulary'     => $vocabulary,
                        'vocabulary-url' => getVal($transaction, ['sector', 0, 'vocabulary_uri']),
                        'code'           => $sectorValue
                    ],
                    'narrative'   => $this->buildNarrative($transaction['sector'][0]['narrative'])
                ],
                'recipient-country'    => [
                    '@attributes' => [
                        'code' => $transaction['recipient_country'][0]['country_code']
                    ],
                    'narrative'   => $this->buildNarrative($transaction['recipient_country'][0]['narrative'])
                ],
                'recipient-region'     => [
                    '@attributes' => [
                        'code'           => $transaction['recipient_region'][0]['region_code'],
                        'vocabulary'     => $transaction['recipient_region'][0]['vocabulary'],
                        'vocabulary-uri' => getVal($transaction, ['recipient_region', 0, 'vocabulary_uri']),
                    ],
                    'narrative'   => $this->buildNarrative($transaction['recipient_region'][0]['narrative'])
                ],
                'flow-type'            => [
                    '@attributes' => [
                        'code' => $transaction['flow_type'][0]['flow_type'],
                    ]
                ],
                'finance-type'         => [
                    '@attributes' => [
                        'code' => $transaction['finance_type'][0]['finance_type'],
                    ]
                ],
                'aid-type'             => [
                    '@attributes' => [
                        'code' => $transaction['aid_type'][0]['aid_type'],
                    ]
                ],
                'tied-status'          => [
                    '@attributes' => [
                        'code' => $transaction['tied_status'][0]['tied_status_code'],
                    ]
                ]
            ];
        }

        return $transactionData;
    }
}
