<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Repositories\Activity\UploadTransaction;
use App\Core\V201\Requests\Activity\Transaction as V201Transaction;

/**
 * Class Transaction
 * @package App\Core\V202\Requests\Activity
 */
class Transaction extends V201Transaction
{
    /**
     * Transaction constructor.
     * @param UploadTransaction $transactionRepo
     */
    public function __construct(UploadTransaction $transactionRepo)
    {
        parent::__construct($transactionRepo);
    }

    /**
     * returns rules for transaction
     * @param $formFields
     * @return array|mixed
     */
    protected function getTransactionRules($formFields)
    {
        $rules = [];

        foreach ($formFields as $transactionIndex => $transaction) {
            $transactionForm = sprintf('transaction.%s', $transactionIndex);
            $transactionId   = $this->segment(4);
            $activityId      = $this->segment(2);
            $references      = ($transactionId) ? $this->transactionRepo->getTransactionReferencesExcept(
                $activityId,
                $transactionId
            ) : $this->transactionRepo->getTransactionReferences($activityId);

            $transactionReferences = [];
            foreach ($references as $referenceKey => $reference) {
                $transactionReferences[] = $referenceKey;
            }

            $transactionReference                                                                        = implode(',', $transactionReferences);
            $rules                                                                                       = [];
            $rules[sprintf('%s.reference', $transactionForm)]                                            = 'not_in:' . $transactionReference;
            $rules[sprintf('%s.disbursement_channel.0.disbursement_channel_code', $transactionForm)]     = 'required';
            $rules[sprintf('%s.provider_organization.0.organization_identifier_code', $transactionForm)] = 'exclude_operators';
            $rules[sprintf('%s.receiver_organization.0.organization_identifier_code', $transactionForm)] = 'exclude_operators';

            $rules = array_merge(
                $rules,
                $this->getTransactionTypeRules($transaction['transaction_type'], $transactionForm),
                $this->getTransactionDateRules($transaction['transaction_date'], $transactionForm),
                $this->getValueRules($transaction['value'], $transactionForm),
                $this->getDescriptionRules($transaction['description'], $transactionForm),
                $this->getSectorsRules($transaction['sector'], $transactionForm),
                $this->getRecipientRegionRules($transaction['recipient_region'], $transactionForm),
                $this->getRulesForProviderOrg($transaction['provider_organization'], $transactionForm),
                $this->getRulesForReceiverOrg($transaction['receiver_organization'], $transactionForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for transaction
     * @param $formFields
     * @return array|mixed
     */
    protected function getTransactionMessages($formFields)
    {
        $messages = [];

        foreach ($formFields as $transactionIndex => $transaction) {
            $transactionForm                                                                                     = sprintf('transaction.%s', $transactionIndex);
            $messages[sprintf('%s.reference.not_in', $transactionForm)]                                          = 'Reference should be unique';
            $messages[sprintf('%s.disbursement_channel.0.disbursement_channel_code.required', $transactionForm)] = 'Disbursement Channel Code is required.';

            $messages = array_merge(
                $messages,
                $this->getTransactionTypeMessages($transaction['transaction_type'], $transactionForm),
                $this->getTransactionDateMessages($transaction['transaction_date'], $transactionForm),
                $this->getValueMessages($transaction['value'], $transactionForm),
                $this->getDescriptionMessages($transaction['description'], $transactionForm),
                $this->getSectorsMessages($transaction['sector'], $transactionForm),
                $this->getRecipientRegionMessages($transaction['recipient_region'], $transactionForm),
                $this->getMessagesForProviderOrg($transaction['provider_organization'], $transactionForm),
                $this->getMessagesForReceiverOrg($transaction['receiver_organization'], $transactionForm)
            );
        }

        return $messages;
    }

    /**
     * @param array $formFields
     * @param       $formBase
     * @return array
     */
    protected function getRulesForProviderOrg(array $formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $providerOrgIndex => $providerOrg) {
            $providerOrgForm = sprintf('%s.provider_organization.%s', $formBase, $providerOrgIndex);
            $rules           = array_merge(
                $rules,
                $this->getRulesForNarrative($providerOrg['narrative'], $providerOrgForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @param       $formBase
     * @return array
     */
    protected function getMessagesForProviderOrg(array $formFields, $formBase)
    {
        $message = [];

        foreach ($formFields as $providerOrgIndex => $providerOrg) {
            $providerOrgForm = sprintf('%s.provider_organization.%s', $formBase, $providerOrgIndex);
            $message         = array_merge(
                $message,
                $this->getMessagesForNarrative($providerOrg['narrative'], $providerOrgForm)
            );
        }

        return $message;
    }

    /**
     * @param array $formFields
     * @param       $formBase
     * @return array
     */
    protected function getRulesForReceiverOrg(array $formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $receiverOrgIndex => $receiverOrg) {
            $receiverOrgForm = sprintf('%s.receiver_organization.%s', $formBase, $receiverOrgIndex);
            $rules           = array_merge(
                $rules,
                $this->getRulesForNarrative($receiverOrg['narrative'], $receiverOrgForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @param       $formBase
     * @return array
     */
    protected function getMessagesForReceiverOrg(array $formFields, $formBase)
    {
        $message = [];

        foreach ($formFields as $receiverOrgIndex => $receiverOrg) {
            $receiverOrgForm = sprintf('%s.receiver_organization.%s', $formBase, $receiverOrgIndex);
            $message         = array_merge(
                $message,
                $this->getMessagesForNarrative($receiverOrg['narrative'], $receiverOrgForm)
            );
        }

        return $message;
    }

    /**
     * returns rules for sector
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    public function getSectorsRules($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $sectorIndex => $sector) {
            $sectorForm                                       = sprintf('%s.sector.%s', $formBase, $sectorIndex);
            $rules[sprintf('%s.vocabulary_uri', $sectorForm)] = 'url';
            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                $rules[sprintf('%s.sector_code', $sectorForm)] = 'required';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $rules[sprintf('%s.sector_category_code', $sectorForm)] = 'required';
            } else {
                $rules[sprintf('%s.sector_text', $sectorForm)] = 'required';
            }
            $rules = array_merge($rules, $this->getRulesForNarrative($sector['narrative'], $sectorForm));
        }

        return $rules;
    }

    /**
     * returns messages for sector
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    public function getSectorsMessages($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $sectorIndex => $sector) {
            $sectorForm                                              = sprintf('%s.sector.%s', $formBase, $sectorIndex);
            $messages[sprintf('%s.vocabulary_uri.url', $sectorForm)] = 'Enter valid URL. eg. http://example.com';
            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                $messages[sprintf('%s.sector_code.%s', $sectorForm, 'required')] = 'Sector is required.';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $messages[sprintf('%s.sector_category_code.%s', $sectorForm, 'required')] = 'Sector is required.';
            } else {
                $messages[sprintf('%s.sector_text.%s', $sectorForm, 'required')] = 'Sector is required.';
            }
            $messages = array_merge($messages, $this->getMessagesForNarrative($sector['narrative'], $sectorForm));
        }

        return $messages;
    }

    /**
     * returns rules for recipient region
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    public function getRecipientRegionRules($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                             = sprintf('%s.recipient_region.%s', $formBase, $recipientRegionIndex);
            $rules[$recipientRegionForm . '.region_code']    = 'required';
            $rules[$recipientRegionForm . '.vocabulary_uri'] = 'url';
            $rules                                           = array_merge($rules, $this->getRulesForNarrative($recipientRegion['narrative'], $recipientRegionForm));
        }

        return $rules;
    }

    /**
     * returns messages for recipient region
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    public function getRecipientRegionMessages($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionForm                                      = sprintf('%s.recipient_region.%s', $formBase, $recipientRegionIndex);
            $messages[$recipientRegionForm . '.region_code.required'] = 'Recipient region code is required';
            $messages[$recipientRegionForm . '.vocabulary_uri.url']   = 'Enter valid URL. eg. http://example.com';
            $messages                                                 = array_merge($messages, $this->getMessagesForNarrative($recipientRegion['narrative'], $recipientRegionForm));
        }

        return $messages;
    }
}
