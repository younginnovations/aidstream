<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\PlannedDisbursement as V201PlannedDisbursement;

/**
 * Class PlannedDisbursement
 * @package App\Core\V202\Requests\Activity
 */
class PlannedDisbursement extends V201PlannedDisbursement
{

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForPlannedDisbursement(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $plannedDisbursementIndex => $plannedDisbursement) {
            $plannedDisbursementForm = sprintf('planned_disbursement.%s', $plannedDisbursementIndex);

            $rules = array_merge(
                $rules,
                $this->getRulesForPeriodStart($plannedDisbursement['period_start'], $plannedDisbursementForm),
                $this->getRulesForPeriodEnd($plannedDisbursement['period_end'], $plannedDisbursementForm),
                $this->getRulesForValue($plannedDisbursement['value'], $plannedDisbursementForm),
                $this->getRulesForProviderOrg($plannedDisbursement['provider_org'], $plannedDisbursementForm),
                $this->getRulesForReceiverOrg($plannedDisbursement['receiver_org'], $plannedDisbursementForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getMessagesForPlannedDisbursement(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $plannedDisbursementIndex => $plannedDisbursement) {
            $plannedDisbursementForm = sprintf('planned_disbursement.%s', $plannedDisbursementIndex);

            $messages = array_merge(
                $messages,
                $this->getMessagesForPeriodStart($plannedDisbursement['period_start'], $plannedDisbursementForm),
                $this->getMessagesForPeriodEnd($plannedDisbursement['period_end'], $plannedDisbursementForm),
                $this->getMessagesForValue($plannedDisbursement['value'], $plannedDisbursementForm),
                $this->getMessagesForProviderOrg($plannedDisbursement['provider_org'], $plannedDisbursementForm),
                $this->getMessagesForReceiverOrg($plannedDisbursement['receiver_org'], $plannedDisbursementForm)
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
            $providerOrgForm = sprintf('%s.provider_org.%s', $formBase, $providerOrgIndex);
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
            $providerOrgForm = sprintf('%s.provider_org.%s', $formBase, $providerOrgIndex);
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
            $receiverOrgForm = sprintf('%s.receiver_org.%s', $formBase, $receiverOrgIndex);
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
            $receiverOrgForm = sprintf('%s.receiver_org.%s', $formBase, $receiverOrgIndex);
            $message         = array_merge(
                $message,
                $this->getMessagesForNarrative($receiverOrg['narrative'], $receiverOrgForm)
            );
        }

        return $message;
    }
}
