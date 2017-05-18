<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements\Transaction;


/**
 * Class PreparesTransactionData
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements\Transaction
 */
trait PreparesTransactionData
{
    /**
     * Set Internal Reference for Transaction
     * @param $key
     * @param $value
     */
    protected function setInternalReference($key, $value)
    {
        if ($key == $this->_csvHeaders[0]) {
            $this->data['transaction']['reference'] = $value;
        }
    }

    /**
     * Set the Humanitarian field for the Transaction Element.
     */
    protected function setHumanitarian()
    {
        if (array_key_exists('reference', $this->data)) {
            $this->data['transaction']['humanitarian'] = '';
        }
    }

    /**
     * Set the Transaction Type for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setTransactionType($key, $value)
    {
        if ($key == $this->_csvHeaders[1]) {
            $validTransactionType = $this->loadCodeList('TransactionType', 'V201');

            foreach ($validTransactionType['TransactionType'] as $type) {
                if (ucwords($value) == $type['name']) {
                    $value = $type['code'];
                }
            }
            $this->data['transaction']['transaction_type'][] = ['transaction_type_code' => $value];
        }
    }

    /**
     * Set the date for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setTransactionDate($key, $value)
    {
        if ($key == $this->_csvHeaders[2]) {
            $this->data['transaction']['transaction_date'][] = ['date' => dateFormat('Y-m-d', $value)];
        }
    }

    /**
     * Set the value for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setTransactionValue($key, $value)
    {
        if ($key == $this->_csvHeaders[3]) {
            $this->data['transaction']['value'][0]['amount'] = $value;
        }
    }

    /**
     * Set the value date for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setTransactionValueDate($key, $value)
    {
        if ($key == $this->_csvHeaders[4]) {
            $this->data['transaction']['value'][0]['date']     = dateFormat('Y-m-d', $value);
            $this->data['transaction']['value'][0]['currency'] = '';
        }
    }

    /**
     * Set the description for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setTransactionDescription($key, $value)
    {
        if ($key == $this->_csvHeaders[5]) {
            $this->data['transaction']['description'][0]['narrative'][0] = ['narrative' => $value, 'language' => ''];
        }
    }

    /**
     * Set the Provider Organization for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setProviderOrganization($key, $value)
    {
        if ($key == $this->_csvHeaders[6]) {
            $this->data['transaction']['provider_organization'][0]['organization_identifier_code'] = $value;
        }
        if ($key == $this->_csvHeaders[7]) {
            $this->data['transaction']['provider_organization'][0]['provider_activity_id'] = $value;
        }
        if ($key == $this->_csvHeaders[8]) {
            $this->data['transaction']['provider_organization'][0]['type'] = $this->setOrganizationTypeNameToCode($value);
        }
        if ($key == $this->_csvHeaders[9]) {
            $this->data['transaction']['provider_organization'][0]['narrative'][0] = ['narrative' => $value, 'language' => ''];
        }
    }

    /**
     * Set the Receiver Organization for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setReceiverOrganization($key, $value)
    {
        if ($key == $this->_csvHeaders[10]) {
            $this->data['transaction']['receiver_organization'][0]['organization_identifier_code'] = $value;
        }
        if ($key == $this->_csvHeaders[11]) {
            $this->data['transaction']['receiver_organization'][0]['receiver_activity_id'] = $value;
        }
        if ($key == $this->_csvHeaders[12]) {
            $this->data['transaction']['receiver_organization'][0]['type'] = $this->setOrganizationTypeNameToCode($value);
        }
        if ($key == $this->_csvHeaders[13]) {
            $this->data['transaction']['receiver_organization'][0]['narrative'][0] = ['narrative' => $value, 'language' => ''];
        }
    }

    /**
     * Set the Disbursement Channel for the Transaction Element.
     */
    protected function setDisbursementChannel()
    {
        if (array_key_exists('receiver_organization', $this->data['transaction'])) {
            $this->data['transaction']['disbursement_channel'][0] = ['disbursement_channel_code' => ''];
        }
    }

    /**
     * Set the Sector for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setSector($key, $value)
    {
        if ($key == $this->_csvHeaders[14]) {
            $this->data['transaction']['sector'][0]['sector_vocabulary'] = $value;
        }
        if ($key == $this->_csvHeaders[15]) {
            $sectorVocabulary = $this->data['transaction']['sector'][0]['sector_vocabulary'];
            $this->setSectorCode($sectorVocabulary, $value);
            $this->data['transaction']['sector'][0]['vocabulary_uri'] = '';
            $this->data['transaction']['sector'][0]['narrative'][0]   = ['narrative' => '', 'language' => ''];
        }
    }

    /**
     * Set the Sector code for the Transaction Element's Sector.
     * @param $sectorVocabulary
     * @param $value
     */
    protected function setSectorCode($sectorVocabulary, $value)
    {
        if ($sectorVocabulary == 1) {
            $this->data['transaction']['sector'][0]['sector_code'] = $value;
        } else {
            $this->data['transaction']['sector'][0]['sector_code'] = '';
        }

        if ($sectorVocabulary == 2) {
            $this->data['transaction']['sector'][0]['sector_category_code'] = $value;
        } else {
            $this->data['transaction']['sector'][0]['sector_category_code'] = '';
        }

        if ($sectorVocabulary != 1 && $sectorVocabulary != 2) {
            $this->data['transaction']['sector'][0]['sector_text'] = $value;
        } else {
            $this->data['transaction']['sector'][0]['sector_text'] = '';
        }
    }

    /**
     * Set the Recipient Country for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setRecipientCountry($key, $value)
    {
        if ($key == $this->_csvHeaders[16]) {
            $this->data['transaction']['recipient_country'][0]['country_code'] = $value;
            $this->data['transaction']['recipient_country'][0]['narrative'][0] = ['narrative' => '', 'language' => ''];
        }
    }

    /**
     * Set the Recipient Region for the Transaction Element.
     * @param $key
     * @param $value
     */
    protected function setRecipientRegion($key, $value)
    {
        if ($key == $this->_csvHeaders[17]) {
            $this->data['transaction']['recipient_region'][0]['region_code']    = $value;
            $this->data['transaction']['recipient_region'][0]['vocabulary']     = '';
            $this->data['transaction']['recipient_region'][0]['vocabulary_uri'] = '';
            $this->data['transaction']['recipient_region'][0]['narrative'][0]   = ['narrative' => '', 'language' => ''];
        }
    }

    /**
     * Set the Flow Type for the Transaction Element.
     */
    protected function setFlowType()
    {
        if (array_key_exists('recipient_region', $this->data['transaction'])) {
            $this->data['transaction']['flow_type'][0] = ['flow_type' => ''];
        }
    }

    /**
     * Set the Finance Type for the Transaction Element.
     */
    protected function setFinanceType()
    {
        if (array_key_exists('flow_type', $this->data['transaction'])) {
            $this->data['transaction']['finance_type'][0] = ['finance_type' => ''];
        }
    }

    /**
     * Set the Aid Type for the Transaction Element.
     */
    protected function setAidType()
    {
        if (array_key_exists('finance_type', $this->data['transaction'])) {
            $this->data['transaction']['aid_type'][0] = ['aid_type' => ''];
        }
    }

    /**
     *
     */
    protected function setTiedStatus()
    {
        if (array_key_exists('aid_type', $this->data['transaction'])) {
            $this->data['transaction']['tied_status'][0] = ['tied_status_code' => ''];
        }
    }

    /**
     * Load the provided Activity CodeList.
     * @param        $codeList
     * @param        $version
     * @param string $directory
     * @return array
     */
    protected function loadCodeList($codeList, $version, $directory = "Activity")
    {
        return json_decode(file_get_contents(app_path(sprintf('Core/%s/Codelist/en/%s/%s.json', $version, $directory, $codeList))), true);
    }

    protected function setOrganizationTypeNameToCode($value)
    {
        $validOrganizationType = $this->loadCodeList('OrganisationType', 'V201');
        foreach ($validOrganizationType['OrganisationType'] as $type) {
            if (ucwords($value) == $type['name']) {
                $value = $type['code'];
                break;
            }
        }

        return $value;
    }

}