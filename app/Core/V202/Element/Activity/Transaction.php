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
            $vocabulary  = getVal($transaction, ['sector', 0, 'sector_vocabulary']);
            if ($vocabulary == 1) {
                $sectorValue = getVal($transaction, ['sector', 0, 'sector_code']);
            } elseif ($vocabulary == 2) {
                $sectorValue = getVal($transaction, ['sector', 0, 'sector_category_code']);
            } elseif ($vocabulary == "") {
                $sectorValue = getVal($transaction, ['sector', 0, 'sector_code']);
            } else {
                $sectorValue = getVal($transaction, ['sector', 0, 'sector_text']);
            }
            $transactionData[] = [
                '@attributes'          => [
                    'ref'          => $transaction['reference'],
                    'humanitarian' => getVal($transaction, ['humanitarian'])
                ],
                'transaction-type'     => [
                    '@attributes' => [
                        'code' => getVal($transaction,['transaction_type',0,'transaction_type_code'])
                    ]
                ],
                'transaction-date'     => [
                    '@attributes' => [
                        'iso-date' => getVal($transaction,['transaction_date',0,'date'])
                    ]
                ],
                'value'                => [
                    '@attributes' => [
                        'currency'   => getVal($transaction,['value',0,'currency']),
                        'value-date' => getVal($transaction, ['value', 0, 'date'])
                    ],
                    '@value'      => getVal($transaction,['value',0,'amount'])
                ],
                'description'          => [
                    'narrative' => $this->buildNarrative(getVal($transaction, ['description', 0, 'narrative'], []))
                ],
                'provider-org'         => [
                    '@attributes' => [
                        'ref'                  => getVal($transaction,['provider_organization',0,'organization_identifier_code']),
                        'provider-activity-id' => getVal($transaction,['provider_organization',0,'provider_activity_id']),
                        'type'                 => getVal($transaction, ['provider_organization', 0, 'type'])
                    ],
                    'narrative'   => $this->buildNarrative(getVal($transaction, ['provider_organization', 0, 'narrative'], []))
                ],
                'receiver-org'         => [
                    '@attributes' => [
                        'ref'                  => getVal($transaction,['receiver_organization',0,'organization_identifier_code']),
                        'receiver-activity-id' => getVal($transaction,['receiver_organization',0,'receiver_activity_id']),
                        'type'                 => getVal($transaction, ['receiver_organization', 0, 'type'])
                    ],
                    'narrative'   => $this->buildNarrative(getVal($transaction, ['receiver_organization', 0, 'narrative'], []))
                ],
                'disbursement-channel' => [
                    '@attributes' => [
                        'code' => getVal($transaction,['disbursement_channel',0,'disbursement_channel_code'])
                    ]
                ],
                'sector'               => [
                    '@attributes' => [
                        'vocabulary'     => $vocabulary,
                        'vocabulary-uri' => getVal($transaction, ['sector', 0, 'vocabulary_uri']),
                        'code'           => $sectorValue
                    ],
                    'narrative'   => $this->buildNarrative(getVal($transaction, ['sector', 0, 'narrative'], []))
                ],
                'recipient-country'    => [
                    '@attributes' => [
                        'code' => getVal($transaction,['recipient_country',0,'country_code'])
                    ],
                    'narrative'   => $this->buildNarrative(getVal($transaction, ['recipient_country', 0, 'narrative'], []))
                ],
                'recipient-region'     => [
                    '@attributes' => [
                        'code'           => getVal($transaction,['recipient_region',0,'region_code']),
                        'vocabulary'     => getVal($transaction,['recipient_region',0,'vocabulary']),
                        'vocabulary-uri' => getVal($transaction, ['recipient_region', 0, 'vocabulary_uri']),
                    ],
                    'narrative'   => $this->buildNarrative(getVal($transaction, ['recipient_region', 0, 'narrative'], []))
                ],
                'flow-type'            => [
                    '@attributes' => [
                        'code' => getVal($transaction,['flow_type',0,'flow_type']),
                    ]
                ],
                'finance-type'         => [
                    '@attributes' => [
                        'code' => getVal($transaction,['finance_type',0,'finance_type']),
                    ]
                ],
                'aid-type'             => [
                    '@attributes' => [
                        'code' => getVal($transaction,['aid_type',0,'aid_type']),
                    ]
                ],
                'tied-status'          => [
                    '@attributes' => [
                        'code' => getVal($transaction,['tied_status',0,'tied_status_code']),
                    ]
                ]
            ];
        }

        return $transactionData;
    }
}
