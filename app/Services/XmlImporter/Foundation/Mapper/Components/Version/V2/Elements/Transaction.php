<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2\Elements;

use App\Services\XmlImporter\Foundation\Support\Helpers\Traits\XmlHelper;

/**
 * Class Transaction
 * @package App\Services\XmlImporter\Mapper\V103\Activity\Elements
 */
class Transaction
{
    use XmlHelper;

    /**
     * @var array
     */
    protected $transaction = [];

    /**
     * Map raw Xml Transaction data for import.
     *
     * @param array $transactions
     * @param       $template
     * @return array
     */
    public function map(array $transactions, $template)
    {
        foreach ($transactions as $index => $transaction) {
            $this->transaction[$index] = $template['transaction'];
            $this->reference($transaction, $index);
            $this->humanitarian($transaction, $index);

            foreach ($this->getValue($transaction) as $subElement) {
                $fieldName = $this->name($subElement['name']);

                $this->$fieldName($subElement, $index);
            }
        }

        return $this->transaction;
    }

    /**
     * @param $element
     * @param $index
     */
    protected function reference($element, $index)
    {
        $this->transaction[$index]['reference'] = $this->attributes($element, 'ref');
    }

    protected function humanitarian($element, $index)
    {
        $this->transaction[$index]['humanitarian'] = $this->attributes($element, 'humanitarian');
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function transactionType($subElement, $index)
    {
        $this->transaction[$index]['transaction_type'][0]['transaction_type_code'] = $this->attributes($subElement, 'code');
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function transactionDate($subElement, $index)
    {
        $this->transaction[$index]['transaction_date'][0]['date'] = dateFormat('Y-m-d', $this->attributes($subElement, 'iso-date'));
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function value($subElement, $index)
    {
        $this->transaction[$index]['value'][0]['amount']   = $this->getValue($subElement);
        $this->transaction[$index]['value'][0]['date']     = dateFormat('Y-m-d', $this->attributes($subElement, 'value-date'));
        $this->transaction[$index]['value'][0]['currency'] = $this->attributes($subElement, 'currency');
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function description($subElement, $index)
    {
        $this->transaction[$index]['description'][0]['narrative'] = $this->narrative($subElement);
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function providerOrg($subElement, $index)
    {
        $this->transaction[$index]['provider_organization'][0]['organization_identifier_code'] = $this->attributes($subElement, 'ref');
        $this->transaction[$index]['provider_organization'][0]['type']                         = $this->attributes($subElement, 'type');
        $this->transaction[$index]['provider_organization'][0]['provider_activity_id']         = $this->attributes($subElement, 'provider-activity-id');
        $this->transaction[$index]['provider_organization'][0]['narrative']                    = $this->narrative($subElement);
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function receiverOrg($subElement, $index)
    {
        $this->transaction[$index]['receiver_organization'][0]['organization_identifier_code'] = $this->attributes($subElement, 'ref');
        $this->transaction[$index]['receiver_organization'][0]['type']                         = $this->attributes($subElement, 'type');
        $this->transaction[$index]['receiver_organization'][0]['receiver_activity_id']         = $this->attributes($subElement, 'receiver-activity-id');
        $this->transaction[$index]['receiver_organization'][0]['narrative']                    = $this->narrative($subElement);
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function disbursementChannel($subElement, $index)
    {
        $this->transaction[$index]['disbursement_channel'][0]['disbursement_channel_code'] = $this->attributes($subElement, 'code');
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function sector($subElement, $index)
    {
        $this->transaction[$index]['sector'][0]['sector_vocabulary']    = ($vocabulary = $this->attributes($subElement, 'vocabulary'));
        $this->transaction[$index]['sector'][0]['sector_code']          = ($vocabulary == 1) ? $this->attributes($subElement, 'code') : "";
        $this->transaction[$index]['sector'][0]['sector_category_code'] = ($vocabulary == 2) ? $this->attributes($subElement, 'code') : "";
        $this->transaction[$index]['sector'][0]['sector_text']          = ($vocabulary != 1 && $vocabulary != 2) ? $this->attributes($subElement, 'code') : "";
        $this->transaction[$index]['sector'][0]['vocabulary_uri']       = $this->attributes($subElement, 'vocabulary-uri');
        $this->transaction[$index]['sector'][0]['narrative']            = $this->narrative($subElement);
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function recipientCountry($subElement, $index)
    {
        $this->transaction[$index]['recipient_country'][0]['country_code'] = $this->attributes($subElement, 'code');
        $this->transaction[$index]['recipient_country'][0]['narrative']    = $this->narrative($subElement);

    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function recipientRegion($subElement, $index)
    {
        $this->transaction[$index]['recipient_region'][0]['region_code']    = $this->attributes($subElement, 'code');
        $this->transaction[$index]['recipient_region'][0]['vocabulary']     = $this->attributes($subElement, 'vocabulary');
        $this->transaction[$index]['recipient_region'][0]['vocabulary_uri'] = $this->attributes($subElement, 'vocabulary-uri');
        $this->transaction[$index]['recipient_region'][0]['narrative']      = $this->narrative($subElement);

    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function flowType($subElement, $index)
    {
        $this->transaction[$index]['flow_type'][0]['flow_type'] = $this->attributes($subElement, 'code');
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function financeType($subElement, $index)
    {
        $this->transaction[$index]['finance_type'][0]['finance_type'] = $this->attributes($subElement, 'code');
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function aidType($subElement, $index)
    {

        $vocabulary = $this->attributes($subElement, 'vocabulary');
        if($vocabulary == 1){
            $this->transaction[$index]['aid_type'][0]['default_aid_type']             = $this->attributes($subElement, 'code');
        } else if ($vocabulary == 2) {
            $this->transaction[$index]['aid_type'][0]['aidtype_earmarking_category']  = $this->attributes($subElement, 'code');
        } else if ($vocabulary == 3) {
            $this->transaction[$index]['aid_type'][0]['default_aid_type_text']        = $this->attributes($subElement, 'code');
        } else if($vocabulary == 4){
            $this->transaction[$index]['aid_type'][0]['cash_and_voucher_modalities']  = $this->attributes($subElement, 'code');
        }

        $this->transaction[$index]['aid_type'][0]['default_aidtype_vocabulary'] = $vocabulary;
    }

    /**
     * @param $subElement
     * @param $index
     */
    protected function tiedStatus($subElement, $index)
    {
        $this->transaction[$index]['tied_status'][0]['tied_status_code'] = $this->attributes($subElement, 'code');
    }

    /**
     * @param array $element
     * @return array
     */
    protected function getValue(array $element)
    {
        return getVal($element, ['value'], []);
    }
}
