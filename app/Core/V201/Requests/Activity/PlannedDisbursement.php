<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class PlannedDisbursement
 * @package App\Core\V201\Requests\Activity
 */
class PlannedDisbursement extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForPlannedDisbursement($this->get('planned_disbursement'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForPlannedDisbursement($this->get('planned_disbursement'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForPlannedDisbursement(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $plannedDisbursementIndex => $plannedDisbursement) {
            $plannedDisbursementForm = sprintf('planned_disbursement.%s', $plannedDisbursementIndex);

            $rules[sprintf('%s.planned_disbursement_type', $plannedDisbursementForm)] = 'required';
            $rules                                                                    = array_merge(
                $rules,
                $this->getRulesForPeriodStart($plannedDisbursement['period_start'], $plannedDisbursementForm),
                $this->getRulesForPeriodEnd($plannedDisbursement['period_end'], $plannedDisbursementForm),
                $this->getRulesForValue($plannedDisbursement['value'], $plannedDisbursementForm)
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

            $messages[sprintf('%s.planned_disbursement_type', $plannedDisbursementForm)] = 'required';
            $messages                                                                    = array_merge(
                $messages,
                $this->getMessagesForPeriodStart($plannedDisbursement['period_start'], $plannedDisbursementForm),
                $this->getMessagesForPeriodEnd($plannedDisbursement['period_end'], $plannedDisbursementForm),
                $this->getMessagesForValue($plannedDisbursement['value'], $plannedDisbursementForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForPeriodStart($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodStartIndex => $periodStart) {
            $rules[sprintf('%s.period_start.%s.date', $formBase, $periodStartIndex)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForPeriodStart($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodStartIndex => $periodStart) {
            $messages[sprintf('%s.period_start.%s.date.required', $formBase, $periodStartIndex)] = 'Period Start is required';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForPeriodEnd($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodEndIndex => $periodEnd) {
            $rules[sprintf('%s.period_end.%s.date', $formBase, $periodEndIndex)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForPeriodEnd($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodEndIndex => $periodEnd) {
            $messages[sprintf('%s.period_end.%s.date.required', $formBase, $periodEndIndex)] = 'Period End is required';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getRulesForValue($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $valueIndex => $value) {
            $valueForm                                   = sprintf('%s.value.%s', $formBase, $valueIndex);
            $rules[sprintf('%s.amount', $valueForm)]     = 'required|numeric';
            $rules[sprintf('%s.value_date', $valueForm)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForValue($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $valueIndex => $value) {
            $valueForm                                               = sprintf('%s.value.%s', $formBase, $valueIndex);
            $messages[sprintf('%s.amount.required', $valueForm)]     = 'Amount is Required';
            $messages[sprintf('%s.amount.numeric', $valueForm)]      = 'Amount should be numeric';
            $messages[sprintf('%s.value_date.required', $valueForm)] = 'Date is Required';
        }

        return $messages;
    }
}
