<?php namespace App\Core\V201\Requests;

class ActivityElementValidation
{
    public function validateActivity($activityData, $transactionData)
    {
        $messages = [];

        if (empty($activityData->title)) {
            $messages[] = trans('validation.required', ['attribute' => trans('element.title')]);
        }

        if (empty($activityData->description)) {
            $messages[] = trans('validation.required', ['attribute' => trans('element.description')]);
        }

        if (empty($activityData->participating_organization)) {
            $messages[] = trans('validation.required', ['attribute' => trans('element.participating_organisation')]);
        }

        if (empty($activityData->activity_status)) {
            $messages[] = trans('validation.required', ['attribute' => trans('element.activity_status')]);
        }

        if (empty($activityData->activity_date)) {
            $messages[] = trans('validation.required', ['attribute' => trans('element.activity_date')]);
        }

        $transaction = [];
        if (empty($activityData->sector)) {
            if (!$transactionData->first()) {
                $messages[] = trans('validation.sector_validation');
            } else {
                foreach ($transactionData as $transactions) {
                    $transactionDetail = $transactions->transaction;
                    removeEmptyValues($transactionDetail);
                    if (empty($transactionDetail['sector'])) {
                        if ($transaction == []) {
                            $transaction[] = ['transaction' => 'it contains data'];
                            $messages[]    = trans('validation.sector_validation');
                        } else {
                            $messages[] = trans('validation.transaction_sector_validation');
                        }
                    } else {
                        $transaction[] = ['transaction' => 'it contains data'];
                    }
                }
            }
        }

        $transaction = [];
        if (!empty($activityData->sector)) {
            foreach ($transactionData as $transactions) {
                $transactionDetail = $transactions->transaction;
                removeEmptyValues($transactionDetail);
                if (!empty($transactionDetail['sector']) && $transaction == []) {
                    $transaction[] = ['transaction' => 'it contains data'];
                    $messages[]    = sprintf(
                        trans('validation.sector_in_activity_and_transaction_remove'),
                        route('remove.transactionSector', $activityData->id),
                        route('remove.activitySector', $activityData->id)
                    );
                }
            }
        }

        $transactionCountryRegion = false;

        if (empty($activityData->recipient_country) && empty($activityData->recipient_region)) {
            if (!empty($transactionData)) {
                foreach ($transactionData as $transactions) {
                    $transactionDetail = $transactions->transaction;
                    removeEmptyValues($transactionDetail);
                    if (!empty($transactionDetail['recipient_country']) || !empty($transactionDetail['recipient_region'])) {
                        $transactionCountryRegion = true;
                    } else {
                        $messages[] = trans('validation.recipient_country_or_region_required');
                    }
                }
            } else {
                $messages[] = trans('validation.recipient_country_or_region_required');
            }
        }

        $recipientCountryValue         = false;
        $activityRecipientCountryValue = false;
        $recipientRegionValue          = false;
        $activityRecipientRegionValue  = false;
        $totalPercentage               = 0;
        $percentage                    = [];

        if (!empty($activityData->recipient_country)) {
            foreach ($activityData->recipient_country as $recipientCountry) {
                if ($recipientCountry['percentage'] !== '') {
                    $percentage[] = $recipientCountry['percentage'];
                    $totalPercentage += (float) $recipientCountry['percentage'];
                    $recipientCountryValue = true;
                }
                $activityRecipientCountryValue = true;
            }
        }

        if (!empty($activityData->recipient_region)) {
            foreach ($activityData->recipient_region as $recipientRegion) {
                if ($recipientRegion['percentage'] !== '') {
                    $percentage[] = $recipientRegion['percentage'];
                    $totalPercentage += (float) $recipientRegion['percentage'];
                    $recipientRegionValue = true;
                }
                $activityRecipientRegionValue = true;
            }
        }

        $epsilon = 0.01;

        if (!(abs(100.0 - $totalPercentage) < $epsilon) && $totalPercentage != 0) {
            if ($recipientCountryValue == true && $recipientRegionValue == true) {
                $messages[] = trans('validation.sum_of_percentage', ['attribute' => trans('element.recipient_country') . ' ' . trans('global.and') . ' ' . trans('element.recipient_region')]);
            } elseif ($recipientCountryValue == true) {
                $messages[] = trans('validation.sum_of_percentage', ['attribute' => trans('element.recipient_country')]);
            } elseif ($recipientRegionValue == true) {
                $messages[] = trans('validation.sum_of_percentage', ['attribute' => trans('element.recipient_region')]);
            }
        }

        if ($transactionCountryRegion == true && ($activityRecipientCountryValue == true || $activityRecipientRegionValue == true)) {
            $messages[] = trans('validation.sector_in_activity_and_transaction');
        }

        $messageList = '';

        foreach ($messages as $message) {
            $messageList .= sprintf('<li>- %s</li>', $message);
        }

        $messageHtml = '';
        if ($messageList) {
            $messageHtml .= trans('validation.validation_before_completed');
            $messageHtml .= sprintf('<ul>%s</ul>', $messageList);
        }

        return $messageHtml;
    }
}
