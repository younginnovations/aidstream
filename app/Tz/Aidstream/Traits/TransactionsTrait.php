<?php namespace App\Tz\Aidstream\Traits;

/**
 * Class TransactionsTrait
 * @package App\Tz\Aidstream\Traits
 */
trait TransactionsTrait
{

    /**
     * @param $transaction
     * @return array
     */
    public function formatTransactionFormsDataIntoJson($transaction)
    {
        $transactionData = [
            'reference'             => $transaction['reference'],
            'transaction_type'      => [
                [
                    'transaction_type_code' => $transaction['transaction_type']
                ]
            ],
            'transaction_date'      => [
                [
                    'date' => $transaction['transaction_date']
                ]
            ],
            'value'                 => [
                [
                    'amount'   => $transaction['amount'],
                    'currency' => $transaction['currency']
                ]
            ],
            'description'           => [
                [
                    'narrative' => [
                        [
                            'narrative' => $transaction['description']
                        ]
                    ]
                ]
            ],
            'receiver_organization' => [
                [
                    'narrative' => [
                        'narrative' => $transaction['receiver_org']
                    ]
                ]
            ]
        ];

        return [
            'activity_id' => $transaction['project_id'],
            'transaction' => $transactionData
        ];
    }
}
