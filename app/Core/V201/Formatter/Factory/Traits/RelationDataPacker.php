<?php namespace App\Core\V201\Formatter\Factory\Traits;


/**
 * Class RelationDataPacker
 *
 * Packs data from an Activity's relation into a single array.
 * @package App\Core\V201\Formatter\Factory\Traits
 */
trait RelationDataPacker
{
    /**
     * Pack Transactions specific data for Complete Csv.
     * @param       $activityId
     * @param array $transactionDataHolder
     * @param array $data
     * @return array
     */
    protected function packTransactionData($activityId, array $transactionDataHolder, array $data)
    {
        $data[$activityId]['Activity_transaction_ref']                                 = implode(';', $transactionDataHolder['reference']);
        $data[$activityId]['Activity_transaction_transactiontype_code']                = implode(';', $transactionDataHolder['transactionType']);
        $data[$activityId]['Activity_transaction_transactiondate_iso_date']            = implode(';', $transactionDataHolder['transactionDate']);
        $data[$activityId]['Activity_transaction_value_currency']                      = implode(';', $transactionDataHolder['value']['currency']);
        $data[$activityId]['Activity_transaction_value_value_date']                    = implode(';', $transactionDataHolder['value']['date']);
        $data[$activityId]['Activity_transaction_value_text']                          = implode(';', $transactionDataHolder['value']['text']);
        $data[$activityId]['Activity_transaction_description_narrative_xml_lang']      = implode(';', $transactionDataHolder['description']['language']);
        $data[$activityId]['Activity_transaction_description_narrative_text']          = implode(';', $transactionDataHolder['description']['narrative']);
        $data[$activityId]['Activity_transaction_providerorg_provider_activity_id']    = implode(';', $transactionDataHolder['providerOrg']['providerActivityId']);
        $data[$activityId]['Activity_transaction_providerorg_ref']                     = implode(';', $transactionDataHolder['providerOrg']['reference']);
        $data[$activityId]['Activity_transaction_providerorg_narrative_xml_lang']      = implode(';', $transactionDataHolder['providerOrg']['narrative']['language']);
        $data[$activityId]['Activity_transaction_providerorg_narrative_text']          = implode(';', $transactionDataHolder['providerOrg']['narrative']['narrative']);
        $data[$activityId]['Activity_transaction_receiverorg_receiver_activity_id']    = implode(';', $transactionDataHolder['receiverOrg']['receiverActivityId']);
        $data[$activityId]['Activity_transaction_receiverorg_ref']                     = implode(';', $transactionDataHolder['receiverOrg']['reference']);
        $data[$activityId]['Activity_transaction_receiverorg_narrative_xml_lang']      = implode(';', $transactionDataHolder['receiverOrg']['narrative']['language']);
        $data[$activityId]['Activity_transaction_receiverorg_narrative_text']          = implode(';', $transactionDataHolder['receiverOrg']['narrative']['narrative']);
        $data[$activityId]['Activity_transaction_disbursementchannel_code']            = implode(';', $transactionDataHolder['disbursementChannel']);
        $data[$activityId]['Activity_transaction_sector_vocabulary']                   = implode(';', $transactionDataHolder['sector']['vocabulary']);
        $data[$activityId]['Activity_transaction_sector_code']                         = implode(';', $transactionDataHolder['sector']['code']);
        $data[$activityId]['Activity_transaction_sector_narrative_xml_lang']           = implode(';', $transactionDataHolder['sector']['narrative']['language']);
        $data[$activityId]['Activity_transaction_sector_narrative_text']               = implode(';', $transactionDataHolder['sector']['narrative']['narrative']);
        $data[$activityId]['Activity_transaction_recipientcountry_code']               = implode(';', $transactionDataHolder['recipientCountry']['code']);
        $data[$activityId]['Activity_transaction_recipientcountry_narrative_xml_lang'] = implode(';', $transactionDataHolder['recipientCountry']['narrative']['language']);
        $data[$activityId]['Activity_transaction_recipientcountry_narrative_text']     = implode(';', $transactionDataHolder['recipientCountry']['narrative']['narrative']);
        $data[$activityId]['Activity_transaction_recipientregion_code']                = implode(';', $transactionDataHolder['recipientRegion']['code']);
        $data[$activityId]['Activity_transaction_recipientregion_vocabulary']          = implode(';', $transactionDataHolder['recipientRegion']['vocabulary']);
        $data[$activityId]['Activity_transaction_recipientregion_narrative_xml_lang']  = implode(';', $transactionDataHolder['recipientRegion']['narrative']['language']);
        $data[$activityId]['Activity_transaction_recipientregion_narrative_text']      = implode(';', $transactionDataHolder['recipientRegion']['narrative']['narrative']);

        return $data;
    }

    /**
     * Pack Result specific data for Complete Csv.
     * @param       $activityId
     * @param array $resultMetaData
     * @param array $data
     * @return array
     */
    protected function packResultsData($activityId, array $resultMetaData, array $data)
    {
        $data[$activityId]['Activity_result_type'] = array_key_exists($activityId, $resultMetaData['type']) ? implode(';', $resultMetaData['type'][$activityId]) : '';

        $data[$activityId]['Activity_result_aggregation_status'] = array_key_exists($activityId, $resultMetaData['aggregationStatus'])
            ? implode(';', $resultMetaData['aggregationStatus'][$activityId])
            : '';


        if (array_key_exists($activityId, $resultMetaData['title'])) {
            $data[$activityId]['Activity_result_title_narrative_xml_lang'] = implode(';', $resultMetaData['title'][$activityId]['language']);
            $data[$activityId]['Activity_result_title_narrative_text']     = implode(';', $resultMetaData['title'][$activityId]['narrative']);
        }

        if (array_key_exists($activityId, $resultMetaData['description'])) {
            $data[$activityId]['Activity_result_description_narrative_xml_lang'] = implode(';', $resultMetaData['description'][$activityId]['language']);
            $data[$activityId]['Activity_result_description_narrative_text']     = implode(';', $resultMetaData['description'][$activityId]['narrative']);
        }

        if (array_key_exists($activityId, $resultMetaData['indicator'])) {
            $data[$activityId]['Activity_result_indicator_measure']   = implode(';', $resultMetaData['indicator'][$activityId]['measure']);
            $data[$activityId]['Activity_result_indicator_ascending'] = implode(';', $resultMetaData['indicator'][$activityId]['ascending']);

            $data[$activityId]['Activity_result_indicator_title_narrative_xml_lang'] = implode(';', $resultMetaData['indicator'][$activityId]['title']['language']);
            $data[$activityId]['Activity_result_indicator_title_narrative_text']     = implode(';', $resultMetaData['indicator'][$activityId]['title']['narrative']);

            $data[$activityId]['Activity_result_indicator_description_narrative_xml_lang'] = implode(';', $resultMetaData['indicator'][$activityId]['description']['language']);
            $data[$activityId]['Activity_result_indicator_description_narrative_text']     = implode(';', $resultMetaData['indicator'][$activityId]['description']['narrative']);
            $data[$activityId]['Activity_result_indicator_baseline_year']                  = implode(';', $resultMetaData['indicator'][$activityId]['baseline']['year']);
            $data[$activityId]['Activity_result_indicator_baseline_value']                 = implode(';', $resultMetaData['indicator'][$activityId]['baseline']['value']);

            $data[$activityId]['Activity_result_indicator_baseline_comment_narrative_xml_lang'] = implode(';', $resultMetaData['indicator'][$activityId]['baseline']['comment']['language']);
            $data[$activityId]['Activity_result_indicator_baseline_comment_narrative_text']     = implode(';', $resultMetaData['indicator'][$activityId]['baseline']['comment']['narrative']);

            $data[$activityId]['Activity_result_indicator_period_periodstart_iso_date'] = implode(';', $resultMetaData['indicator'][$activityId]['period']['period_start']);
            $data[$activityId]['Activity_result_indicator_period_periodend_iso_date']   = implode(';', $resultMetaData['indicator'][$activityId]['period']['period_end']);

            $data[$activityId]['Activity_result_indicator_period_target_value'] = implode(';', $resultMetaData['indicator'][$activityId]['period']['target']['value']);

            $data[$activityId]['Activity_result_indicator_period_target_comment_narrative_xml_lang'] = implode(
                ';',
                $resultMetaData['indicator'][$activityId]['period']['target']['comment']['language']
            );

            $data[$activityId]['Activity_result_indicator_period_target_comment_narrative_text'] = implode(
                ';',
                $resultMetaData['indicator'][$activityId]['period']['target']['comment']['narrative']
            );

            $data[$activityId]['Activity_result_indicator_period_actual_value'] = implode(
                ';',
                $resultMetaData['indicator'][$activityId]['period']['actual']['value']
            );

            $data[$activityId]['Activity_result_indicator_period_actual_comment_narrative_xml_lang'] = implode(
                ';',
                $resultMetaData['indicator'][$activityId]['period']['actual']['comment']['language']
            );

            $data[$activityId]['Activity_result_indicator_period_actual_comment_narrative_text'] = implode(
                ';',
                $resultMetaData['indicator'][$activityId]['period']['actual']['comment']['narrative']
            );
        }

        return $data;
    }
}
