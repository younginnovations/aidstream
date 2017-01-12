<?php namespace App\Core\V201\Formatter\Factory\Traits;

use App\Models\Activity\ActivityResult;
use App\Models\Activity\Transaction;

/**
 * Trait RelationDataProvider
 *
 * Provides data from an Activity's Relation (Transaction/Result).
 * @package App\Core\V201\Formatter\Factory\Traits
 */
trait RelationDataProvider
{
    use StringConcatenator;

    /**
     * Data holder for Narratives.
     * @var array
     */
    protected $narrative = ['narrative' => [], 'language' => []];

    /**
     * Gets a template for Transaction data.
     * @return array
     */
    protected function getTransactionDataTemplate()
    {
        return [
            'reference'           => [],
            'transactionType'     => [],
            'transactionDate'     => [],
            'value'               => ['currency' => [], 'date' => [], 'text' => []],
            'description'         => $this->narrative,
            'providerOrg'         => ['providerActivityId' => [], 'reference' => [], 'narrative' => $this->narrative],
            'receiverOrg'         => ['receiverActivityId' => [], 'reference' => [], 'narrative' => $this->narrative],
            'disbursementChannel' => [],
            'sector'              => ['vocabulary' => [], 'code' => [], 'narrative' => $this->narrative],
            'recipientCountry'    => ['code' => [], 'narrative' => $this->narrative],
            'recipientRegion'     => ['code' => [], 'vocabulary' => [], 'narrative' => $this->narrative]
        ];
    }

    /**
     * Gets a template for Results data.
     * @param $activityId
     * @return array
     */
    protected function getResultDataTemplate($activityId)
    {
        return [
            'type'              => [],
            'aggregationStatus' => [],
            'title'             => $this->narrative,
            'description'       => $this->narrative,
            'indicator'         => [$activityId => $this->getIndicatorTemplate()]
        ];
    }

    /**
     * Gets a template for Result Indicator data.
     * @return array
     */
    protected function getIndicatorTemplate()
    {
        return [
            'measure'     => [],
            'ascending'   => [],
            'title'       => $this->narrative,
            'description' => $this->narrative,
            'baseline'    => ['year' => [], 'value' => [], 'comment' => $this->narrative],
            'period'      => [
                'period_start' => [],
                'period_end'   => [],
                'target'       => ['value' => [], 'comment' => $this->narrative],
                'actual'       => [
                    'value'   => [],
                    'comment' => $this->narrative
                ]
            ],
        ];
    }

    /**
     * Get Transaction data.
     * @param Transaction $transaction
     * @return array
     */
    protected function transactionData(Transaction $transaction)
    {
        $transactionDataHolder = $this->getTransactionDataTemplate();

        $transactionDataHolder['reference'][]                         = $this->concatenateRelation($transaction, 'transaction', 'reference');
        $transactionDataHolder['transactionType'][]                   = $this->concatenateRelation($transaction, 'transaction', 'transaction_type', true, 'transaction_type_code');
        $transactionDataHolder['transactionDate'][]                   = $this->concatenateRelation($transaction, 'transaction', 'transaction_date', true, 'date');
        $transactionDataHolder['value']['currency'][]                 = $this->concatenateRelation($transaction, 'transaction', 'value', true, 'currency');
        $transactionDataHolder['value']['date'][]                     = $this->concatenateRelation($transaction, 'transaction', 'value', true, 'date');
        $transactionDataHolder['value']['text'][]                     = $this->concatenateRelation($transaction, 'transaction', 'value', true, 'amount');
        $transactionDataHolder['description']['language'][]           = $this->concatenateIntoString($transaction->transaction['description'], 'narrative', true, 'language');
        $transactionDataHolder['description']['narrative'][]          = $this->concatenateIntoString($transaction->transaction['description'], 'narrative', true, 'narrative');
        $transactionDataHolder['providerOrg']['providerActivityId'][] = $this->concatenateRelation($transaction, 'transaction', 'provider_organization', true, 'provider_activity_id');


        $transactionDataHolder['providerOrg']['reference'][]                   = $this->concatenateRelation(
            $transaction,
            'transaction',
            'provider_organization',
            true,
            'organization_identifier_code'
        );
        $transactionDataHolder['providerOrg']['narrative']['language'][]       = $this->concatenateIntoString(
            $transaction->transaction['provider_organization'],
            'narrative',
            true,
            'language'
        );
        $transactionDataHolder['providerOrg']['narrative']['narrative'][]      = $this->concatenateIntoString(
            $transaction->transaction['provider_organization'],
            'narrative',
            true,
            'narrative'
        );
        $transactionDataHolder['receiverOrg']['receiverActivityId'][]          = $this->concatenateRelation(
            $transaction,
            'transaction',
            'receiver_organization',
            true,
            'receiver_activity_id'
        );
        $transactionDataHolder['receiverOrg']['reference'][]                   = $this->concatenateRelation(
            $transaction,
            'transaction',
            'receiver_organization',
            true,
            'organization_identifier_code'
        );
        $transactionDataHolder['receiverOrg']['narrative']['language'][]       = $this->concatenateIntoString(
            $transaction->transaction['receiver_organization'],
            'narrative',
            true,
            'language'
        );
        $transactionDataHolder['receiverOrg']['narrative']['narrative'][]      = $this->concatenateIntoString(
            $transaction->transaction['receiver_organization'],
            'narrative',
            true,
            'narrative'
        );
        $transactionDataHolder['disbursementChannel'][]                        = $this->concatenateRelation(
            $transaction,
            'transaction',
            'disbursement_channel',
            true,
            'disbursement_channel_code'
        );
        $transactionDataHolder['sector']['vocabulary'][]                       = $this->concatenateRelation($transaction, 'transaction', 'sector', true, 'sector_vocabulary');
        $transactionDataHolder['sector']['code'][]                             = $this->concatenateRelation($transaction, 'transaction', 'sector', true, 'sector_code');
        $transactionDataHolder['sector']['narrative']['language'][]            = $this->concatenateIntoString(getVal($transaction->transaction, ['sector'], []), 'narrative', true, 'language');
        $transactionDataHolder['sector']['narrative']['narrative'][]           = $this->concatenateIntoString(getVal($transaction->transaction, ['sector'], []), 'narrative', true, 'narrative');
        $transactionDataHolder['recipientCountry']['code'][]                   = $this->concatenateRelation($transaction, 'transaction', 'recipient_country', true, 'country_code');
        $transactionDataHolder['recipientCountry']['narrative']['language'][]  = $this->concatenateIntoString(getVal($transaction->transaction, ['recipient_country'], []), 'narrative', true, 'language');
        $transactionDataHolder['recipientCountry']['narrative']['narrative'][] = $this->concatenateIntoString(getVal($transaction->transaction, ['recipient_country'], []), 'narrative', true, 'narrative');
        $transactionDataHolder['recipientRegion']['code'][]                    = $this->concatenateRelation($transaction, 'transaction', 'recipient_region', true, 'region_code');
        $transactionDataHolder['recipientRegion']['vocabulary'][]              = $this->concatenateRelation($transaction, 'transaction', 'recipient_region', true, 'vocabulary');
        $transactionDataHolder['recipientRegion']['narrative']['language'][]   = $this->concatenateIntoString(getVal($transaction->transaction, ['recipient_region'], []), 'narrative', true, 'language');
        $transactionDataHolder['recipientRegion']['narrative']['language'][]   = $this->concatenateIntoString(getVal($transaction->transaction, ['recipient_region'], []), 'narrative', true, 'language');
        $transactionDataHolder['recipientRegion']['narrative']['narrative'][]  = $this->concatenateIntoString(getVal($transaction->transaction, ['recipient_region'], []), 'narrative', true, 'narrative');

        return $transactionDataHolder;
    }

    /**
     * Get a Result's Data for an Activity.
     * @param                $activityId
     * @param ActivityResult $result
     * @param array          $resultMetaData
     * @return array
     */
    protected function resultData($activityId, ActivityResult $result, array $resultMetaData)
    {
        $resultMetaData['type'][$activityId][]              = $result->result['type'];
        $resultMetaData['aggregationStatus'][$activityId][] = $result->result['aggregation_status'];

        $resultMetaData['title'][$activityId]['language'][]  = $this->concatenateIntoString($result->result['title'], 'narrative', true, 'language');
        $resultMetaData['title'][$activityId]['narrative'][] = $this->concatenateIntoString($result->result['title'], 'narrative', true, 'narrative');

        $resultMetaData['description'][$activityId]['language'][]  = $this->concatenateIntoString($result->result['description'], 'narrative', true, 'language');
        $resultMetaData['description'][$activityId]['narrative'][] = $this->concatenateIntoString($result->result['description'], 'narrative', true, 'language');

        $resultMetaData['indicator'][$activityId]['measure'][]   = $this->concatenateIntoString($result->result['indicator'], 'measure');
        $resultMetaData['indicator'][$activityId]['ascending'][] = $this->concatenateIntoString($result->result['indicator'], 'ascending');

        $resultMetaData['indicator'][$activityId]['title']['language'][]  = $this->concatenateIndicator($result->result['indicator'], 'title', 'narrative', 'language');
        $resultMetaData['indicator'][$activityId]['title']['narrative'][] = $this->concatenateIndicator($result->result['indicator'], 'title', 'narrative', 'narrative');

        $resultMetaData['indicator'][$activityId]['description']['language'][]  = $this->concatenateIndicator($result->result['indicator'], 'description', 'narrative', 'language');
        $resultMetaData['indicator'][$activityId]['description']['narrative'][] = $this->concatenateIndicator($result->result['indicator'], 'description', 'narrative', 'narrative');

        $resultMetaData['indicator'][$activityId]['baseline']['year'][]  = $this->concatenateIndicator($result->result['indicator'], 'baseline', 'year');
        $resultMetaData['indicator'][$activityId]['baseline']['value'][] = $this->concatenateIndicator($result->result['indicator'], 'baseline', 'value');

        $resultMetaData['indicator'][$activityId]['baseline']['comment']['language'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'baseline',
            'comment',
            'narrative',
            'language'
        );

        $resultMetaData['indicator'][$activityId]['baseline']['comment']['narrative'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'baseline',
            'comment',
            'narrative',
            'narrative'
        );

        $resultMetaData['indicator'][$activityId]['period']['period_start'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'period_start',
            'date'
        );

        $resultMetaData['indicator'][$activityId]['period']['period_end'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'period_end',
            'date'
        );

        $resultMetaData['indicator'][$activityId]['period']['target']['value'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'target',
            'value'
        );

        $resultMetaData['indicator'][$activityId]['period']['target']['comment']['language'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'target',
            'comment',
            'narrative',
            'language'
        );

        $resultMetaData['indicator'][$activityId]['period']['target']['comment']['narrative'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'target',
            'comment',
            'narrative',
            'narrative'
        );

        $resultMetaData['indicator'][$activityId]['period']['actual']['value'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'actual',
            'value'
        );

        $resultMetaData['indicator'][$activityId]['period']['actual']['comment']['language'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'actual',
            'comment',
            'narrative',
            'language'
        );

        $resultMetaData['indicator'][$activityId]['period']['actual']['comment']['narrative'][] = $this->concatenateIndicator(
            $result->result['indicator'],
            'period',
            'actual',
            'comment',
            'narrative',
            'narrative'
        );

        return $resultMetaData;
    }
}
